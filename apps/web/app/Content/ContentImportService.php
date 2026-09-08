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
