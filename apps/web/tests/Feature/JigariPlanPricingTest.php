<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class JigariPlanPricingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_only_subscription_admin_can_open_and_update_plan_pricing(): void
    {
        $member = User::factory()->create();
        $editor = $this->staff('content_editor');
        $admin = $this->staff('admin');
        $plan = Plan::query()->where('code', 'jigari-3m')->firstOrFail();

        $this->get(route('admin.content.plans.index'))->assertUnauthorized();
        $this->actingAs($member)->get(route('admin.content.plans.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.content.plans.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.content.plans.index'))
            ->assertOk()->assertSee('پلن‌ها و قیمت آزمایشی')->assertSee('۳۹۰٬۰۰۰');

        $this->actingAs($admin)->put(route('admin.content.plans.update', $plan), [
            'title' => ' تیله جیگری سه‌ماهه تازه ',
            'price_toman' => '۴۵۰٬۰۰۰',
            'is_active' => '1',
            'duration_months' => 1,
            'code' => 'trial',
        ])->assertRedirect()->assertSessionHas('status');

        $plan->refresh();
        $this->assertSame('تیله جیگری سه‌ماهه تازه', $plan->title);
        $this->assertSame(4_500_000, $plan->price_minor);
        $this->assertSame('IRR', $plan->currency);
        $this->assertTrue($plan->is_active);
        $this->assertSame(3, $plan->duration_months);
        $this->assertSame('jigari-3m', $plan->code);
        $this->assertDatabaseHas('audit_logs', [
            'actor_user_id' => $admin->id,
            'actor_type' => 'staff',
            'action' => 'commerce.plan.updated',
            'target_type' => Plan::class,
            'target_id' => (string) $plan->id,
        ]);
    }

    public function test_validation_rejects_missing_or_unreasonable_price(): void
    {
        $admin = $this->staff('admin');
        $plan = Plan::query()->where('code', 'jigari-6m')->firstOrFail();

        $this->actingAs($admin)->from(route('admin.content.plans.index'))
            ->put(route('admin.content.plans.update', $plan), [
                'title' => $plan->title,
                'price_toman' => '۰',
            ])->assertRedirect(route('admin.content.plans.index'))->assertSessionHasErrors('price_toman');

        $this->assertSame(6_900_000, $plan->fresh()->price_minor);
        $this->assertDatabaseMissing('audit_logs', ['action' => 'commerce.plan.updated']);
    }

    public function test_disabling_a_plan_hides_it_without_creating_checkout_or_entitlement(): void
    {
        $admin = $this->staff('admin');
        $plan = Plan::query()->where('code', 'jigari-12m')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.content.plans.update', $plan), [
            'title' => $plan->title,
            'price_toman' => '۱٬۱۹۰٬۰۰۰',
        ])->assertRedirect();

        $this->get(route('jigari.show'))->assertOk()
            ->assertDontSee('۱۲ ماهه')->assertDontSee('۱٬۱۹۰٬۰۰۰ تومان')
            ->assertSee('۳۹۰٬۰۰۰ تومان')->assertSee('۶۹۰٬۰۰۰ تومان')
            ->assertDontSee('/checkout', false);
        $this->assertDatabaseCount('purchases', 0);
        $this->assertDatabaseCount('payment_events', 0);
        $this->assertDatabaseCount('entitlements', 0);
    }

    public function test_reseeding_initializes_legacy_null_price_without_overwriting_admin_changes(): void
    {
        $plan = Plan::query()->where('code', 'jigari-3m')->firstOrFail();
        $plan->update(['price_minor' => null, 'is_active' => false]);

        $this->seed(PlanSeeder::class);
        $this->assertSame(3_900_000, $plan->fresh()->price_minor);
        $this->assertTrue($plan->fresh()->is_active);

        $plan->refresh();
        $plan->update(['title' => 'عنوان ویرایش‌شده', 'price_minor' => 4_700_000, 'is_active' => false]);
        $this->seed(PlanSeeder::class);
        $plan->refresh();
        $this->assertSame('عنوان ویرایش‌شده', $plan->title);
        $this->assertSame(4_700_000, $plan->price_minor);
        $this->assertFalse($plan->is_active);
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }
}
