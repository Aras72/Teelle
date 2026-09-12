<?php

declare(strict_types=1);

namespace App\Collections;

use App\Enums\GameStatus;
use App\Models\EditorialCollection;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class PublicCollectionCatalog
{
    public function collections(): Collection
    {
        return EditorialCollection::query()->where('status', 'published')->whereNotNull('published_at')
            ->whereHas('games', fn (Builder $query) => $this->constrainAvailable($query))
            ->orderByDesc('published_at')->get();
    }

    public function collection(EditorialCollection $collection): EditorialCollection
    {
        abort_unless($collection->status === 'published' && $collection->published_at !== null, 404);
        $games = $this->constrainAvailable($collection->games()->getQuery())
            ->select('games.*')->with('currentPublishedVersion')->get();
        abort_if($games->isEmpty(), 404);
        $collection->setRelation('games', $games);

        return $collection;
    }

    public function cover(EditorialCollection $collection, Game $game): ?object
    {
        $available = $this->collection($collection)->games->firstWhere('id', $game->id);
        abort_unless($available, 404);

        return DB::table('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
            ->where('game_media.game_version_id', $available->current_published_version_id)
            ->where('game_media.role', 'cover')->where('media_assets.status', 'reviewed')
            ->whereIn('media_assets.mime', ['image/jpeg', 'image/png', 'image/webp'])
            ->first(['media_assets.disk', 'media_assets.path', 'media_assets.mime', 'media_assets.alt_text']);
    }

    public function eligibleIds(array $ids): array
    {
        return $this->constrainAvailable(Game::query())->whereKey($ids)->pluck('games.id')->all();
    }

    private function constrainAvailable(Builder $query): Builder
    {
        return $query->where('games.status', GameStatus::Published)
            ->whereNotNull('games.current_published_version_id')
            ->whereHas('currentPublishedVersion.facts')
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_publications')
                ->whereColumn('game_publications.game_id', 'games.id')
                ->whereColumn('game_publications.game_version_id', 'games.current_published_version_id')->whereNull('game_publications.unpublished_at'))
            ->whereRaw("(SELECT content_reviews.decision FROM content_reviews WHERE content_reviews.game_version_id = games.current_published_version_id ORDER BY content_reviews.reviewed_at DESC, content_reviews.id DESC LIMIT 1) = 'approved'")
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_age_ranges')->whereColumn('game_age_ranges.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_situations')->whereColumn('game_situations.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_locations')->whereColumn('game_locations.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_player_requirements')->whereColumn('game_player_requirements.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_safety_rules')->join('safety_rules', 'safety_rules.id', '=', 'game_safety_rules.safety_rule_id')
                ->whereColumn('game_safety_rules.game_version_id', 'games.current_published_version_id')->where('safety_rules.is_active', true))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
                ->whereColumn('game_media.game_version_id', 'games.current_published_version_id')->where('game_media.role', 'cover')
                ->whereNotNull('game_media.crop_data')->where('media_assets.status', 'reviewed')->whereNotNull('media_assets.alt_text'));
    }
}
