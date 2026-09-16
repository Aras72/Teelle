<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LOCATION_ONLY_SLUGS = ['restaurant', 'car', 'party'];

    public function up(): void
    {
        DB::transaction(function (): void {
            DB::table('situations')
                ->whereIn('slug', self::LOCATION_ONLY_SLUGS)
                ->update(['is_active' => false, 'updated_at' => now()]);

            $this->rebuildCoverageTargets();
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {
            DB::table('situations')
                ->whereIn('slug', self::LOCATION_ONLY_SLUGS)
                ->update(['is_active' => true, 'updated_at' => now()]);

            $this->rebuildCoverageTargets();
        });
    }

    private function rebuildCoverageTargets(): void
    {
        $now = now();
        $activeSituationIds = DB::table('situations')->where('is_active', true)->pluck('id');

        DB::table('coverage_matrix_cells')
            ->whereNotIn('situation_id', $activeSituationIds)
            ->update(['is_critical' => false, 'updated_at' => $now]);

        foreach (DB::table('age_bands')->pluck('id') as $ageBandId) {
            foreach ($activeSituationIds as $situationId) {
                DB::table('coverage_matrix_cells')->updateOrInsert(
                    ['age_band_id' => $ageBandId, 'situation_id' => $situationId],
                    ['is_critical' => true, 'minimum_survivors' => 3, 'updated_at' => $now, 'created_at' => $now],
                );
            }
        }
    }
};
