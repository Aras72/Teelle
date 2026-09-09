<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAgeBands();

        $this->seedSlugTable('situations', [
            'connection' => 'وقت باهم بودن',
            'restless' => 'بی‌قراری',
            'bored' => 'حوصله‌سررفتگی',
            'calm-down' => 'آرام‌شدن',
            'indoor-time' => 'وقت داخل خانه',
        ]);
        $this->seedSlugTable('locations', [
            'home-inside' => 'داخل خانه',
            'home-outside' => 'حیاط یا فضای باز خانه',
            'park' => 'پارک',
            'travel' => 'در مسیر یا سفر',
        ]);
        $this->seedSlugTable('energy_levels', ['low' => 'کم', 'medium' => 'متوسط', 'high' => 'زیاد']);
        $this->seedSlugTable('moods', ['calm' => 'آرام', 'restless' => 'بی‌قرار', 'sad' => 'غمگین', 'excited' => 'هیجان‌زده']);
        $this->seedSlugTable('player_requirements', [
            'child-and-adult' => 'کودک و یک بزرگسال',
            'two-players' => 'حداقل دو بازیکن',
            'small-group' => 'گروه کوچک',
        ]);
        $this->seedSlugTable('tags', [
            'creative' => 'خلاقیت',
            'movement' => 'حرکت',
            'sensory' => 'حسی',
            'language' => 'زبان',
            'cooperative' => 'همکاری',
        ]);

        foreach (['paper' => 'کاغذ', 'ball' => 'توپ', 'cups' => 'لیوان', 'blanket' => 'پتو'] as $slug => $title) {
            DB::table('materials')->updateOrInsert(
                ['slug' => $slug],
                ['title' => $title, 'risk_class' => 'low', 'is_active' => true],
            );
        }

        foreach ($this->safetyRules() as $code => $copy) {
            DB::table('safety_rules')->updateOrInsert(
                ['code' => $code],
                ['severity' => 'warning', 'rule_type' => 'flag', 'copy' => $copy, 'is_active' => true],
            );
        }

        $this->seedCoverageMatrix();
    }

    private function seedCoverageMatrix(): void
    {
        $now = now();
        foreach (DB::table('age_bands')->pluck('id') as $ageBandId) {
            foreach (DB::table('situations')->where('is_active', true)->pluck('id') as $situationId) {
                DB::table('coverage_matrix_cells')->updateOrInsert(
                    ['age_band_id' => $ageBandId, 'situation_id' => $situationId],
                    ['is_critical' => true, 'minimum_survivors' => 3, 'updated_at' => $now, 'created_at' => $now],
                );
            }
        }
    }

    private function seedAgeBands(): void
    {
        $bands = [
            ['code' => '6-23m', 'title' => '۶ تا ۲۳ ماه', 'minimum_age_months' => 6, 'maximum_age_months_exclusive' => 24],
            ['code' => '2-3y', 'title' => '۲ تا ۳ سال', 'minimum_age_months' => 24, 'maximum_age_months_exclusive' => 48],
            ['code' => '4-6y', 'title' => '۴ تا ۶ سال', 'minimum_age_months' => 48, 'maximum_age_months_exclusive' => 84],
            ['code' => '7-9y', 'title' => '۷ تا ۹ سال', 'minimum_age_months' => 84, 'maximum_age_months_exclusive' => 120],
            ['code' => '10-12y', 'title' => '۱۰ تا ۱۲ سال', 'minimum_age_months' => 120, 'maximum_age_months_exclusive' => 156],
        ];

        foreach ($bands as $band) {
            DB::table('age_bands')->updateOrInsert(['code' => $band['code']], $band);
        }
    }

    /** @param array<string, string> $items */
    private function seedSlugTable(string $table, array $items): void
    {
        foreach ($items as $slug => $title) {
            DB::table($table)->updateOrInsert(
                ['slug' => $slug],
                ['title' => $title, 'is_active' => true],
            );
        }
    }

    /** @return array<string, string> */
    private function safetyRules(): array
    {
        return [
            'small_parts' => 'قطعات کوچک باید دور از دسترس کودک در معرض بلع باشند',
            'choking' => 'خطر خفگی را پیش از شروع بررسی کنید',
            'ingestion' => 'مواد این بازی خوراکی نیستند',
            'allergen' => 'حساسیت‌های شناخته‌شده کودک را بررسی کنید',
            'water' => 'کنار آب نظارت پیوسته بزرگسال لازم است',
            'heat_fire' => 'کودک را از گرما و آتش دور نگه دارید',
            'sharp_object' => 'اشیای تیز فقط با نظارت مناسب استفاده شوند',
            'fall_height' => 'محیط را از خطر سقوط ایمن کنید',
            'traffic_outdoor' => 'در فضای بیرون خطر ترافیک را کنترل کنید',
            'chemical' => 'مواد شیمیایی دور از دسترس کودک باشند',
            'strangulation' => 'بند و طناب می‌تواند خطر گیرکردن ایجاد کند',
            'high_impact' => 'فضای کافی برای حرکت پرقدرت فراهم کنید',
            'sensory_intensity' => 'شدت محرک حسی را با نیاز کودک هماهنگ کنید',
        ];
    }
}
