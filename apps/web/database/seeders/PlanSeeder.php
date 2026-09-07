<?php

namespace Database\Seeders;

use App\Models\HeartbeatProjection;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([3, 6, 12] as $months) {
            Plan::query()->updateOrCreate(
                ['code' => "jigari-{$months}m"],
                [
                    'title' => "تیله جیگری {$months} ماهه",
                    'duration_months' => $months,
                    'price_minor' => null,
                    'currency' => 'IRR',
                    'is_active' => false,
                ],
            );
        }

        HeartbeatProjection::query()->firstOrCreate(
            ['key' => 'public_play_starts'],
            ['started_count' => 0, 'last_event_recorded_at' => null],
        );
    }
}
