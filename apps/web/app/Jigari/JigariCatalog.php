<?php

declare(strict_types=1);

namespace App\Jigari;

use App\Enums\GameStatus;
use App\Models\Game;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class JigariCatalog
{
    public function search(array $filters): LengthAwarePaginator
    {
        $query = $this->availableQuery()->with(['currentPublishedVersion.facts']);
        $versionColumn = 'games.current_published_version_id';

        if (filled($filters['q'] ?? null)) {
            $pattern = '%'.addcslashes((string) $filters['q'], '\\%_').'%';
            $query->whereHas('currentPublishedVersion', function (Builder $version) use ($pattern): void {
                $version->where(function (Builder $text) use ($pattern): void {
                    $title = "LOWER(REPLACE(REPLACE(title, 'ي', 'ی'), 'ك', 'ک'))";
                    $summary = "LOWER(REPLACE(REPLACE(summary, 'ي', 'ی'), 'ك', 'ک'))";
                    $text->whereRaw("{$title} LIKE ?", [$pattern])->orWhereRaw("{$summary} LIKE ?", [$pattern]);
                });
            });
        }

        $this->wherePivotSlug($query, $versionColumn, 'game_situations', 'situations', 'situation_id', $filters['situation'] ?? null);
        $this->wherePivotSlug($query, $versionColumn, 'game_locations', 'locations', 'location_id', $filters['location'] ?? null);
        $this->wherePivotSlug($query, $versionColumn, 'game_player_requirements', 'player_requirements', 'player_requirement_id', $filters['players'] ?? null);

        if (filled($filters['age_band'] ?? null)) {
            $query->whereExists(fn ($sub) => $sub->selectRaw('1')->from('game_age_ranges')
                ->join('age_bands', 'age_bands.id', '=', 'game_age_ranges.age_band_id')
                ->whereColumn('game_age_ranges.game_version_id', $versionColumn)
                ->where('age_bands.code', $filters['age_band']));
        }

        if (filled($filters['duration'] ?? null)) {
            $duration = (int) $filters['duration'];
            $query->whereHas('currentPublishedVersion.facts', fn (Builder $facts) => $facts
                ->where('duration_min_minutes', '<=', $duration)
                ->where('duration_max_minutes', '>=', $duration));
        }

        foreach ($filters['materials'] ?? [] as $material) {
            $this->wherePivotSlug($query, $versionColumn, 'game_materials', 'materials', 'material_id', $material);
        }

        return $query->orderBy('games.id')->paginate(12)->withQueryString();
    }

    public function findAvailable(Game $game): Game
    {
        return $this->availableQuery()->with(['currentPublishedVersion.facts'])->whereKey($game->getKey())->firstOrFail();
    }

    public function cover(Game $game): ?object
    {
        $available = $this->findAvailable($game);

        return DB::table('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
            ->where('game_media.game_version_id', $available->current_published_version_id)
            ->where('game_media.role', 'cover')->where('media_assets.status', 'reviewed')
            ->whereIn('media_assets.mime', ['image/jpeg', 'image/png', 'image/webp'])
            ->first(['media_assets.disk', 'media_assets.path', 'media_assets.mime']);
    }

    private function availableQuery(): Builder
    {
        return Game::query()
            ->where('games.status', GameStatus::Published)
            ->whereNotNull('games.current_published_version_id')
            ->whereHas('currentPublishedVersion.facts')
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_publications')
                ->whereColumn('game_publications.game_id', 'games.id')
                ->whereColumn('game_publications.game_version_id', 'games.current_published_version_id')
                ->whereNull('game_publications.unpublished_at'))
            ->whereRaw("(SELECT content_reviews.decision FROM content_reviews WHERE content_reviews.game_version_id = games.current_published_version_id ORDER BY content_reviews.reviewed_at DESC, content_reviews.id DESC LIMIT 1) = 'approved'")
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_age_ranges')->whereColumn('game_age_ranges.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_situations')->whereColumn('game_situations.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_locations')->whereColumn('game_locations.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_player_requirements')->whereColumn('game_player_requirements.game_version_id', 'games.current_published_version_id'))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_safety_rules')
                ->join('safety_rules', 'safety_rules.id', '=', 'game_safety_rules.safety_rule_id')
                ->whereColumn('game_safety_rules.game_version_id', 'games.current_published_version_id')->where('safety_rules.is_active', true))
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('game_media')
                ->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
                ->whereColumn('game_media.game_version_id', 'games.current_published_version_id')
                ->where('game_media.role', 'cover')->whereNotNull('game_media.crop_data')
                ->where('media_assets.status', 'reviewed')->whereNotNull('media_assets.alt_text'));
    }

    private function wherePivotSlug(Builder $query, string $versionColumn, string $pivot, string $taxonomy, string $foreignKey, mixed $slug): void
    {
        if (! filled($slug)) {
            return;
        }

        $query->whereExists(fn ($sub) => $sub->selectRaw('1')->from($pivot)
            ->join($taxonomy, "{$taxonomy}.id", '=', "{$pivot}.{$foreignKey}")
            ->whereColumn("{$pivot}.game_version_id", $versionColumn)
            ->where("{$taxonomy}.slug", (string) $slug)
            ->where("{$taxonomy}.is_active", true));
    }
}
