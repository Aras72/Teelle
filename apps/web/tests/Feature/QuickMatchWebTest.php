<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\MatchOutcome;
use App\Models\GuestIdentity;
use App\Models\MatchSession;
use Database\Seeders\SystemTaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuickMatchWebTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SystemTaxonomySeeder::class);
    }

    public function test_home_cta_opens_public_age_question(): void
    {
        $this->get('/')->assertOk()->assertSee('href="/match"', false);
        $this->get(route('match.show'))->assertOk()
            ->assertSee('سن کودک چقدر است؟')->assertSee('سؤال 1 از 6');
    }

    public function test_age_bounds_and_persian_digits_are_normalized(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => '۰', 'age_months' => '۵'])
            ->assertSessionHasErrors('age_years');
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => '۰', 'age_months' => '۶'])
            ->assertRedirect(route('match.show'));
        $this->get(route('match.show'))->assertOk()->assertSee('الان بیشتر دنبال چه لحظه‌ای هستید؟');
    }

    public function test_guest_flow_persists_normalized_context_once_without_results(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => 4, 'age_months' => 2]);
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'connection']);
        $this->post(route('match.answer'), ['step' => 'duration', 'answer' => 15]);
        $this->post(route('match.answer'), ['step' => 'location', 'answer' => 'home-inside']);
        $this->post(route('match.answer'), ['step' => 'materials', 'answer' => ['paper', 'ball']]);
        $this->post(route('match.answer'), ['step' => 'players', 'answer' => 'one-child-adult'])->assertRedirect(route('match.show'));

        $match = MatchSession::query()->firstOrFail();
        $this->assertSame(50, $match->age_months);
        $this->assertSame(MatchOutcome::Collecting, $match->outcome);
        $this->assertSame(['paper', 'ball'], $match->context_json['available_materials']);
        $this->assertTrue($match->context_json['adult_present']);
        $this->assertDatabaseCount('guest_identities', 1);
        $this->assertDatabaseCount('match_results', 0);
        $this->get(route('match.show'))->assertOk()->assertSee('حالا تیله این لحظه را می‌شناسد');

        $this->post(route('match.back'));
        $this->get(route('match.show'))->assertOk()->assertSee('حالا تیله این لحظه را می‌شناسد');
        $this->post(route('match.answer'), ['step' => 'players', 'answer' => 'one-child-adult']);
        $this->assertDatabaseCount('match_sessions', 1);
        $this->assertSame(1, GuestIdentity::query()->count());
    }

    public function test_indoor_situation_skips_redundant_location_question(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => 3, 'age_months' => 0]);
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'indoor-time']);
        $this->post(route('match.answer'), ['step' => 'duration', 'answer' => 10]);

        $this->get(route('match.show'))->assertOk()
            ->assertSee('سؤال 4 از 5')
            ->assertSee('کدام وسیله‌ها همین حالا در دسترس‌اند؟')
            ->assertDontSee('کجا می‌خواهید بازی کنید؟');

        $this->post(route('match.answer'), ['step' => 'materials', 'answer' => ['none']]);
        $this->post(route('match.answer'), ['step' => 'players', 'answer' => 'one-child-adult']);

        $this->assertSame('home-inside', MatchSession::query()->firstOrFail()->context_json['location']);
    }

    public function test_step_skipping_and_conflicting_material_answers_fail_closed(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'connection'])
            ->assertSessionHasErrors('answer');
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => 5, 'age_months' => 0]);
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'bored']);
        $this->post(route('match.answer'), ['step' => 'duration', 'answer' => 10]);
        $this->post(route('match.answer'), ['step' => 'location', 'answer' => 'home-inside']);
        $this->post(route('match.answer'), ['step' => 'materials', 'answer' => ['none', 'paper']])
            ->assertSessionHasErrors('answer');
        $this->assertDatabaseCount('match_sessions', 0);
    }

    public function test_match_mutations_are_rate_limited_per_guest_session(): void
    {
        $this->get(route('match.show'));
        for ($attempt = 1; $attempt <= 30; $attempt++) {
            $this->post(route('match.back'))->assertRedirect(route('match.show'));
        }

        $this->post(route('match.back'))->assertTooManyRequests();
    }
}
