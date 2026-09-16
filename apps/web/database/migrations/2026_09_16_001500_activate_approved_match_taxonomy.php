<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceActiveTaxonomy('situations', [
            'after-work' => 'بعد از کار',
            'rainy-day' => 'روز بارانی',
            'before-bed' => 'قبل خواب',
        ]);
        $this->replaceActiveTaxonomy('locations', [
            'home-inside' => 'داخل خانه',
            'outdoors' => 'بیرون',
            'restaurant' => 'رستوران',
            'car' => 'ماشین',
            'party' => 'مهمانی',
        ]);
        $this->replaceActiveTaxonomy('moods', [
            'calm' => 'آرام',
            'bored' => 'بی‌حوصله',
            'energetic' => 'پرانرژی',
            'needs-attention' => 'نیاز به توجه',
        ]);
        $this->replaceActiveTaxonomy('player_requirements', [
            'child-and-adult' => 'یک کودک و یک بزرگسال',
            'multiple-children' => 'چند کودک',
            'no-adult' => 'بزرگسال همراه نیست',
        ]);

        DB::table('materials')->update(['is_active' => false]);
        foreach ([
            'paper-pencil' => 'کاغذ و مداد',
            'ball' => 'توپ',
            'household-items' => 'وسایل خانه',
        ] as $slug => $title) {
            DB::table('materials')->updateOrInsert(
                ['slug' => $slug],
                ['title' => $title, 'risk_class' => 'low', 'is_active' => true, 'updated_at' => now(), 'created_at' => now()],
            );
        }

        $activeSituationIds = DB::table('situations')->where('is_active', true)->pluck('id');
        DB::table('coverage_matrix_cells')->whereNotIn('situation_id', $activeSituationIds)->update(['is_critical' => false, 'updated_at' => now()]);
        foreach (DB::table('age_bands')->pluck('id') as $ageBandId) {
            foreach ($activeSituationIds as $situationId) {
                DB::table('coverage_matrix_cells')->updateOrInsert(
                    ['age_band_id' => $ageBandId, 'situation_id' => $situationId],
                    ['is_critical' => true, 'minimum_survivors' => 3, 'updated_at' => now(), 'created_at' => now()],
                );
            }
        }
    }

    public function down(): void
    {
        $this->replaceActiveTaxonomy('situations', [
            'connection' => 'وقت باهم بودن',
            'restless' => 'بی‌قراری',
            'bored' => 'حوصله‌سررفتگی',
            'calm-down' => 'آرام‌شدن',
            'indoor-time' => 'وقت داخل خانه',
        ]);
        $this->replaceActiveTaxonomy('locations', [
            'home-inside' => 'داخل خانه',
            'home-outside' => 'حیاط یا فضای باز خانه',
            'park' => 'پارک',
            'travel' => 'در مسیر یا سفر',
        ]);
        $this->replaceActiveTaxonomy('moods', [
            'calm' => 'آرام',
            'restless' => 'بی‌قرار',
            'sad' => 'غمگین',
            'excited' => 'هیجان‌زده',
        ]);
        $this->replaceActiveTaxonomy('player_requirements', [
            'child-and-adult' => 'کودک و یک بزرگسال',
            'two-players' => 'حداقل دو بازیکن',
            'small-group' => 'گروه کوچک',
        ]);
        DB::table('materials')->whereIn('slug', ['paper', 'ball', 'cups', 'blanket'])->update(['is_active' => true]);
        DB::table('materials')->whereIn('slug', ['paper-pencil', 'household-items'])->update(['is_active' => false]);
    }

    /** @param array<string, string> $items */
    private function replaceActiveTaxonomy(string $table, array $items): void
    {
        DB::table($table)->update(['is_active' => false]);
        foreach ($items as $slug => $title) {
            DB::table($table)->updateOrInsert(
                ['slug' => $slug],
                ['title' => $title, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }
};
