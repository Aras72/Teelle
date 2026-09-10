<?php

namespace Tests\Feature;

use Tests\TestCase;

class DesignSystemTest extends TestCase
{
    public function test_self_hosted_font_and_license_are_present(): void
    {
        $font = resource_path('fonts/Vazirmatn-Variable.woff2');
        $license = resource_path('fonts/OFL.txt');

        $this->assertFileExists($font);
        $this->assertGreaterThan(100_000, filesize($font));
        $this->assertFileExists($license);
        $this->assertStringContainsString('SIL OPEN FONT LICENSE Version 1.1', file_get_contents($license));
    }

    public function test_semantic_tokens_cover_both_themes_and_motion_preferences(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);
        $this->assertStringContainsString(":root[data-theme='dark']", $css);
        $this->assertStringContainsString('@media (prefers-color-scheme: dark)', $css);
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $css);
        $this->assertStringContainsString('--surface-primary:', $css);
        $this->assertStringContainsString('--action-primary:', $css);
        $this->assertStringContainsString('--focus-ring:', $css);
        $this->assertStringContainsString('--touch-target: 2.75rem', $css);
        $this->assertStringNotContainsString('#000000', $css);
        $this->assertStringNotContainsString('#ffffff', $css);
    }

    public function test_theme_controller_handles_storage_system_changes_and_invalid_values(): void
    {
        $javascript = file_get_contents(resource_path('js/app.js'));

        $this->assertIsString($javascript);
        $this->assertStringContainsString("new Set(['light', 'dark'])", $javascript);
        $this->assertStringContainsString('window.localStorage.removeItem(storageKey)', $javascript);
        $this->assertStringContainsString("media.addEventListener('change'", $javascript);
        $this->assertStringContainsString('window.localStorage.setItem(storageKey, theme)', $javascript);
    }

    public function test_shared_components_render_accessible_contracts(): void
    {
        $this->blade('<x-ui.button href="/">شروع بازی</x-ui.button>')
            ->assertSee('<a href="/" class="teelle-button teelle-button--primary">', false)
            ->assertSee('شروع بازی');

        $this->blade('<x-ui.field label="سن کودک" name="age" hint="به ماه وارد کنید" error="سن معتبر نیست" />')
            ->assertSee('for="age"', false)
            ->assertSee('aria-describedby="age-hint age-error"', false)
            ->assertSee('aria-invalid="true"', false);

        $this->blade('<x-ui.state-message tone="error">خطا</x-ui.state-message>')
            ->assertSee('role="status"', false)
            ->assertSee('teelle-state-message--error', false);
    }

    public function test_homepage_tagline_only_forces_a_single_line_on_desktop(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);
        $this->assertStringContainsString(".teelle-container {\n    width: min(100% - (2 * var(--page-gutter)), var(--content-max));\n    min-width: 0;", $css);
        $this->assertStringContainsString('@media (min-width: 48rem) {', $css);
        $this->assertStringContainsString('.home-heartbeat__tagline { white-space: nowrap; font-size:', $css);
        $this->assertStringNotContainsString(".home-heartbeat__tagline {\n    max-width: none;\n    white-space: nowrap;", $css);
    }
}
