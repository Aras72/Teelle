<?php

declare(strict_types=1);

namespace App\Content;

use App\Enums\GameStatus;
use App\Enums\GameVersionStatus;
use App\Models\ContentReview;
use App\Models\Game;
use App\Models\GamePublication;
use App\Models\GameVersion;
use App\Models\MediaAsset;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\DB;

final class GameContentWorkflow
{
    public function __construct(private readonly ContentHash $hash, private readonly AuditWriter $audit) {}

    public function createDraft(User $actor, array $data): Game
    {
        return DB::transaction(function () use ($actor, $data): Game {
            $game = Game::query()->create(['slug' => $data['slug'], 'status' => GameStatus::Draft]);
            $version = $game->versions()->create($this->versionPayload($actor, $data, 1));
            $this->audit->write($actor, 'content.game.created', $game, null, ['version_id' => $version->id, 'slug' => $game->slug]);

            return $game;
        });
    }

    public function updateDraft(User $actor, GameVersion $version, array $data): GameVersion
    {
        if ($version->status !== GameVersionStatus::Draft) {
            throw new DomainException('Only draft versions can be edited.');
        }
        $before = $version->only(['title', 'summary', 'instructions', 'safety_copy', 'contraindications', 'supervision_level', 'content_hash']);
        $version->update($this->contentPayload($data));
        $this->audit->write($actor, 'content.version.updated', $version, $before, $version->fresh()->only(array_keys($before)));

        return $version->fresh();
    }

    public function createRevision(User $actor, Game $game, array $data): GameVersion
    {
        $source = $game->versions()->latest('version_no')->firstOrFail();
        $content = $this->contentPayload($data);
        if ($content['content_hash'] === $source->content_hash) {
            throw new DomainException('A new version must contain a real content change.');
        }

        return DB::transaction(function () use ($actor, $game, $source, $content): GameVersion {
            $version = $game->versions()->create([
                'created_by' => $actor->id, 'version_no' => $source->version_no + 1, 'status' => GameVersionStatus::Draft,
            ] + $content);
            foreach (['game_age_ranges', 'game_locations', 'game_player_requirements', 'game_safety_rules', 'game_media'] as $table) {
                $rows = DB::table($table)->where('game_version_id', $source->id)->get();
                foreach ($rows as $row) {
                    $copy = (array) $row;
                    unset($copy['id']);
                    $copy['game_version_id'] = $version->id;
                    DB::table($table)->insert($copy);
                }
            }
            if ($game->status !== GameStatus::Published) {
                $game->update(['status' => GameStatus::Draft]);
            }
            $this->audit->write($actor, 'content.version.created', $version, ['source_version_id' => $source->id], ['version_no' => $version->version_no]);

            return $version;
        });
    }

    public function submit(User $actor, GameVersion $version): void
    {
        if ($version->status !== GameVersionStatus::Draft) {
            throw new DomainException('Only drafts can be submitted.');
        }
        DB::transaction(function () use ($actor, $version): void {
            $version->update(['status' => GameVersionStatus::InReview, 'submitted_by' => $actor->id, 'submitted_at' => now()]);
            $version->game()->update(['status' => GameStatus::InReview]);
            $this->audit->write($actor, 'content.version.submitted', $version, ['status' => 'draft'], ['status' => 'in_review']);
        });
    }

    public function updateRequiredMetadata(User $actor, GameVersion $version, array $data): void
    {
        if ($version->status !== GameVersionStatus::Draft) {
            throw new DomainException('Metadata can only be changed on a draft.');
        }
        DB::transaction(function () use ($actor, $version, $data): void {
            DB::table('game_age_ranges')->where('game_version_id', $version->id)->delete();
            DB::table('game_locations')->where('game_version_id', $version->id)->delete();
            DB::table('game_player_requirements')->where('game_version_id', $version->id)->delete();
            DB::table('game_safety_rules')->where('game_version_id', $version->id)->delete();
            DB::table('game_age_ranges')->insert(['game_version_id' => $version->id, 'age_band_id' => null,
                'minimum_age_months' => $data['minimum_age_months'], 'maximum_age_months_exclusive' => $data['maximum_age_months_exclusive']]);
            DB::table('game_locations')->insert(['game_version_id' => $version->id, 'location_id' => $data['location_id'], 'weight' => 100, 'is_constraint' => true]);
            DB::table('game_player_requirements')->insert(['game_version_id' => $version->id, 'player_requirement_id' => $data['player_requirement_id'], 'weight' => 100, 'is_constraint' => true]);
            DB::table('game_safety_rules')->insert(['game_version_id' => $version->id, 'safety_rule_id' => $data['safety_rule_id'], 'hard_filter' => true]);
            $this->audit->write($actor, 'content.version.metadata_updated', $version, null, $data);
        });
    }

    public function review(User $actor, GameVersion $version, string $decision, ?string $notes = null): void
    {
        if ($version->status !== GameVersionStatus::InReview) {
            throw new DomainException('Only submitted versions can be reviewed.');
        }
        if ((int) $version->created_by === (int) $actor->id) {
            throw new DomainException('Self-review is prohibited.');
        }
        if ($this->hash->make($version->toArray()) !== $version->content_hash) {
            throw new DomainException('Content changed after its review hash was created.');
        }
        if (! in_array($decision, ['approved', 'changes_requested'], true)) {
            throw new DomainException('Invalid review decision.');
        }
        DB::transaction(function () use ($actor, $version, $decision, $notes): void {
            ContentReview::query()->create(['game_version_id' => $version->id, 'reviewer_id' => $actor->id,
                'decision' => $decision, 'scope_hash' => $version->content_hash, 'notes' => $notes, 'reviewed_at' => now()]);
            $approved = $decision === 'approved';
            $version->update(['status' => $approved ? GameVersionStatus::Approved : GameVersionStatus::Draft, 'approved_at' => $approved ? now() : null]);
            $version->game()->update(['status' => $approved ? GameStatus::Approved : GameStatus::Draft]);
            $this->audit->write($actor, 'content.version.'.$decision, $version, ['status' => 'in_review'], ['status' => $version->status->value], $notes);
        });
    }

    public function publish(User $actor, GameVersion $version): void
    {
        if ((int) $version->created_by === (int) $actor->id) {
            throw new DomainException('Self-publication is prohibited.');
        }
        if ($version->status !== GameVersionStatus::Approved || ! $this->isComplete($version)) {
            throw new DomainException('The approved version is incomplete for publication.');
        }
        $approved = ContentReview::query()->where('game_version_id', $version->id)->where('decision', 'approved')
            ->where('scope_hash', $version->content_hash)->latest('reviewed_at')->exists();
        if (! $approved) {
            throw new DomainException('A current independent approval is required.');
        }
        DB::transaction(function () use ($actor, $version): void {
            GamePublication::query()->where('game_id', $version->game_id)->whereNull('unpublished_at')->update([
                'unpublished_at' => now(), 'unpublished_by' => $actor->id, 'unpublish_reason' => 'نسخه جدید منتشر شد',
            ]);
            GamePublication::query()->create(['game_id' => $version->game_id, 'game_version_id' => $version->id,
                'published_by' => $actor->id, 'published_at' => now()]);
            $version->game()->update(['status' => GameStatus::Published, 'current_published_version_id' => $version->id]);
            $this->audit->write($actor, 'content.game.published', $version->game, null, ['version_id' => $version->id]);
        });
    }

    public function unpublish(User $actor, Game $game, string $reason): void
    {
        if ($game->status !== GameStatus::Published || blank($reason)) {
            throw new DomainException('A published game and reason are required.');
        }
        DB::transaction(function () use ($actor, $game, $reason): void {
            GamePublication::query()->where('game_id', $game->id)->whereNull('unpublished_at')->update([
                'unpublished_at' => now(), 'unpublished_by' => $actor->id, 'unpublish_reason' => $reason,
            ]);
            $before = ['version_id' => $game->current_published_version_id, 'status' => 'published'];
            $game->update(['status' => GameStatus::Unpublished, 'current_published_version_id' => null]);
            $this->audit->write($actor, 'content.game.unpublished', $game, $before, ['status' => 'unpublished'], $reason);
        });
    }

    public function reviewMedia(User $actor, MediaAsset $asset): void
    {
        if ((int) $asset->uploaded_by === (int) $actor->id) {
            throw new DomainException('Self-review of media is prohibited.');
        }
        $asset->update(['status' => 'reviewed']);
        $this->audit->write($actor, 'content.media.reviewed', $asset, ['status' => 'quarantined'], ['status' => 'reviewed']);
    }

    private function isComplete(GameVersion $version): bool
    {
        $id = $version->id;
        $cover = DB::table('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
            ->where('game_media.game_version_id', $id)->where('game_media.role', 'cover')->where('media_assets.status', 'reviewed')
            ->whereNotNull('media_assets.alt_text')->whereNotNull('game_media.crop_data')->exists();

        return DB::table('game_age_ranges')->where('game_version_id', $id)->exists()
            && DB::table('game_locations')->where('game_version_id', $id)->exists()
            && DB::table('game_player_requirements')->where('game_version_id', $id)->exists()
            && DB::table('game_safety_rules')->where('game_version_id', $id)->exists() && $cover;
    }

    private function versionPayload(User $actor, array $data, int $version): array
    {
        return ['created_by' => $actor->id, 'version_no' => $version, 'status' => GameVersionStatus::Draft] + $this->contentPayload($data);
    }

    private function contentPayload(array $data): array
    {
        $content = ['title' => $data['title'], 'summary' => $data['summary'], 'instructions' => array_values($data['instructions']),
            'safety_copy' => $data['safety_copy'], 'contraindications' => array_values($data['contraindications'] ?? []),
            'supervision_level' => $data['supervision_level']];

        return $content + ['content_hash' => $this->hash->make($content)];
    }
}
