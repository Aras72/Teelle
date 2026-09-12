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
            ->assertSee('از صفحه به بازی واقعی')
            ->assertSee('کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد')
            ->assertSee('سه انتخاب روشن، نه یک فهرست بی‌انتها')
            ->assertSee('پیشنهادها با Metadata و قواعد قطعی ساخته می‌شوند، نه با AI')
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
