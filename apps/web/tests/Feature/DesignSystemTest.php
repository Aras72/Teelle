<?php

namespace Tests\Feature;

use Tests\TestCase;

class DesignSystemTest extends TestCase
{
    public function test_self_hosted_font_and_license_are_present(): void
    {
        $font = resource_path('fonts/Vazirmatn-Variable.woff2');
        $displayFont = resource_path('fonts/Lalezar-Regular.ttf');
        $license = resource_path('fonts/OFL.txt');

        $this->assertFileExists($font);
        $this->assertGreaterThan(100_000, filesize($font));
        $this->assertFileExists($displayFont);
        $this->assertGreaterThan(200_000, filesize($displayFont));
        $this->assertFileExists($license);
        $this->assertStringContainsString('SIL OPEN FONT LICENSE Version 1.1', file_get_contents($license));

        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString("font-family: 'Lalezar';", $css);
        $this->assertStringContainsString("--font-display: 'Lalezar', 'Vazirmatn'", $css);
        $this->assertStringContainsString('.teelle-wordmark {', $css);
        $this->assertStringContainsString('.teelle-play-cta {', $css);
        $this->assertStringContainsString('.teelle-brand-promise {', $css);
        $this->assertStringContainsString('--teelle-brick: #b84632', $css);
        $this->assertStringContainsString('.match-question legend {', $css);
        $this->assertStringContainsString('font-family: var(--font-display);', $css);
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
        $this->assertStringNotContainsString('.teelle-account-link { min-height: 2.5rem;', $css);
        $this->assertStringContainsString('.auth-check { display: flex; min-height: var(--touch-target);', $css);
        $this->assertStringContainsString('.auth-links a { display: inline-flex; min-height: var(--touch-target);', $css);
        $this->assertStringContainsString('overflow-x: clip; overflow-y: visible;', $css);
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

    public function test_collections_list_keeps_clear_space_below_the_floating_marble(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);
        $this->assertStringContainsString(
            ".collection-list {\n    padding-block-start: clamp(var(--space-10), 6vw, var(--space-16));",
            $css,
        );
        $this->assertStringContainsString(".collections-heading {\n        padding-block-end: 7.5rem;", $css);
        $this->assertStringContainsString(".collections-heading__marble {\n        top: auto;\n        bottom: 0;\n        width: 7rem;", $css);
    }

    public function test_page_marbles_use_distinct_optimized_photorealistic_assets(): void
    {
        $assets = [
            'heartbeat-cobalt-v1.webp',
            'auth-emerald-v1.webp',
            'match-violet-v1.webp',
            'play-amber-v1.webp',
            'account-indigo-v1.webp',
            'jigari-ruby-v1.webp',
        ];

        foreach ($assets as $asset) {
            $path = public_path('images/marbles/'.$asset);
            $this->assertFileExists($path);
            $this->assertGreaterThan(100_000, filesize($path), $asset.' must retain enough image detail.');
            $this->assertLessThan(250_000, filesize($path), $asset.' must remain web optimized.');
        }

        $expectedByView = [
            'welcome.blade.php' => ['heartbeat-cobalt-v1.webp'],
            'match/show.blade.php' => ['match-violet-v1.webp', 'auth-emerald-v1.webp'],
            'results/show.blade.php' => ['play-amber-v1.webp'],
            'play/show.blade.php' => ['play-amber-v1.webp'],
            'auth/login.blade.php' => ['auth-emerald-v1.webp'],
            'auth/register.blade.php' => ['account-indigo-v1.webp'],
            'account/show.blade.php' => ['account-indigo-v1.webp'],
            'account/children/index.blade.php' => ['account-indigo-v1.webp'],
            'account/children/form.blade.php' => ['account-indigo-v1.webp'],
            'jigari/show.blade.php' => ['jigari-ruby-v1.webp'],
        ];

        foreach ($expectedByView as $view => $expectedAssets) {
            $source = file_get_contents(resource_path('views/'.$view));
            $this->assertIsString($source);

            foreach ($expectedAssets as $expectedAsset) {
                $this->assertStringContainsString($expectedAsset, $source);
            }
        }

        $jigari = file_get_contents(resource_path('views/jigari/show.blade.php'));
        $this->assertIsString($jigari);
        $this->assertSame(4, substr_count($jigari, 'jigari-ruby-v1.webp'));
        $this->assertSame(3, substr_count($jigari, 'jigari-orbit__track jigari-orbit__track--'));
        $this->assertDoesNotMatchRegularExpression('/images\/marbles\/(?!jigari-ruby-v1\.webp)/', $jigari);

        $css = file_get_contents(resource_path('css/app.css'));
        $this->assertIsString($css);
        $this->assertStringContainsString('.jigari-orbit__track--one { animation: jigari-track-one', $css);
        $this->assertStringContainsString('.jigari-orbit__track--two { animation: jigari-track-two', $css);
        $this->assertStringContainsString('.jigari-orbit__track--three { animation: jigari-track-three', $css);
        $this->assertStringContainsString('.auth-orbit span { animation: auth-marble-orbit', $css);
        $this->assertStringContainsString('.auth-orbit { position: absolute; width: min(88vw, 62rem); aspect-ratio: 2 / 1; border: 0;', $css);
        $this->assertStringContainsString('.jigari-orbit { position: absolute; width: min(88%, 50rem); aspect-ratio: 2 / 1; border: 0;', $css);
        $this->assertStringContainsString('.match-orbit { position: absolute; inset: 8% 3%; border: 0;', $css);
        $this->assertStringContainsString('.results-orbit { position: absolute; inset: 2rem 0 auto; height: 22rem; border: 0;', $css);

        $match = file_get_contents(resource_path('views/match/show.blade.php'));
        $this->assertIsString($match);
        $this->assertSame(5, substr_count($match, 'match-orbit__marble match-orbit__marble--'));

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views'))) as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php' || $file->getFilename() === 'welcome.blade.php') {
                continue;
            }

            $this->assertStringNotContainsString('teelle-hero-marble-poster-v1.png', file_get_contents($file->getPathname()));
        }
    }
}
