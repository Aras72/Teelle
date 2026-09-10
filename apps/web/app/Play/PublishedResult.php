<?php

declare(strict_types=1);

namespace App\Play;

use App\Enums\GameStatus;
use App\Enums\MatchOutcome;
use App\Models\GamePublication;
use App\Models\MatchResult;
use App\Models\MatchSession;
use Illuminate\Support\Facades\DB;

final class PublishedResult
{
    public function hasCompleteSet(MatchSession $match): bool
    {
        if ($match->outcome !== MatchOutcome::Matched) {
            return false;
        }

        $results = $match->orderedResults()->with(['game', 'gameVersion.facts'])->get();

        return $results->count() === 3
            && $results->every(fn (MatchResult $result): bool => $this->isAvailable($result)
                && $result->gameVersion?->facts !== null
                && filled($result->explanation_json['summary'] ?? null));
    }

    public function isAvailable(MatchResult $result): bool
    {
        $game = $result->game;
        if (! $game || $game->status !== GameStatus::Published || (int) $game->current_published_version_id !== (int) $result->game_version_id) {
            return false;
        }

        $activePublication = GamePublication::query()->where('game_id', $result->game_id)
            ->where('game_version_id', $result->game_version_id)->whereNull('unpublished_at')->exists();
        $lastReview = DB::table('content_reviews')->where('game_version_id', $result->game_version_id)
            ->latest('reviewed_at')->latest('id')->value('decision');
        $completeFacts = DB::table('game_facts')->where('game_version_id', $result->game_version_id)->exists();
        $reviewedCover = DB::table('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
            ->where('game_media.game_version_id', $result->game_version_id)->where('game_media.role', 'cover')
            ->where('media_assets.status', 'reviewed')->exists();

        return $activePublication && $lastReview === 'approved' && $completeFacts && $reviewedCover;
    }
}
