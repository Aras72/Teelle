<?php

namespace Database\Seeders;

use App\Models\HeartbeatProjection;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [
            3 => 3_900_000,
            6 => 6_900_000,
            12 => 11_900_000,
        ];

        foreach ([3, 6, 12] as $months) {
            $plan = Plan::query()->firstOrCreate(
                ['code' => "jigari-{$months}m"],
                [
                    'title' => "تیله جیگری {$months} ماهه",
                    'duration_months' => $months,
                    'price_minor' => $prices[$months],
                    'currency' => 'IRR',
                    'is_active' => true,
                ],
            );

            if (! $plan->wasRecentlyCreated && $plan->price_minor === null) {
                $plan->update([
                    'price_minor' => $prices[$months],
                    'currency' => 'IRR',
                    'is_active' => true,
                ]);
            }
        }

        HeartbeatProjection::query()->firstOrCreate(
            ['key' => 'public_play_starts'],
            ['started_count' => 0, 'last_event_recorded_at' => null],
        );
    }
}
