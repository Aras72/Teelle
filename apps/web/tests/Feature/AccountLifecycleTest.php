<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Auth\GuestAccountContinuity;
use App\Auth\OtpSender;
use App\Enums\PlayState;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\GuestIdentity;
use App\Models\MatchResult;
use App\Models\MatchSession;
use App\Models\PlaySession;
use App\Models\SavedGame;
use App\Models\User;
use DomainException;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

final class AccountLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_adult_household_sends_verification_and_rotates_session(): void
    {
        Notification::fake();
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('نامی بنویسید که کودک شما را با آن می‌شناسد؛ مثلاً: داییِ ارغوان، مامانِ کوهیار.');
        $oldSessionId = session()->getId();

        $this->post(route('register.store'), [
            'name' => 'آراس', 'email' => 'ARAS@example.com',
            'password' => 'SecurePass!2026', 'password_confirmation' => 'SecurePass!2026',
            'privacy_accepted' => '1',
        ])->assertRedirect(route('verification.notice'));

        $user = User::query()->where('email', 'aras@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($oldSessionId, session()->getId());
        $this->assertDatabaseHas('households', ['owner_user_id' => $user->id]);
        $this->assertDatabaseHas('household_members', ['user_id' => $user->id, 'role' => 'owner']);
        $this->assertNotNull($user->privacy_accepted_at);
        $this->assertSame('2026-09-14', $user->privacy_policy_version);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_registration_rejects_a_common_password_even_when_it_looks_complex(): void
    {
        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'مراقب', 'email' => 'caregiver@example.com',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!',
            'privacy_accepted' => '1',
        ])->assertRedirect(route('register'))->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'caregiver@example.com']);
    }

    public function test_registration_requires_privacy_acceptance_without_forcing_policy_open(): void
    {
        $this->get(route('register'))->assertOk()->assertSee(route('privacy'));
        $this->get(route('privacy'))->assertOk()->assertDontSee('این متن، پیش‌نویس عملیاتی MVP است');

        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'داییِ ارغوان', 'email' => 'privacy@example.com',
            'password' => 'SecurePass!2026', 'password_confirmation' => 'SecurePass!2026',
        ])->assertRedirect(route('register'))->assertSessionHasErrors('privacy_accepted');

        $this->assertDatabaseMissing('users', ['email' => 'privacy@example.com']);
    }

    public function test_login_uses_generic_failure_for_wrong_and_inactive_accounts_and_is_throttled(): void
    {
        User::factory()->create(['email' => 'disabled@example.com', 'status' => 'disabled']);
        $wrong = $this->from(route('login'))->post(route('login.store'), ['email' => 'missing@example.com', 'password' => 'wrong']);
        $inactive = $this->from(route('login'))->post(route('login.store'), ['email' => 'disabled@example.com', 'password' => 'password']);
        $wrong->assertSessionHasErrors(['email' => 'اطلاعات ورود درست نیست']);
        $inactive->assertSessionHasErrors(['email' => 'اطلاعات ورود درست نیست']);
        $this->assertGuest();

        foreach (range(1, 5) as $attempt) {
            $this->post(route('login.store'), ['email' => 'rate@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');
        }
        $this->post(route('login.store'), ['email' => 'rate@example.com', 'password' => 'wrong'])->assertTooManyRequests();
    }

    public function test_signed_email_verification_and_generic_recovery_are_available(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'))
            ->assertOk()->assertSee('پیام تأیید برای')->assertSee('ارسال دوباره پیام')->assertDontSee('ارسال دوباره پیوند');
        $verificationUrl = URL::temporarySignedRoute('verification.verify', now()->addMinutes(10), ['id' => $user->id, 'hash' => sha1($user->email)]);
        $this->actingAs($user)->get($verificationUrl)->assertRedirect(route('account.show'));
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->post(route('logout'))->assertRedirect(route('home'));

        $known = $this->post(route('password.email'), ['email' => $user->email]);
        $unknown = $this->post(route('password.email'), ['email' => 'unknown@example.com']);
        $message = 'اگر حسابی با این ایمیل وجود داشته باشد، پیوند بازیابی ارسال می‌شود';
        $known->assertSessionHas('status', $message);
        $unknown->assertSessionHas('status', $message);
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_reset_changes_password_and_revokes_database_sessions(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        DB::table('sessions')->insert([
            'id' => Str::random(40), 'user_id' => $user->id, 'ip_address' => '127.0.0.1',
            'user_agent' => 'test', 'payload' => 'test', 'last_activity' => now()->timestamp,
        ]);

        $this->post(route('password.update'), [
            'token' => $token, 'email' => $user->email,
            'password' => 'NewSecure!2026', 'password_confirmation' => 'NewSecure!2026',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('NewSecure!2026', $user->fresh()->password));
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
    }

    public function test_successful_login_merges_guest_records_once_and_writes_audit(): void
    {
        $token = Str::random(64);
        $guest = GuestIdentity::factory()->create(['token_hash' => hash('sha256', $token)]);
        [$match, $play] = $this->guestPlay($guest);
        $user = User::factory()->create(['email' => 'merge@example.com']);

        $this->withSession(['teelle.guest_token' => $token])
            ->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('account.show'));

        $this->assertSame($user->id, $match->fresh()->user_id);
        $this->assertNull($match->fresh()->guest_identity_id);
        $this->assertSame($user->id, $play->fresh()->user_id);
        $this->assertNull($play->fresh()->guest_identity_id);
        $this->assertDatabaseMissing('guest_identities', ['id' => $guest->id]);
        $this->assertDatabaseHas('audit_logs', ['actor_user_id' => $user->id, 'action' => 'identity.guest_merged']);
        $this->assertSame(['matches' => 0, 'plays' => 0], app(GuestAccountContinuity::class)->merge($user, session()->driver()));
        $this->assertDatabaseCount('audit_logs', 1);
    }

    public function test_guest_merge_conflict_rolls_back_without_partial_ownership_change(): void
    {
        $token = Str::random(64);
        $guest = GuestIdentity::factory()->create(['token_hash' => hash('sha256', $token)]);
        [$match, $play] = $this->guestPlay($guest);
        $other = User::factory()->create();
        $play->update(['user_id' => $other->id, 'guest_identity_id' => null]);
        $target = User::factory()->create();
        $this->withSession(['teelle.guest_token' => $token]);

        $this->expectException(DomainException::class);
        try {
            app(GuestAccountContinuity::class)->merge($target, session()->driver());
        } finally {
            $this->assertSame($guest->id, $match->fresh()->guest_identity_id);
            $this->assertNull($match->fresh()->user_id);
            $this->assertSame($other->id, $play->fresh()->user_id);
            $this->assertDatabaseCount('audit_logs', 0);
        }
    }

    public function test_login_does_not_leave_an_authenticated_session_when_guest_merge_conflicts(): void
    {
        $token = Str::random(64);
        $guest = GuestIdentity::factory()->create(['token_hash' => hash('sha256', $token)]);
        [$match, $play] = $this->guestPlay($guest);
        $play->update(['user_id' => User::factory()->create()->id, 'guest_identity_id' => null]);
        $target = User::factory()->create(['email' => 'closed@example.com']);

        $this->withSession(['teelle.guest_token' => $token])
            ->from(route('login'))->post(route('login.store'), ['email' => $target->email, 'password' => 'password'])
            ->assertRedirect(route('login'))->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame($guest->id, $match->fresh()->guest_identity_id);
        $this->assertDatabaseHas('guest_identities', ['id' => $guest->id]);
        $this->assertDatabaseCount('audit_logs', 0);
    }

    public function test_account_history_and_saved_mutations_are_scoped_to_current_user(): void
    {
        $owner = User::factory()->create(['name' => 'داییِ ارغوان']);
        $other = User::factory()->create();
        [$match, $play, $game] = $this->userPlay($owner, 'بازی خصوصی آراس');
        SavedGame::query()->create(['user_id' => $owner->id, 'game_id' => $game->id]);

        $this->actingAs($other)->get(route('account.show'))->assertOk()->assertDontSee('بازی خصوصی آراس');
        $this->delete(route('account.saved.destroy', $game))->assertRedirect();
        $this->assertDatabaseHas('saved_games', ['user_id' => $owner->id, 'game_id' => $game->id]);

        $this->actingAs($owner)->get(route('account.show'))->assertOk()->assertSee('بازی خصوصی آراس')
            ->assertSee('سلام داییِ ارغوان')->assertSee('مثلاً: داییِ ارغوان، مامانِ کوهیار')
            ->assertSee('پس از ثبت درخواست حذف حساب، ۳ روز برای درخواست بازگردانی از پشتیبانی فرصت دارید.')
            ->assertSee('پروفایل کودک')->assertSee('پروفایل کودک فقط با عضویت فعال جیگری در دسترس است.')
            ->assertSee('شروع یک بازی')->assertSee('خروج از حساب');
        $this->put(route('account.update'), ['name' => 'نام تازه', 'timezone' => 'Asia/Tehran'])
            ->assertRedirect()->assertSessionHas('status', 'تنظیمات حساب ذخیره شد');
        $this->assertDatabaseHas('users', ['id' => $owner->id, 'name' => 'نام تازه', 'timezone' => 'Asia/Tehran']);
        $this->assertSame($owner->id, $play->user_id);
        $this->assertSame($owner->id, $match->user_id);
    }

    public function test_otp_sender_is_bound_to_a_fail_closed_implementation(): void
    {
        $this->assertFalse(config('features.otp_login'));
        $this->expectException(DomainException::class);
        app(OtpSender::class)->send('+989121234567', '123456');
    }

    /** @return array{MatchSession, PlaySession} */
    private function guestPlay(GuestIdentity $guest): array
    {
        $match = MatchSession::factory()->create(['guest_identity_id' => $guest->id]);
        [, $result] = $this->resultFor($match, 'بازی مهمان');
        $play = PlaySession::query()->create([
            'match_result_id' => $result->id, 'guest_identity_id' => $guest->id,
            'state' => PlayState::Completed, 'started_at' => now(), 'completed_at' => now(),
        ]);

        return [$match, $play];
    }

    /** @return array{MatchSession, PlaySession, Game} */
    private function userPlay(User $user, string $title): array
    {
        $match = MatchSession::factory()->create(['user_id' => $user->id, 'guest_identity_id' => null]);
        [$game, $result] = $this->resultFor($match, $title);
        $play = PlaySession::query()->create([
            'match_result_id' => $result->id, 'user_id' => $user->id,
            'state' => PlayState::Completed, 'started_at' => now(), 'completed_at' => now(),
        ]);

        return [$match, $play, $game];
    }

    /** @return array{Game, MatchResult} */
    private function resultFor(MatchSession $match, string $title): array
    {
        $game = Game::factory()->create();
        $version = GameVersion::factory()->for($game)->create(['title' => $title]);
        $result = MatchResult::query()->create([
            'match_session_id' => $match->id, 'rank' => 1, 'game_id' => $game->id,
            'game_version_id' => $version->id, 'score' => 90, 'explanation_json' => ['summary' => 'test'],
        ]);

        return [$game, $result];
    }
}
