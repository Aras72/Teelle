<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceSituations([
            'between-meals' => 'بین وعده‌های غذایی',
            'after-meal' => 'بعد از غذا',
            'between-routines' => 'بین کارهای روزمره',
            'before-bed' => 'قبل از خواب',
        ]);
    }

    public function down(): void
    {
        $this->replaceSituations([
            'after-work' => 'بعد از کار',
            'rainy-day' => 'روز بارانی',
            'before-bed' => 'قبل خواب',
        ]);
    }

    /** @param array<string, string> $items */
    private function replaceSituations(array $items): void
    {
        DB::transaction(function () use ($items): void {
            $now = now();
            DB::table('situations')->update(['is_active' => false, 'updated_at' => $now]);

            foreach ($items as $slug => $title) {
                DB::table('situations')->updateOrInsert(
                    ['slug' => $slug],
                    ['title' => $title, 'is_active' => true, 'updated_at' => $now, 'created_at' => $now],
                );
            }

            $activeIds = DB::table('situations')->where('is_active', true)->pluck('id');
            DB::table('coverage_matrix_cells')->update(['is_critical' => false, 'updated_at' => $now]);
            foreach (DB::table('age_bands')->pluck('id') as $ageBandId) {
                foreach ($activeIds as $situationId) {
                    DB::table('coverage_matrix_cells')->updateOrInsert(
                        ['age_band_id' => $ageBandId, 'situation_id' => $situationId],
                        ['is_critical' => true, 'minimum_survivors' => 3, 'updated_at' => $now, 'created_at' => $now],
                    );
                }
            }
        });
    }
};
