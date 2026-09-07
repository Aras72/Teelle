<?php

namespace Tests\Feature;

use App\Homepage\ReadPublicHeartbeat;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function test_homepage_renders_approved_copy_and_real_heartbeat_mapping(): void
    {
        $this->fakeHeartbeat(12345);

        $this->get('/')
            ->assertOk()
            ->assertSee('بازی مناسب، برای همین لحظه')
            ->assertSee('چند سؤال کوتاه، سه بازی مناسب برای همین حالا')
            ->assertSee('کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد')
            ->assertSee('۱۲٬۳۴۵')
            ->assertSee('بار بازی با تیله انجام شده')
            ->assertSee('چی بازی کنیم؟')
            ->assertSee('data-heartbeat-count="12345"', false);
    }

    public function test_homepage_uses_zero_without_inventing_activity(): void
    {
        $this->fakeHeartbeat(0);

        $this->get('/')
            ->assertOk()
            ->assertSee('۰')
            ->assertSee('data-heartbeat-count="0"', false)
            ->assertDontSee('۱۲٬۳۴۵');
    }

    public function test_homepage_handles_an_unavailable_projection_without_a_fake_count(): void
    {
        $this->fakeHeartbeat(null);

        $this->get('/')
            ->assertOk()
            ->assertSee('آمار بازی‌ها فعلاً در دسترس نیست')
            ->assertDontSee('data-heartbeat-count', false)
            ->assertDontSee('۱۲٬۳۴۵');
    }

    public function test_display_copy_has_no_trailing_full_stop(): void
    {
        $this->fakeHeartbeat(27);
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertIsString($html);
        $this->assertStringNotContainsString('لحظه.</h1>', $html);
        $this->assertStringNotContainsString('حالا.</p>', $html);
        $this->assertStringNotContainsString('دارد.</p>', $html);
    }

    public function test_marble_fallback_reserves_space_and_cta_is_independent(): void
    {
        $this->fakeHeartbeat(1);

        $this->get('/')
            ->assertOk()
            ->assertSee('width="1536"', false)
            ->assertSee('height="1024"', false)
            ->assertSee('home-marble-stage__fallback', false)
            ->assertSee('href="/match"', false);

        $this->assertFileExists(public_path('images/teelle-hero-marble-poster-v1.png'));
    }

    private function fakeHeartbeat(?int $count): void
    {
        $this->app->instance(ReadPublicHeartbeat::class, new class($count) extends ReadPublicHeartbeat
        {
            public function __construct(private readonly ?int $count) {}

            public function __invoke(): ?int
            {
                return $this->count;
            }
        });
    }
}
