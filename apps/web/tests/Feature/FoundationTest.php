<?php

namespace Tests\Feature;

use Tests\TestCase;

class FoundationTest extends TestCase
{
    public function test_home_page_uses_the_persian_rtl_teelle_design_shell(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="fa" dir="rtl" class="no-js">', false)
            ->assertSee('تیله')
            ->assertSee('بازی مناسب، برای همین لحظه')
            ->assertSee('چی بازی کنیم؟')
            ->assertSee('data-theme-toggle', false)
            ->assertSee('رفتن به محتوای اصلی');
    }

    public function test_theme_bootstrap_precedes_compiled_assets(): void
    {
        $response = $this->get('/')->assertOk();
        $html = $response->getContent();

        $this->assertIsString($html);
        $this->assertStringContainsString("const key = 'teelle-theme'", $html);
        $this->assertStringContainsString("allowed = ['light', 'dark']", $html);
        $assetPosition = strpos($html, '<link rel="stylesheet"');
        $bootstrapPosition = strpos($html, "const key = 'teelle-theme'");

        $this->assertNotFalse($assetPosition);
        $this->assertNotFalse($bootstrapPosition);
        $this->assertLessThan(
            $assetPosition,
            $bootstrapPosition,
            'Theme bootstrap must run before the compiled stylesheet is requested.',
        );
    }

    public function test_health_endpoint_is_available_without_secret_output(): void
    {
        $this->get('/up')
            ->assertOk()
            ->assertDontSee((string) config('app.key'));
    }

    public function test_web_responses_include_baseline_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('Content-Security-Policy', "base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'")
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeaderMissing('Strict-Transport-Security');
    }

    public function test_hsts_is_only_added_for_secure_production_requests(): void
    {
        $this->app->instance('env', 'production');

        $this->withServerVariables(['HTTPS' => 'on', 'SERVER_PORT' => 443])
            ->get('https://localhost/')
            ->assertOk()
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
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
