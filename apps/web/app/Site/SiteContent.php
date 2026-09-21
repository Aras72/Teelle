<?php

declare(strict_types=1);

namespace App\Site;

use App\Models\SiteContentSetting;
use Illuminate\Support\Facades\Cache;
use Throwable;

final class SiteContent
{
    public const CACHE_KEY = 'teelle.site-content.v1';

    public function defaults(): array
    {
        return [
            'nav_jigari_label' => 'تیله جیگری', 'nav_about_label' => 'درباره تیله', 'nav_order' => 'jigari_about_magazine',
            'nav_magazine_label' => 'مجله تیله',
            'jigari_title' => 'تیله جیگری', 'jigari_intro' => 'پروفایل کودک، برنامه‌ریزی بازی، کشف دقیق‌تر و کیفیت بالاتر',
            'magazine_title' => 'مجله تیله', 'magazine_intro' => 'قصه‌های شب، راهنمای بازی و ایده‌هایی که وقت واقعی کنار کودک را شیرین‌تر می‌کنند.',
            'home_title' => 'بازی مناسب، برای همین لحظه', 'home_intro' => 'چند سؤال کوتاه، سه بازی مناسب برای همین حالا',
            'home_cta_label' => 'چی بازی کنیم؟', 'brand_promise' => 'کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد',
            'home_alignment' => 'center', 'about_title' => 'راه کوتاه‌تر تا بازی کنار کودک',
            'about_lead' => 'تیله با سه انتخاب روشن، کمک می‌کند تا زودتر در کنار کودک‌تان تجربه‌های شیرین بسازید.',
            'about_cta_label' => 'چی بازی کنیم؟',
        ];
    }

    public function all(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, fn (): array => array_replace(
                $this->defaults(), SiteContentSetting::query()->pluck('value', 'key')->all()
            ));
        } catch (Throwable) {
            return $this->defaults();
        }
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
