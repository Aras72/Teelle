<?php

namespace Tests\Feature;

use Tests\TestCase;

class FoundationTest extends TestCase
{
    public function test_home_page_is_a_persian_rtl_teelle_shell(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="fa" dir="rtl">', false)
            ->assertSee('تیله')
            ->assertSee('کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد')
            ->assertSee('این صفحه موقت است');
    }

    public function test_health_endpoint_is_available_without_secret_output(): void
    {
        $this->get('/up')
            ->assertOk()
            ->assertDontSee((string) config('app.key'));
    }

    public function test_persian_application_defaults_are_active(): void
    {
        $this->assertSame('Teelle', config('app.name'));
        $this->assertSame('fa', config('app.locale'));
        $this->assertSame('fa', config('app.fallback_locale'));
        $this->assertSame('fa_IR', config('app.faker_locale'));
        $this->assertSame('Asia/Tehran', config('app.timezone'));
    }
}
