<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PrivacyRequest;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class AccountPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_requires_verified_owner_and_current_password(): void
    {
        $user = User::factory()->create();

        $this->post(route('account.privacy.export'), ['export_password' => 'password'])->assertRedirect(route('login'));
        $this->actingAs($user)->from(route('account.show'))
            ->post(route('account.privacy.export'), ['export_password' => 'wrong'])
            ->assertRedirect(route('account.show'))->assertSessionHasErrors('export_password');

        $this->assertDatabaseCount('privacy_requests', 0);
        $this->assertDatabaseCount('audit_logs', 0);
    }

    public function test_export_contains_only_portable_data_for_current_user(): void
    {
        $user = User::factory()->create(['email' => 'owner@example.com', 'password' => Hash::make('Correct!2026')]);
        User::factory()->create(['email' => 'other@example.com']);

        $response = $this->actingAs($user)->post(route('account.privacy.export'), ['export_password' => 'Correct!2026']);

        $response->assertOk()->assertHeader('content-type', 'application/json; charset=UTF-8');
        $payload = json_decode($response->streamedContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('teelle-account-export-v1', $payload['format']);
        $this->assertSame($user->public_id, $payload['account']['public_id']);
        $this->assertSame('owner@example.com', $payload['account']['email']);
        $this->assertArrayNotHasKey('password', $payload['account']);
        $this->assertStringNotContainsString('other@example.com', $response->streamedContent());
        $this->assertDatabaseHas('privacy_requests', ['user_id' => $user->id, 'request_type' => 'export', 'status' => 'completed']);
        $this->assertDatabaseHas('audit_logs', ['actor_user_id' => $user->id, 'actor_type' => 'user', 'action' => 'privacy.export_completed']);
    }

    public function test_deletion_request_has_three_day_grace_and_deactivates_session(): void
    {
        $this->travelTo(now()->startOfSecond());
        $user = User::factory()->create(['password' => Hash::make('Correct!2026')]);

        $this->actingAs($user)->post(route('account.privacy.deletion.store'), ['deletion_password' => 'Correct!2026'])->assertRedirect(route('home'));

        $request = PrivacyRequest::query()->where('user_id', $user->id)->where('request_type', 'deletion')->sole();
        $this->assertSame('pending', $request->status);
        $this->assertSame('active', $request->active_key);
        $this->assertTrue($request->scheduled_for->equalTo(now()->addDays(3)));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'deletion_pending']);
        $this->assertGuest();
        $this->assertSame(1, DB::table('audit_logs')->where('action', 'privacy.deletion_requested')->count());
    }

    public function test_admin_can_reactivate_pending_deletion_within_three_days(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create(['password' => Hash::make('Correct!2026')]);
        $this->actingAs($user)->post(route('account.privacy.deletion.store'), ['deletion_password' => 'Correct!2026']);
        $privacyRequest = PrivacyRequest::query()->where('user_id', $user->id)->sole();
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::query()->where('code', 'admin')->sole());

        $this->actingAs($admin)->post(route('admin.content.accounts.reactivate', $privacyRequest))
            ->assertRedirect()->assertSessionHas('status', 'حساب بازگردانی شد و کاربر دوباره می‌تواند وارد شود');

        $this->assertSame('cancelled', $privacyRequest->fresh()->status);
        $this->assertNull($privacyRequest->fresh()->active_key);
        $this->assertNotNull($privacyRequest->fresh()->cancelled_at);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'active']);
        $this->assertDatabaseHas('audit_logs', ['actor_user_id' => $admin->id, 'actor_type' => 'admin', 'action' => 'privacy.account_reactivated_by_admin']);
    }

    public function test_account_page_exposes_honest_privacy_controls_and_status(): void
    {
        $user = User::factory()->create();
        PrivacyRequest::query()->create([
            'user_id' => $user->id,
            'request_type' => 'deletion',
            'status' => 'pending',
            'active_key' => 'active',
            'requested_at' => now(),
            'scheduled_for' => now()->addDays(3),
        ]);

        $this->actingAs($user)->get(route('account.show'))->assertOk()
            ->assertSee('حریم خصوصی حساب')->assertSee('دریافت فایل داده‌های من')
            ->assertSee('فعلاً حسابم بماند')->assertSee('تا آن روز می‌توانید آن را لغو کنید');

        $this->get(route('privacy'))->assertOk()->assertSee('سیاست حریم خصوصی تیله');
    }
}
