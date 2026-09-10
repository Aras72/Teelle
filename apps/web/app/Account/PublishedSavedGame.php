<?php

declare(strict_types=1);

namespace App\Account;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\GamePublication;
use Illuminate\Support\Facades\DB;

final class PublishedSavedGame
{
    public function isAvailable(Game $game): bool
    {
        if ($game->status !== GameStatus::Published || ! $game->current_published_version_id) {
            return false;
        }

        return GamePublication::query()->where('game_id', $game->id)
            ->where('game_version_id', $game->current_published_version_id)->whereNull('unpublished_at')->exists()
            && DB::table('content_reviews')->where('game_version_id', $game->current_published_version_id)
                ->latest('reviewed_at')->latest('id')->value('decision') === 'approved'
            && DB::table('game_facts')->where('game_version_id', $game->current_published_version_id)->exists()
            && DB::table('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
                ->where('game_media.game_version_id', $game->current_published_version_id)->where('game_media.role', 'cover')
                ->where('media_assets.status', 'reviewed')->exists();
    }

    public function cover(Game $game): ?object
    {
        if (! $this->isAvailable($game)) {
            return null;
        }

        return DB::table('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
            ->where('game_media.game_version_id', $game->current_published_version_id)->where('game_media.role', 'cover')
            ->where('media_assets.status', 'reviewed')->whereIn('media_assets.mime', ['image/jpeg', 'image/png', 'image/webp'])
            ->first(['media_assets.disk', 'media_assets.path', 'media_assets.mime']);
    }
}
