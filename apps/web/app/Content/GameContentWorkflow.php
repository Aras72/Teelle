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
            if (isset($data['metadata'])) {
                $this->replaceStructuredMetadata($version, $data['metadata']);
            }
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
            foreach (['game_age_ranges', 'game_situations', 'game_locations', 'game_energy_levels', 'game_moods', 'game_player_requirements', 'game_tags', 'game_materials', 'game_safety_rules', 'game_media'] as $table) {
                $rows = DB::table($table)->where('game_version_id', $source->id)->get();
                foreach ($rows as $row) {
                    $copy = (array) $row;
                    unset($copy['id']);
                    $copy['game_version_id'] = $version->id;
                    DB::table($table)->insert($copy);
                }
            }
            $facts = DB::table('game_facts')->where('game_version_id', $source->id)->first();
            if ($facts) {
                $copy = (array) $facts;
                unset($copy['id']);
                $copy['game_version_id'] = $version->id;
                $copy['created_at'] = now();
                $copy['updated_at'] = now();
                DB::table('game_facts')->insert($copy);
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

    public function updateStructuredMetadata(User $actor, GameVersion $version, array $metadata): void
    {
        if ($version->status !== GameVersionStatus::Draft) {
            throw new DomainException('Metadata can only be changed on a draft.');
        }
        DB::transaction(function () use ($actor, $version, $metadata): void {
            $this->replaceStructuredMetadata($version, $metadata);
            $this->audit->write($actor, 'content.version.structured_metadata_updated', $version, null, $metadata);
        });
    }

    public function review(User $actor, GameVersion $version, string $decision, ?string $notes = null, array $scopeDecisions = []): void
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
        $requiredScopes = ['copy', 'source', 'age', 'safety', 'cover'];
        if ($scopeDecisions !== []) {
            $providedScopes = array_keys($scopeDecisions);
            sort($providedScopes);
            $sortedRequiredScopes = $requiredScopes;
            sort($sortedRequiredScopes);
            if ($providedScopes !== $sortedRequiredScopes
                || array_diff(array_values($scopeDecisions), ['approved', 'changes_requested']) !== []) {
                throw new DomainException('Review scope decisions are incomplete or invalid.');
            }
            $derivedDecision = in_array('changes_requested', $scopeDecisions, true) ? 'changes_requested' : 'approved';
            if ($decision !== $derivedDecision) {
                throw new DomainException('Review decision does not match its scope decisions.');
            }
        }
        $scopeHash = $this->hash->reviewScope($version);
        DB::transaction(function () use ($actor, $version, $decision, $notes, $scopeHash, $scopeDecisions): void {
            ContentReview::query()->create(['game_version_id' => $version->id, 'reviewer_id' => $actor->id,
                'decision' => $decision, 'scope_hash' => $scopeHash, 'scope_decisions' => $scopeDecisions ?: null,
                'notes' => $notes, 'reviewed_at' => now()]);
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
            ->where('scope_hash', $this->hash->reviewScope($version))->latest('reviewed_at')->exists();
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

        return DB::table('game_facts')->where('game_version_id', $id)->exists()
            && DB::table('game_age_ranges')->where('game_version_id', $id)->exists()
            && DB::table('game_situations')->where('game_version_id', $id)->exists()
            && DB::table('game_locations')->where('game_version_id', $id)->exists()
            && DB::table('game_player_requirements')->where('game_version_id', $id)->exists()
            && DB::table('game_safety_rules')->where('game_version_id', $id)->exists() && $cover;
    }

    private function replaceStructuredMetadata(GameVersion $version, array $metadata): void
    {
        DB::table('game_facts')->updateOrInsert(['game_version_id' => $version->id], [
            'duration_min_minutes' => $metadata['duration_min_minutes'],
            'duration_max_minutes' => $metadata['duration_max_minutes'],
            'prep_time_minutes' => $metadata['prep_time_minutes'],
            'space_required' => $metadata['space_required'], 'noise_level' => $metadata['noise_level'],
            'mess_level' => $metadata['mess_level'], 'minimum_children' => $metadata['minimum_children'],
            'maximum_children' => $metadata['maximum_children'], 'minimum_adults' => $metadata['minimum_adults'],
            'required_adult' => $metadata['required_adult'], 'child_energy' => $metadata['child_energy'],
            'caregiver_energy' => $metadata['caregiver_energy'], 'interaction_type' => $metadata['interaction_type'],
            'caregiver_involvement' => $metadata['caregiver_involvement'], 'setup_complexity' => $metadata['setup_complexity'],
            'source_title' => $metadata['source_title'], 'source_url' => $metadata['source_url'],
            'cultural_origin' => $metadata['cultural_origin'], 'created_at' => now(), 'updated_at' => now(),
            'alternatives' => json_encode($this->alternatives($metadata), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'content_priority' => $metadata['content_priority'] ?? 'normal',
            'priority_reason' => $metadata['priority_reason'] ?? null,
        ]);
        DB::table('game_age_ranges')->updateOrInsert(['game_version_id' => $version->id], [
            'age_band_id' => DB::table('age_bands')->where('code', $metadata['age_band'])->value('id'),
            'minimum_age_months' => $metadata['minimum_age_months'],
            'maximum_age_months_exclusive' => $metadata['maximum_age_months_exclusive'],
        ]);
        $this->replaceSlugPivots($version->id, 'game_situations', 'situations', 'situation_id', $metadata['situations']);
        $this->replaceSlugPivots($version->id, 'game_locations', 'locations', 'location_id', $metadata['locations']);
        $this->replaceSlugPivots($version->id, 'game_energy_levels', 'energy_levels', 'energy_level_id', array_values(array_unique([$metadata['child_energy'], $metadata['caregiver_energy']])));
        $this->replaceSlugPivots($version->id, 'game_moods', 'moods', 'mood_id', $metadata['moods']);
        $this->replaceSlugPivots($version->id, 'game_tags', 'tags', 'tag_id', $metadata['tags']);
        $this->replaceSlugPivots($version->id, 'game_player_requirements', 'player_requirements', 'player_requirement_id', [$metadata['player_requirement']]);

        DB::table('game_materials')->where('game_version_id', $version->id)->delete();
        foreach ($metadata['materials'] as $material) {
            DB::table('game_materials')->insert(['game_version_id' => $version->id,
                'material_id' => DB::table('materials')->where('slug', $material['slug'])->value('id'),
                'requirement' => $material['requirement'], 'quantity_note' => $material['quantity_note'] ?? null,
                'substitute_for_material_id' => null]);
        }
        DB::table('game_safety_rules')->where('game_version_id', $version->id)->delete();
        foreach ($metadata['safety_flags'] as $code) {
            DB::table('game_safety_rules')->insert(['game_version_id' => $version->id,
                'safety_rule_id' => DB::table('safety_rules')->where('code', $code)->value('id'), 'hard_filter' => true]);
        }
    }

    /**
     * گزینه‌های چندگانه انتخاب‌شده برای فیلدهای دسته‌ای؛ اضافه‌ای و بدون اثر روی موتور تطبیق.
     *
     * @param  array<string, mixed>  $metadata
     * @return array<string, array<int, string>>
     */
    private function alternatives(array $metadata): array
    {
        $alternatives = [];
        foreach (['player_requirement' => 'player_requirements', 'child_energy' => 'energy_levels',
            'caregiver_energy' => 'energy_levels', 'interaction_type' => null, 'caregiver_involvement' => null,
            'setup_complexity' => null, 'space_required' => null, 'noise_level' => null, 'mess_level' => null,
            'supervision_level' => null,
        ] as $field => $taxonomy) {
            $extra = $metadata['alternatives'][$field] ?? [];
            if (is_array($extra) && $extra !== []) {
                $alternatives[$field] = array_values(array_filter(array_map('strval', $extra)));
            }
        }
        foreach (['situations', 'locations', 'moods', 'tags', 'safety_flags'] as $field) {
            $extra = $metadata['alternatives'][$field] ?? [];
            if (is_array($extra) && $extra !== []) {
                $alternatives[$field] = array_values(array_filter(array_map('strval', $extra)));
            }
        }

        return $alternatives;
    }

    private function replaceSlugPivots(int $versionId, string $pivot, string $taxonomy, string $foreignKey, array $slugs): void
    {
        DB::table($pivot)->where('game_version_id', $versionId)->delete();
        foreach ($slugs as $slug) {
            DB::table($pivot)->insert(['game_version_id' => $versionId,
                $foreignKey => DB::table($taxonomy)->where('slug', $slug)->value('id'), 'weight' => 100, 'is_constraint' => true]);
        }
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
