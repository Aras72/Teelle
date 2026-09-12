<?php

declare(strict_types=1);

namespace App\Analytics;

use Illuminate\Support\Facades\DB;

final class SearchObservationRecorder
{
    /**
     * Store aggregate search health without retaining a query or user identifier.
     *
     * @param  array<string, mixed>  $filters
     */
    public function record(array $filters, int $resultCount): void
    {
        $today = now()->toDateString();
        $hasQuery = filled($filters['q'] ?? null);
        $hasFilter = collect($filters)
            ->except('q')
            ->contains(fn (mixed $value): bool => is_array($value) ? $value !== [] : filled($value));

        DB::transaction(function () use ($today, $hasQuery, $hasFilter, $resultCount): void {
            DB::table('search_observations')->insertOrIgnore([
                'observed_on' => $today,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('search_observations')->where('observed_on', $today)->update([
                'search_count' => DB::raw('search_count + 1'),
                'searches_with_results' => DB::raw('searches_with_results + '.($resultCount > 0 ? 1 : 0)),
                'zero_result_count' => DB::raw('zero_result_count + '.($resultCount === 0 ? 1 : 0)),
                'query_search_count' => DB::raw('query_search_count + '.($hasQuery ? 1 : 0)),
                'filtered_search_count' => DB::raw('filtered_search_count + '.($hasFilter ? 1 : 0)),
                'updated_at' => now(),
            ]);
        });
    }
}
