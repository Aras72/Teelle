<?php

namespace Tests\Feature;

use Tests\TestCase;

class PwaReadinessTest extends TestCase
{
    public function test_web_shell_exposes_the_pwa_manifest_and_metadata(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('rel="manifest"', false)
            ->assertSee('/manifest.webmanifest', false)
            ->assertSee('name="application-name" content="تیله"', false)
            ->assertSee('name="mobile-web-app-capable" content="yes"', false);
    }

    public function test_manifest_is_installable_and_uses_approved_real_marble_assets(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('تیله', $manifest['short_name']);
        $this->assertSame('fa', $manifest['lang']);
        $this->assertSame('rtl', $manifest['dir']);
        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('/', $manifest['scope']);
        $this->assertStringStartsWith('/', $manifest['start_url']);
        $this->assertCount(2, $manifest['icons']);
        $this->assertSame('any', $manifest['icons'][0]['purpose']);
        $this->assertSame('maskable', $manifest['icons'][1]['purpose']);

        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists(public_path(ltrim($icon['src'], '/')));
        }
    }

    public function test_service_worker_keeps_navigation_private_and_has_an_offline_fallback(): void
    {
        $worker = file_get_contents(public_path('sw.js'));
        $apache = file_get_contents(public_path('.htaccess'));

        $this->assertIsString($worker);
        $this->assertIsString($apache);
        $this->assertStringContainsString("request.method !== 'GET'", $worker);
        $this->assertStringContainsString("request.mode === 'navigate'", $worker);
        $this->assertStringContainsString('caches.match(OFFLINE_URL)', $worker);
        $this->assertStringNotContainsString('cache.put(request', strstr($worker, "request.mode === 'navigate'", true));
        $this->assertStringContainsString("url.pathname.startsWith('/api/')", $worker);
        $this->assertStringContainsString('application/manifest+json', $apache);
        $this->assertStringContainsString('no-cache, no-store, must-revalidate', $apache);
        $this->assertFileExists(public_path('offline.html'));
    }

    public function test_offline_document_has_recovery_copy_and_accessible_action(): void
    {
        $offline = file_get_contents(public_path('offline.html'));

        $this->assertIsString($offline);
        $this->assertStringContainsString('فعلاً آفلاینی', $offline);
        $this->assertStringContainsString('دوباره تلاش کن', $offline);
        $this->assertStringContainsString('min-height:44px', $offline);
    }
}
