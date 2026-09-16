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
            ->assertSee('چند سالشه؟')->assertSee('سؤال 1 از 8');
    }

    public function test_age_bounds_and_persian_digits_are_normalized(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => '۰', 'age_months' => '۵'])
            ->assertSessionHasErrors('age_years');
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => '۰', 'age_months' => '۶'])
            ->assertRedirect(route('match.show'));
        $this->get(route('match.show'))->assertOk()->assertSee('الان چه موقعیتیه؟');
    }

    public function test_guest_flow_persists_normalized_context_once_without_results(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => 4, 'age_months' => 2]);
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'between-meals']);
        $this->post(route('match.answer'), ['step' => 'duration', 'answer' => 15]);
        $this->post(route('match.answer'), ['step' => 'location', 'answer' => 'home-inside']);
        $this->post(route('match.answer'), ['step' => 'materials', 'answer' => ['paper-pencil', 'ball']]);
        $this->post(route('match.answer'), ['step' => 'players', 'answer' => 'child-and-adult']);
        $this->post(route('match.answer'), ['step' => 'caregiver_energy', 'answer' => 'medium']);
        $response = $this->post(route('match.answer'), ['step' => 'mood', 'answer' => 'calm']);

        $match = MatchSession::query()->firstOrFail();
        $response->assertRedirect(route('matches.show', $match));
        $this->assertSame(50, $match->age_months);
        $this->assertSame(MatchOutcome::Collecting, $match->outcome);
        $this->assertSame(['paper-pencil', 'ball'], $match->context_json['available_materials']);
        $this->assertTrue($match->context_json['adult_present']);
        $this->assertSame('child-and-adult', $match->context_json['player_requirement']);
        $this->assertSame('medium', $match->context_json['caregiver_energy']);
        $this->assertSame('calm', $match->context_json['child_mood']);
        $this->assertDatabaseCount('guest_identities', 1);
        $this->assertDatabaseCount('match_results', 0);
        $this->get(route('matches.show', $match))->assertOk()->assertSee('پیشنهادها هنوز آماده نیستند');
        $this->get(route('match.show'))->assertOk()->assertSee('حالا تیله این لحظه را می‌شناسد');

        $this->post(route('match.back'));
        $this->get(route('match.show'))->assertOk()->assertSee('حالا تیله این لحظه را می‌شناسد');
        $this->post(route('match.answer'), ['step' => 'mood', 'answer' => 'calm']);
        $this->assertDatabaseCount('match_sessions', 1);
        $this->assertSame(1, GuestIdentity::query()->count());
    }

    public function test_approved_situation_and_location_options_are_rendered_in_order(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => 3, 'age_months' => 0]);
        $this->get(route('match.show'))->assertOk()
            ->assertSeeInOrder(['بین وعده‌های غذایی', 'بعد از غذا', 'بین کارهای روزمره', 'قبل از خواب'])
            ->assertDontSee('رستوران')
            ->assertDontSee('ماشین')
            ->assertDontSee('مهمانی')
            ->assertDontSee('وقت با هم بودن');
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'party'])
            ->assertSessionHasErrors('answer');
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'rainy-day'])
            ->assertSessionHasErrors('answer');
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'before-bed']);
        $this->post(route('match.answer'), ['step' => 'duration', 'answer' => 30]);
        $this->get(route('match.show'))->assertOk()
            ->assertSeeInOrder(['داخل خانه', 'بیرون', 'رستوران', 'ماشین', 'مهمانی']);
    }

    public function test_caregiver_energy_options_and_rtl_back_control_are_always_available(): void
    {
        $this->get(route('match.show'))->assertOk()->assertSee('›');
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => 3, 'age_months' => 0]);
        $this->get(route('match.show'))->assertOk()->assertSee('aria-label="سؤال قبل">›</button>', false);

        foreach ([
            ['situation', 'between-meals'], ['duration', 15], ['location', 'home-inside'],
            ['materials', ['none']], ['players', 'child-and-adult'],
        ] as [$step, $answer]) {
            $this->post(route('match.answer'), ['step' => $step, 'answer' => $answer])->assertRedirect(route('match.show'));
        }

        $this->get(route('match.show'))->assertOk()
            ->assertSee('انرژی‌تون چقدره؟')
            ->assertSeeInOrder(['کم', 'متوسط', 'زیاد']);
        $this->post(route('match.answer'), ['step' => 'caregiver_energy', 'answer' => 'medium'])
            ->assertRedirect(route('match.show'));
        $this->get(route('match.show'))->assertOk()
            ->assertSee('حال کودک چطوره؟')
            ->assertSeeInOrder(['آرام', 'بی‌حوصله', 'پرانرژی', 'نیاز به توجه']);
    }

    public function test_step_skipping_and_conflicting_material_answers_fail_closed(): void
    {
        $this->get(route('match.show'));
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'between-meals'])
            ->assertSessionHasErrors('answer');
        $this->post(route('match.answer'), ['step' => 'age', 'age_years' => 5, 'age_months' => 0]);
        $this->post(route('match.answer'), ['step' => 'situation', 'answer' => 'between-routines']);
        $this->post(route('match.answer'), ['step' => 'duration', 'answer' => 15]);
        $this->post(route('match.answer'), ['step' => 'location', 'answer' => 'home-inside']);
        $this->post(route('match.answer'), ['step' => 'materials', 'answer' => ['none', 'paper-pencil']])
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
