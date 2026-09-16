<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EntitlementStatus;
use App\Models\Entitlement;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\Purchase;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_support_admin_can_list_users_and_see_latest_plan_but_not_manager_surfaces(): void
    {
        $support = $this->staff('support_admin');
        $member = User::factory()->create(['name' => 'داییِ ارغوان', 'email' => 'uncle@example.test']);
        $plan = Plan::query()->where('code', 'jigari-3m')->firstOrFail();
        $purchase = Purchase::query()->create([
            'user_id' => $member->id, 'plan_id' => $plan->id, 'status' => 'paid',
            'amount_minor' => $plan->price_minor, 'currency' => 'IRR', 'provider' => 'manual-test',
            'provider_reference' => 'test-plan-1', 'paid_at' => now(),
        ]);
        Entitlement::query()->create([
            'user_id' => $member->id, 'purchase_id' => $purchase->id, 'product_code' => 'jigari',
            'status' => EntitlementStatus::Active, 'starts_at' => now()->subDay(), 'ends_at' => now()->addMonths(3),
        ]);

        $this->get(route('admin.content.users.index'))->assertUnauthorized();
        $this->actingAs(User::factory()->create())->get(route('admin.content.users.index'))->assertForbidden();
        $this->actingAs($support)->get(route('admin.content.users.index', ['q' => 'uncle@example.test']))
            ->assertOk()->assertSee('داییِ ارغوان')->assertSee($plan->title)->assertSee('فعال');
        $this->actingAs($support)->get(route('admin.content.index'))->assertOk()->assertSee('افزودن بازی‌ها')->assertSee('مجله تیله');
        $this->actingAs($support)->get(route('admin.content.plans.index'))->assertForbidden();
    }

    public function test_support_admin_can_correct_member_contact_details_with_audit(): void
    {
        $support = $this->staff('support_admin');
        $member = User::factory()->create(['name' => 'مامان کوهیار', 'phone_e164' => '+989121111111', 'phone_verified_at' => now()]);

        $this->actingAs($support)->put(route('admin.content.users.update', $member), [
            'name' => 'مامانِ کوهیار', 'email' => 'new-parent@example.test', 'phone_e164' => '+989122222222',
            'reason' => 'اصلاح مشخصات طبق تیکت ۱۲۳',
        ])->assertRedirect(route('admin.content.users.edit', $member))->assertSessionHas('status');

        $member->refresh();
        $this->assertSame('مامانِ کوهیار', $member->name);
        $this->assertSame('new-parent@example.test', $member->email);
        $this->assertSame('+989122222222', $member->phone_e164);
        $this->assertNull($member->email_verified_at);
        $this->assertNull($member->phone_verified_at);
        $this->assertDatabaseHas('audit_logs', [
            'actor_user_id' => $support->id, 'action' => 'identity.user.updated',
            'target_type' => User::class, 'target_id' => (string) $member->id,
        ]);
    }

    public function test_support_admin_cannot_edit_a_manager_or_assign_roles(): void
    {
        $support = $this->staff('support_admin');
        $manager = $this->staff('admin');
        $member = User::factory()->create();

        $this->actingAs($support)->get(route('admin.content.users.edit', $manager))->assertForbidden();
        $this->actingAs($support)->put(route('admin.content.users.role.update', $member), [
            'support_admin' => '1', 'reason' => 'درخواست غیرمجاز نقش',
        ])->assertForbidden();
        $this->assertFalse($member->roles()->where('code', 'support_admin')->exists());
    }

    public function test_manager_can_grant_and_revoke_the_lower_admin_role(): void
    {
        $manager = $this->staff('admin');
        $member = User::factory()->create();

        $this->actingAs($manager)->put(route('admin.content.users.role.update', $member), [
            'support_admin' => '1', 'permissions' => ['content.edit', 'articles.edit', 'coverage.view'], 'reason' => 'افزودن ادمین پشتیبانی',
        ])->assertRedirect()->assertSessionHas('status');
        $this->assertTrue($member->roles()->where('code', 'support_admin')->exists());
        $this->assertEqualsCanonicalizing(['articles.edit', 'content.edit', 'coverage.view'], $member->directPermissions()->pluck('code')->all());

        $this->actingAs($manager)->put(route('admin.content.users.role.update', $member), [
            'reason' => 'برداشتن دسترسی ادمین',
        ])->assertRedirect()->assertSessionHas('status');
        $this->assertFalse($member->roles()->where('code', 'support_admin')->exists());
        $this->assertDatabaseCount('audit_logs', 2);
    }

    public function test_support_admin_can_disable_a_member_but_cannot_delete_managers_or_other_admins(): void
    {
        $support = $this->staff('support_admin');
        $member = User::factory()->create();
        $manager = $this->staff('admin');
        $otherSupport = $this->staff('support_admin');

        $this->actingAs($support)->delete(route('admin.content.users.destroy', $member), ['reason' => 'درخواست حذف از تیکت ۴۲'])
            ->assertRedirect(route('admin.content.users.index'))->assertSessionHas('status');
        $this->assertSame('disabled', $member->fresh()->status);
        $this->assertDatabaseHas('audit_logs', ['actor_user_id' => $support->id, 'action' => 'identity.user.disabled']);

        $this->actingAs($support)->delete(route('admin.content.users.destroy', $manager), ['reason' => 'درخواست حذف مدیر'])->assertForbidden();
        $this->actingAs($support)->delete(route('admin.content.users.destroy', $otherSupport), ['reason' => 'درخواست حذف ادمین'])->assertForbidden();
        $this->actingAs($support)->delete(route('admin.content.users.destroy', $support), ['reason' => 'درخواست حذف خود'])->assertForbidden();
        $this->assertSame('active', $manager->fresh()->status);
        $this->assertSame('active', $otherSupport->fresh()->status);
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));
        if ($role === 'support_admin') {
            $ids = Permission::query()->whereIn('code', ['content.edit', 'articles.edit', 'users.view', 'users.edit', 'users.delete'])->pluck('id');
            $user->directPermissions()->sync($ids);
        }

        return $user;
    }
}
