<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SiteContentAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_only_manager_can_open_controlled_site_content_editor(): void
    {
        $support = $this->userWithRole('support_admin');
        $admin = $this->userWithRole('admin');

        $this->get('/admin/content/site-content')->assertUnauthorized();
        $this->actingAs($support)->get('/admin/content/site-content')->assertForbidden();
        $this->actingAs($admin)->get('/admin/content/site-content')->assertOk()->assertSee('متن‌ها و چیدمان');
    }

    public function test_manager_can_update_safe_copy_and_layout_choices(): void
    {
        $admin = $this->userWithRole('admin');
        $payload = [
            'nav_jigari_label' => 'عضویت جیگری', 'nav_about_label' => 'قصه تیله', 'nav_magazine_label' => 'مجله تیله', 'nav_order' => 'about_magazine_jigari',
            'jigari_title' => 'عضویت خانواده‌ها', 'jigari_intro' => 'برای بازی بیشتر کنار کودک، عضو شوید.',
            'magazine_title' => 'خواندنی‌های تیله', 'magazine_intro' => 'مطالب کوتاه برای وقت کنار کودک.',
            'home_title' => 'همین حالا بازی را شروع کنیم', 'home_intro' => 'چند پاسخ کوتاه تا بازی واقعی',
            'home_cta_label' => 'بازی را پیدا کن', 'brand_promise' => 'کودک به هم‌بازی نیاز دارد',
            'home_alignment' => 'start', 'about_title' => 'کنار هم بازی کنیم',
            'about_lead' => 'تیله انتخاب را کوتاه و شروع بازی را آسان می‌کند.', 'about_cta_label' => 'شروع کنیم',
        ];

        $this->actingAs($admin)->put('/admin/content/site-content', $payload)->assertRedirect();
        $this->get('/')->assertOk()->assertSee('همین حالا بازی را شروع کنیم')->assertSee('بازی را پیدا کن');
        $this->get('/about')->assertOk()->assertSee('کنار هم بازی کنیم')->assertSee('شروع کنیم');
        $this->get('/jigari')->assertOk()->assertSee('عضویت خانواده‌ها');
        $this->get('/magazine')->assertOk()->assertSee('خواندنی‌های تیله');
        $this->assertDatabaseHas('audit_logs', ['action' => 'site.content.updated']);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }
}
