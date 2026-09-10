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
            ->assertSee('۱۲٬۴۵۵')
            ->assertSee('بار بازی با تیله انجام شده')
            ->assertSee('چی بازی کنیم؟')
            ->assertSee('data-heartbeat-count="12455"', false);
    }

    public function test_homepage_adds_owner_approved_baseline_without_inserting_events(): void
    {
        $this->fakeHeartbeat(0);

        $this->get('/')
            ->assertOk()
            ->assertSee('۱۱۰')
            ->assertSee('data-heartbeat-count="110"', false)
            ->assertDontSee('۱۲٬۳۴۵');
    }

    public function test_homepage_discloses_unavailable_projection_with_baseline(): void
    {
        $this->fakeHeartbeat(null);

        $this->get('/')
            ->assertOk()
            ->assertSee('به‌روزرسانی آمار موقتاً در دسترس نیست')
            ->assertSee('data-heartbeat-count="110"', false)
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

    public function test_marble_video_keeps_the_approved_poster_fallback_and_cta_independent(): void
    {
        $this->fakeHeartbeat(1);

        $this->get('/')
            ->assertOk()
            ->assertSee('width="1536"', false)
            ->assertSee('height="1024"', false)
            ->assertSee('home-marble-stage__fallback', false)
            ->assertSee('home-marble-stage__video', false)
            ->assertSee('video/teelle-hero-marble-loop-v1.mp4', false)
            ->assertSee('poster="'.asset('images/teelle-hero-marble-poster-v1.png').'"', false)
            ->assertDontSee('data-marble-control', false)
            ->assertDontSee('تیله را بکش و بچرخان')
            ->assertDontSee('data-marble>', false)
            ->assertSee('href="/match"', false);

        $this->assertFileExists(public_path('images/teelle-hero-marble-poster-v1.png'));
        $this->assertFileExists(public_path('video/teelle-hero-marble-loop-v1.mp4'));
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
