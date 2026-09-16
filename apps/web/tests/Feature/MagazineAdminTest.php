<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class MagazineAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_support_admin_can_write_and_submit_but_only_manager_can_publish(): void
    {
        $support = $this->userWithRole('support_admin');
        $permissionIds = Permission::query()->whereIn('code', ['content.edit', 'articles.edit'])->pluck('id');
        $support->directPermissions()->sync($permissionIds);
        $manager = $this->userWithRole('admin');
        $category = ArticleCategory::query()->where('slug', 'play-guides')->firstOrFail();

        $this->actingAs($support)->get(route('admin.content.articles.index'))->assertOk()->assertSee('مطالب و چرخه انتشار');
        $this->actingAs($support)->post(route('admin.content.articles.store'), $this->payload($category))->assertRedirect();
        $article = Article::query()->where('slug', 'play-together')->firstOrFail();
        $this->actingAs($support)->post(route('admin.content.articles.submit', $article))->assertRedirect();
        $this->actingAs($support)->post(route('admin.content.articles.publish', $article))->assertForbidden();
        $this->get(route('magazine.show', $article))->assertNotFound();

        $this->actingAs($manager)->post(route('admin.content.articles.publish', $article))->assertRedirect();
        $this->get(route('magazine.index'))->assertOk()->assertSee('چطور بازی را شروع کنیم؟');
        $this->get(route('magazine.show', $article))->assertOk()->assertSee('یک شروع ساده');
    }

    public function test_manager_can_assign_distinct_admin_sections_without_publish_permission(): void
    {
        $manager = $this->userWithRole('admin');
        $member = User::factory()->create();

        $this->actingAs($manager)->put(route('admin.content.users.role.update', $member), [
            'support_admin' => '1',
            'permissions' => ['content.edit', 'articles.edit', 'coverage.view'],
            'reason' => 'دسترسی محدود برای تیم محتوا',
        ])->assertRedirect();

        $member->refresh();
        $this->assertTrue($member->hasPermission('content.edit'));
        $this->assertTrue($member->hasPermission('articles.edit'));
        $this->assertTrue($member->hasPermission('coverage.view'));
        $this->assertFalse($member->hasPermission('articles.publish'));
        $this->assertFalse($member->hasPermission('users.edit'));
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }

    private function payload(ArticleCategory $category): array
    {
        return [
            'slug' => 'play-together',
            'title' => 'چطور بازی را شروع کنیم؟',
            'excerpt' => 'چند راه ساده برای شروع بازی کنار کودک.',
            'body_markdown' => "## یک شروع ساده\n\nاز یک انتخاب کوچک شروع کنید.",
            'seo_title' => 'راهنمای شروع بازی کنار کودک',
            'seo_description' => 'راه‌های ساده و کاربردی برای شروع بازی کنار کودک.',
            'categories' => [$category->id],
            'cover_alt' => null,
        ];
    }
}
