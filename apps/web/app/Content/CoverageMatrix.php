<?php

declare(strict_types=1);

namespace App\Content;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class CoverageMatrix
{
    /** @return Collection<int, object> */
    public function report(): Collection
    {
        return DB::table('coverage_matrix_cells as cells')
            ->join('age_bands', 'age_bands.id', '=', 'cells.age_band_id')
            ->join('situations', 'situations.id', '=', 'cells.situation_id')
            ->leftJoinSub($this->survivorCounts(), 'coverage', function ($join): void {
                $join->on('coverage.age_band_id', '=', 'cells.age_band_id')
                    ->on('coverage.situation_id', '=', 'cells.situation_id');
            })
            ->select([
                'cells.id', 'cells.is_critical', 'cells.minimum_survivors',
                'age_bands.code as age_band_code', 'age_bands.title as age_band_title',
                'situations.slug as situation_slug', 'situations.title as situation_title',
                DB::raw('COALESCE(coverage.survivors, 0) as survivors'),
            ])
            ->orderBy('age_bands.minimum_age_months')->orderBy('situations.id')->get();
    }

    public function criticalGaps(): int
    {
        return $this->report()->filter(fn (object $cell): bool => $cell->is_critical && $cell->survivors < $cell->minimum_survivors)->count();
    }

    private function survivorCounts()
    {
        return DB::table('coverage_matrix_cells as target')
            ->join('age_bands as target_age', 'target_age.id', '=', 'target.age_band_id')
            ->join('game_age_ranges', function ($join): void {
                $join->on('game_age_ranges.minimum_age_months', '<=', 'target_age.minimum_age_months')
                    ->on('game_age_ranges.maximum_age_months_exclusive', '>=', 'target_age.maximum_age_months_exclusive');
            })
            ->join('game_situations', function ($join): void {
                $join->on('game_situations.game_version_id', '=', 'game_age_ranges.game_version_id')
                    ->on('game_situations.situation_id', '=', 'target.situation_id');
            })
            ->join('game_versions', 'game_versions.id', '=', 'game_age_ranges.game_version_id')
            ->join('game_facts', 'game_facts.game_version_id', '=', 'game_versions.id')
            ->join('content_reviews', function ($join): void {
                $join->on('content_reviews.game_version_id', '=', 'game_versions.id')
                    ->where('content_reviews.decision', '=', 'approved');
            })
            ->join('games', function ($join): void {
                $join->on('games.current_published_version_id', '=', 'game_versions.id')
                    ->where('games.status', '=', 'published');
            })
            ->join('game_publications', function ($join): void {
                $join->on('game_publications.game_id', '=', 'games.id')
                    ->on('game_publications.game_version_id', '=', 'game_versions.id')
                    ->whereNull('game_publications.unpublished_at');
            })
            ->where('game_versions.status', 'approved')
            ->groupBy('target.age_band_id', 'target.situation_id')
            ->select('target.age_band_id', 'target.situation_id', DB::raw('COUNT(DISTINCT games.id) as survivors'));
    }
}
