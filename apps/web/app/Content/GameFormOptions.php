<?php

declare(strict_types=1);

namespace App\Content;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class GameFormOptions
{
    /** @return array<string, mixed> */
    public function all(bool $includeInactive = false): array
    {
        $lookup = function (string $table, string $key = 'slug', string $label = 'title') use ($includeInactive): array {
            return DB::table($table)
                ->when(
                    $table === 'situations',
                    fn (Builder $query) => $query->whereNotIn('slug', ['restaurant', 'car', 'party']),
                )
                ->when(
                    ! $includeInactive && Schema::hasColumn($table, 'is_active'),
                    fn (Builder $query) => $query->where('is_active', true),
                )
                ->orderBy($label)
                ->pluck($label, $key)
                ->all();
        };

        return [
            'age_bands' => $lookup('age_bands', 'code'),
            'situations' => $lookup('situations'),
            'locations' => $lookup('locations'),
            'moods' => $lookup('moods'),
            'tags' => $lookup('tags'),
            'energy_levels' => $lookup('energy_levels'),
            'players' => $lookup('player_requirements'),
            'materials' => $lookup('materials'),
            'safety' => $lookup('safety_rules', 'code', 'copy'),
        ];
    }
}
