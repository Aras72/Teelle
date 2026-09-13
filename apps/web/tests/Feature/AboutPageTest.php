<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class AboutPageTest extends TestCase
{
    public function test_about_page_uses_the_approved_product_and_brand_contract(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('راه کوتاه‌تر تا بازی کنار کودک')
            ->assertSee('تیله با سه انتخاب روشن، کمک می‌کند تا زودتر در کنار کودک‌تان تجربه‌های شیرین بسازید.')
            ->assertSee('کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد')
            ->assertSee('سه انتخاب روشن، نه یک فهرست بی‌انتها')
            ->assertSee('پیشنهادها با بررسی و قواعد سختگیرانه ارائه می‌شوند، نه با AI.')
            ->assertSee('ایمنی اطلاعات شما همیشه حفظ می‌شود.')
            ->assertSee('اتلاف وقت کمتر برای انتخاب، فرصت بیشتر برای با هم بودن')
            ->assertSee('ایمنی قبل از درآمد')
            ->assertSee(route('match.show'), false)
            ->assertSee('images/marbles/play-amber-v1.webp', false);
    }

    public function test_primary_navigation_links_to_the_real_about_page(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('about'), false)
            ->assertSee('درباره تیله');
    }
}
