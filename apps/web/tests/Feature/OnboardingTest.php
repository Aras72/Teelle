<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_new_account_is_guided_to_skippable_onboarding(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarding_completed_at' => null]);

        $this->actingAs($user)->get('/account')->assertRedirect(route('onboarding.show'));
        $this->actingAs($user)->get('/onboarding')->assertOk()
            ->assertSee('اینجا بازی اول است')->assertSee('فعلاً رد می‌کنم')
            ->assertSee('نام خانوادگی، عکس یا جنسیت نمی‌خواهیم');
    }

    public function test_guest_and_unverified_user_cannot_open_onboarding(): void
    {
        $this->get('/onboarding')->assertRedirect(route('login'));
        $this->actingAs(User::factory()->unverified()->create())->get('/onboarding')->assertRedirect(route('verification.notice'));
    }

    public function test_onboarding_completion_is_idempotent_and_destinations_are_allowlisted(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarding_completed_at' => null]);

        $this->actingAs($user)->post('/onboarding', ['next' => 'match'])->assertRedirect(route('match.show'));
        $completedAt = $user->fresh()->onboarding_completed_at;
        $this->assertNotNull($completedAt);
        $this->actingAs($user)->post('/onboarding', ['next' => 'account'])->assertRedirect(route('account.show'));
        $this->assertTrue($completedAt->equalTo($user->fresh()->onboarding_completed_at));
        $this->actingAs($user)->post('/onboarding', ['next' => 'https://example.com'])->assertSessionHasErrors('next');
    }

    public function test_completed_user_does_not_repeat_onboarding(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarding_completed_at' => now()]);

        $this->actingAs($user)->get('/onboarding')->assertRedirect(route('account.show'));
        $this->actingAs($user)->get('/account')->assertOk();
    }
}
