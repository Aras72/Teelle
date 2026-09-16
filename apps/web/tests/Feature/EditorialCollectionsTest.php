<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EditorialCollectionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_collection_pages_are_no_longer_available(): void
    {
        $this->get('/collections')->assertNotFound();
        $this->get('/collections/example')->assertNotFound();
        $this->get('/')->assertOk()->assertDontSee('>بازی‌ها<', false);
    }

    public function test_collection_controls_and_separate_draft_button_are_removed_from_admin(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::query()->where('code', 'admin')->value('id'));

        $this->actingAs($admin)->get('/admin/content')
            ->assertOk()
            ->assertDontSee('مجموعه‌ها')
            ->assertDontSee('پیش‌نویس تازه')
            ->assertSee('افزودن بازی‌ها');
        $this->actingAs($admin)->get('/admin/content/collections')->assertNotFound();
    }
}
