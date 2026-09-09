<?php

declare(strict_types=1);

namespace App\Content;

use App\Enums\GameStatus;
use App\Models\ContentImportBatch;
use App\Models\Game;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class ContentImportService
{
    public function __construct(private readonly GameContentWorkflow $workflow, private readonly AuditWriter $audit) {}

    public function preview(User $actor, string $json): ContentImportBatch
    {
        try {
            $payload = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw ValidationException::withMessages(['payload' => 'JSON معتبر نیست']);
        }
        if (! is_array($payload) || ! array_is_list($payload) || $payload === [] || count($payload) > 100) {
            throw ValidationException::withMessages(['payload' => 'ورودی باید آرایه‌ای شامل ۱ تا ۱۰۰ بازی باشد']);
        }
        $rules = [
            'slug' => ['required', 'alpha_dash:ascii', 'max:120', 'distinct', 'unique:games,slug'],
            'title' => ['required', 'string', 'max:180'], 'summary' => ['required', 'string', 'max:1000'],
            'instructions' => ['required', 'array', 'min:1', 'max:20'], 'instructions.*' => ['required', 'string', 'max:1000'],
            'safety_copy' => ['required', 'string', 'max:2000'], 'contraindications' => ['sometimes', 'array', 'max:20'],
            'contraindications.*' => ['string', 'max:500'], 'supervision_level' => ['required', 'in:within_reach,same_room,check_in'],
            'metadata' => ['required', 'array'],
            'metadata.age_band' => ['required', 'exists:age_bands,code'],
            'metadata.minimum_age_months' => ['required', 'integer', 'min:6', 'max:155'],
            'metadata.maximum_age_months_exclusive' => ['required', 'integer', 'gt:metadata.minimum_age_months', 'max:156'],
            'metadata.duration_min_minutes' => ['required', 'integer', 'min:1', 'max:240'],
            'metadata.duration_max_minutes' => ['required', 'integer', 'gte:metadata.duration_min_minutes', 'max:360'],
            'metadata.prep_time_minutes' => ['required', 'integer', 'min:0', 'max:120'],
            'metadata.space_required' => ['required', 'in:lap,small,room,large,outdoor'],
            'metadata.noise_level' => ['required', 'in:quiet,moderate,loud'],
            'metadata.mess_level' => ['required', 'in:none,light,messy'],
            'metadata.minimum_children' => ['required', 'integer', 'min:1', 'max:20'],
            'metadata.maximum_children' => ['required', 'integer', 'gte:metadata.minimum_children', 'max:30'],
            'metadata.minimum_adults' => ['required', 'integer', 'min:0', 'max:5'],
            'metadata.required_adult' => ['required', 'boolean'],
            'metadata.child_energy' => ['required', 'exists:energy_levels,slug'],
            'metadata.caregiver_energy' => ['required', 'exists:energy_levels,slug'],
            'metadata.interaction_type' => ['required', 'in:side_by_side,cooperative,competitive,pretend,conversation'],
            'metadata.caregiver_involvement' => ['required', 'in:active,shared,light'],
            'metadata.setup_complexity' => ['required', 'in:none,simple,moderate'],
            'metadata.source_title' => ['required', 'string', 'max:255'],
            'metadata.source_url' => ['required', 'url:http,https', 'max:2048'],
            'metadata.cultural_origin' => ['required', 'string', 'max:120'],
            'metadata.situations' => ['required', 'array', 'min:1'],
            'metadata.situations.*' => ['required', 'distinct', 'exists:situations,slug'],
            'metadata.locations' => ['required', 'array', 'min:1'],
            'metadata.locations.*' => ['required', 'distinct', 'exists:locations,slug'],
            'metadata.moods' => ['required', 'array', 'min:1'],
            'metadata.moods.*' => ['required', 'distinct', 'exists:moods,slug'],
            'metadata.tags' => ['required', 'array', 'min:1'],
            'metadata.tags.*' => ['required', 'distinct', 'exists:tags,slug'],
            'metadata.player_requirement' => ['required', 'exists:player_requirements,slug'],
            'metadata.materials' => ['present', 'array', 'max:12'],
            'metadata.materials.*.slug' => ['required', 'distinct', 'exists:materials,slug'],
            'metadata.materials.*.requirement' => ['required', 'in:required,optional'],
            'metadata.materials.*.quantity_note' => ['nullable', 'string', 'max:255'],
            'metadata.safety_flags' => ['required', 'array', 'min:1'],
            'metadata.safety_flags.*' => ['required', 'distinct', 'exists:safety_rules,code'],
        ];
        foreach ($payload as $index => $row) {
            $validator = Validator::make(is_array($row) ? $row : [], $rules);
            if ($validator->fails()) {
                throw ValidationException::withMessages(['payload' => 'ردیف '.($index + 1).': '.$validator->errors()->first()]);
            }
            $payload[$index] = $validator->validated();
        }
        if (count(array_unique(array_column($payload, 'slug'))) !== count($payload)) {
            throw ValidationException::withMessages(['payload' => 'slug تکراری در فایل وجود دارد']);
        }
        $batch = ContentImportBatch::query()->create(['actor_user_id' => $actor->id, 'status' => 'previewed', 'payload_json' => $payload]);
        $this->audit->write($actor, 'content.import.previewed', $batch, null, ['items' => count($payload)]);

        return $batch;
    }

    public function confirm(User $actor, ContentImportBatch $batch): ContentImportBatch
    {
        $this->guardOwner($actor, $batch);
        if ($batch->status === 'confirmed') {
            return $batch;
        }
        if ($batch->status !== 'previewed') {
            throw new DomainException('Only a previewed batch can be confirmed.');
        }

        return DB::transaction(function () use ($actor, $batch): ContentImportBatch {
            $manifest = [];
            foreach ($batch->payload_json as $row) {
                $game = $this->workflow->createDraft($actor, $row);
                $manifest[] = ['game_id' => $game->id, 'version_id' => $game->versions()->value('id')];
            }
            $batch->update(['status' => 'confirmed', 'manifest_json' => $manifest, 'confirmed_at' => now()]);
            $this->audit->write($actor, 'content.import.confirmed', $batch, ['status' => 'previewed'], ['status' => 'confirmed', 'items' => count($manifest)]);

            return $batch->fresh();
        });
    }

    public function rollback(User $actor, ContentImportBatch $batch): ContentImportBatch
    {
        $this->guardOwner($actor, $batch);
        if ($batch->status === 'rolled_back') {
            return $batch;
        }
        if ($batch->status !== 'confirmed') {
            throw new DomainException('Only a confirmed batch can be rolled back.');
        }

        return DB::transaction(function () use ($actor, $batch): ContentImportBatch {
            foreach ($batch->manifest_json ?? [] as $entry) {
                $game = Game::query()->lockForUpdate()->findOrFail($entry['game_id']);
                if ($game->status !== GameStatus::Draft || $game->current_published_version_id !== null) {
                    throw new DomainException('Rollback stopped because a batch game is no longer an unpublished draft.');
                }
            }
            foreach (array_reverse($batch->manifest_json ?? []) as $entry) {
                DB::table('game_versions')->where('id', $entry['version_id'])->delete();
                DB::table('games')->where('id', $entry['game_id'])->delete();
            }
            $batch->update(['status' => 'rolled_back', 'rolled_back_at' => now()]);
            $this->audit->write($actor, 'content.import.rolled_back', $batch, ['status' => 'confirmed'], ['status' => 'rolled_back']);

            return $batch->fresh();
        });
    }

    private function guardOwner(User $actor, ContentImportBatch $batch): void
    {
        if ((int) $batch->actor_user_id !== (int) $actor->id && ! $actor->hasPermission('roles.manage')) {
            throw new DomainException('This import belongs to another staff account.');
        }
    }
}
