<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EntitlementStatus;
use App\Jigari\JigariAccess;
use App\Models\ChildProfile;
use App\Models\Entitlement;
use App\Models\Household;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class JigariChildProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_jigari_page_lists_provisional_toman_prices_without_fake_checkout(): void
    {
        $this->seed(PlanSeeder::class);

        $this->get(route('jigari.show'))->assertOk()
            ->assertSee('تیله جیگری')
            ->assertSee('۳ ماهه')->assertSee('۶ ماهه')->assertSee('۱۲ ماهه')
            ->assertSee('images/marbles/jigari-ruby-v1.webp', false)
            ->assertSee('۳۹۰٬۰۰۰ تومان')->assertSee('۶۹۰٬۰۰۰ تومان')->assertSee('۱٬۱۹۰٬۰۰۰ تومان')
            ->assertSee('قیمت آزمایشی و قابل تغییر')
            ->assertSee('درگاه پرداخت بعد از MVP اضافه می‌شود')
            ->assertSee('پروفایل کودک، برنامه‌ریزی بازی، کشف دقیق‌تر و کیفیت بالاتر')
            ->assertDontSee('پروفایل کودک با کمترین داده لازم.')
            ->assertDontSee('همه امکانات تیله جیگری.')
            ->assertSee('پیشنهاد بازی متناسب با شخصیت کودک شما')
            ->assertSee('دوره‌های خاطره بازی')
            ->assertDontSee('سه انتخاب، بدون تفاوت در امکانات')
            ->assertDontSee('/checkout', false)
            ->assertDontSee('یک ماهه');
    }

    public function test_public_jigari_page_has_an_honest_empty_plan_state(): void
    {
        $this->get(route('jigari.show'))->assertOk()
            ->assertSee('هیچ قیمت آزمایشی فعالی نمایش داده نمی‌شود')
            ->assertDontSee('/checkout', false);
    }

    public function test_free_member_cannot_access_or_mutate_child_profiles(): void
    {
        [$user] = $this->householdUser();

        $this->actingAs($user)->get(route('account.children.index'))->assertForbidden();
        $this->post(route('account.children.store'), [
            'nickname' => 'باران',
            'birth_month' => now('Asia/Tehran')->subMonths(36)->format('Y-m'),
            'relationship_code' => 'parent',
        ])->assertForbidden();

        $this->assertDatabaseCount('child_profiles', 0);
    }

    public function test_active_jigari_member_can_create_edit_and_archive_own_profile(): void
    {
        [$user, $household] = $this->householdUser();
        $this->activate($user);

        $this->actingAs($user)->post(route('account.children.store'), [
            'nickname' => ' باران ',
            'birth_month' => now('Asia/Tehran')->subMonths(48)->format('Y-m'),
            'relationship_code' => 'parent',
        ])->assertRedirect(route('account.children.index'));

        $child = ChildProfile::query()->firstOrFail();
        $this->assertSame($household->id, $child->household_id);
        $this->assertSame('باران', $child->nickname);
        $this->assertDatabaseHas('child_relationships', [
            'child_profile_id' => $child->id, 'user_id' => $user->id, 'relationship_code' => 'parent',
        ]);

        $persianBirthMonth = strtr($child->birth_month, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
        $this->get(route('account.children.index'))->assertOk()->assertSee('باران')->assertSee($persianBirthMonth);
        $this->put(route('account.children.update', $child->public_id), [
            'nickname' => 'نیکا',
            'birth_month' => now('Asia/Tehran')->subMonths(49)->format('Y-m'),
            'relationship_code' => 'caregiver',
        ])->assertRedirect(route('account.children.index'));
        $this->assertSame('نیکا', $child->fresh()->nickname);
        $this->assertDatabaseHas('child_relationships', [
            'child_profile_id' => $child->id, 'user_id' => $user->id, 'relationship_code' => 'caregiver',
        ]);

        $this->post(route('account.children.archive', $child->public_id))
            ->assertRedirect(route('account.children.index'));
        $this->assertSame('archived', $child->fresh()->status);
        $this->assertDatabaseHas('child_profiles', ['id' => $child->id]);
    }

    public function test_birth_month_accepts_exact_boundaries_and_rejects_out_of_range_values(): void
    {
        [$user] = $this->householdUser();
        $this->activate($user);
        $this->actingAs($user);

        foreach ([5, 156] as $months) {
            $this->from(route('account.children.create'))->post(route('account.children.store'), [
                'birth_month' => now('Asia/Tehran')->subMonths($months)->format('Y-m'),
                'relationship_code' => 'parent',
            ])->assertRedirect(route('account.children.create'))->assertSessionHasErrors('birth_month');
        }

        foreach ([6, 155] as $months) {
            $this->post(route('account.children.store'), [
                'birth_month' => now('Asia/Tehran')->subMonths($months)->format('Y-m'),
                'relationship_code' => 'parent',
            ])->assertRedirect(route('account.children.index'));
        }

        $this->assertDatabaseCount('child_profiles', 2);
    }

    public function test_profile_lookup_is_scoped_to_the_owning_household(): void
    {
        [$owner, $household] = $this->householdUser();
        [$other] = $this->householdUser();
        $this->activate($owner);
        $this->activate($other);
        $child = $household->childProfiles()->create([
            'nickname' => 'خصوصی',
            'birth_month' => now('Asia/Tehran')->subMonths(60)->format('Y-m'),
            'status' => 'active',
        ]);

        $this->actingAs($other)->get(route('account.children.edit', $child->public_id))->assertNotFound();
        $this->put(route('account.children.update', $child->public_id), [
            'nickname' => 'تغییر غیرمجاز',
            'birth_month' => $child->birth_month,
            'relationship_code' => 'parent',
        ])->assertNotFound();
        $this->post(route('account.children.archive', $child->public_id))->assertNotFound();
        $this->assertSame('خصوصی', $child->fresh()->nickname);
        $this->assertSame('active', $child->fresh()->status);
    }

    public function test_expiry_refund_and_revocation_remove_access_without_deleting_profiles(): void
    {
        [$user, $household] = $this->householdUser();
        $child = $household->childProfiles()->create([
            'nickname' => 'ماندگار',
            'birth_month' => now('Asia/Tehran')->subMonths(72)->format('Y-m'),
            'status' => 'active',
        ]);

        Entitlement::query()->create([
            'user_id' => $user->id, 'product_code' => 'jigari', 'status' => EntitlementStatus::Active,
            'starts_at' => now()->subMonths(4), 'ends_at' => now()->subDay(),
        ]);
        Entitlement::query()->create([
            'user_id' => $user->id, 'product_code' => 'jigari', 'status' => EntitlementStatus::Refunded,
            'starts_at' => now()->subDay(), 'ends_at' => now()->addMonth(),
        ]);
        Entitlement::query()->create([
            'user_id' => $user->id, 'product_code' => 'jigari', 'status' => EntitlementStatus::Active,
            'starts_at' => now()->subDay(), 'ends_at' => now()->addMonth(), 'revoked_at' => now(),
        ]);

        $this->assertFalse(app(JigariAccess::class)->activeFor($user));
        $this->actingAs($user)->get(route('account.children.index'))->assertForbidden();
        $this->assertDatabaseHas('child_profiles', ['id' => $child->id, 'status' => 'active']);
    }

    /** @return array{User, Household} */
    private function householdUser(): array
    {
        $user = User::factory()->create();
        $household = Household::query()->create(['owner_user_id' => $user->id]);
        DB::table('household_members')->insert([
            'household_id' => $household->id, 'user_id' => $user->id, 'role' => 'owner',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return [$user, $household];
    }

    private function activate(User $user): Entitlement
    {
        return Entitlement::query()->create([
            'user_id' => $user->id,
            'product_code' => 'jigari',
            'status' => EntitlementStatus::Active,
            'starts_at' => now()->subMinute(),
            'ends_at' => now()->addMonths(3),
        ]);
    }
}
