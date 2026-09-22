<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Role;
use App\Models\User;
use App\Site\SiteContent;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OwnerMaintenanceSliceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manager_can_delete_an_empty_category_but_not_a_linked_one(): void
    {
        $manager = $this->userWithRole('admin');
        $category = ArticleCategory::query()->create([
            'slug' => 'empty-category', 'title' => 'دسته بدون مطلب', 'is_active' => true, 'sort_order' => 40,
        ]);

        $this->actingAs($manager)->delete(route('admin.content.article-categories.destroy', $category))
            ->assertRedirect(route('admin.content.article-categories.index'))
            ->assertSessionHas('status');
        $this->assertDatabaseMissing('article_categories', ['id' => $category->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'magazine.category.deleted']);

        $linked = ArticleCategory::query()->create([
            'slug' => 'linked-category', 'title' => 'دسته پرمطلب', 'is_active' => true, 'sort_order' => 50,
        ]);
        $article = Article::query()->create([
            'slug' => 'linked-article', 'title' => 'مطلب وصل', 'excerpt' => 'خلاصه', 'body_markdown' => 'متن',
            'status' => 'draft', 'author_id' => $manager->id,
        ]);
        $article->categories()->attach($linked->id);

        $this->actingAs($manager)->delete(route('admin.content.article-categories.destroy', $linked))
            ->assertSessionHasErrors('category');
        $this->assertDatabaseHas('article_categories', ['id' => $linked->id]);
    }

    public function test_support_admin_without_articles_edit_cannot_delete_categories(): void
    {
        $support = $this->userWithRole('support_admin');
        $category = ArticleCategory::query()->create([
            'slug' => 'support-blocked', 'title' => 'دسته بسته', 'is_active' => true, 'sort_order' => 60,
        ]);

        $this->actingAs($support)->delete(route('admin.content.article-categories.destroy', $category))
            ->assertForbidden();
        $this->assertDatabaseHas('article_categories', ['id' => $category->id]);
    }

    public function test_article_form_uses_plain_language_seo_microcopy(): void
    {
        $manager = $this->userWithRole('admin');

        $this->actingAs($manager)->get(route('admin.content.articles.create'))->assertOk()
            ->assertSee('نمایش در گوگل')
            ->assertSee('عنوان در نتیجه جست‌وجو')
            ->assertSee('خالی بگذارید تا همان عنوان مطلب در گوگل نشان داده شود')
            ->assertSee('توضیح کوتاه زیر نتیجه جست‌وجو')
            ->assertDontSee('عنوان SEO')
            ->assertDontSee('توضیح SEO');
    }

    public function test_import_page_shows_form_only_without_excel_or_imported_files(): void
    {
        $manager = $this->userWithRole('admin');

        // DEC-062: مسیر Excel و فهرست فایل‌های ایمپورت‌شده حذف شده است؛ صفحه فقط فرم داخلی سایت را نشان می‌دهد.
        $this->actingAs($manager)->get(route('admin.content.imports.index'))->assertOk()
            ->assertSee('افزودن بازی با فرم')
            ->assertSee('بررسی بازی و پیش‌نمایش')
            ->assertDontSee('بازی‌های پیشنهادی اولیه تیله')
            ->assertDontSee('افزودن از فایل Excel')
            ->assertDontSee('فایل‌های ایمپورت‌شده');
    }

    public function test_site_content_editor_only_exposes_jigari_and_magazine_copy_to_manager(): void
    {
        $support = $this->userWithRole('support_admin');
        $manager = $this->userWithRole('admin');

        $this->actingAs($support)->get(route('admin.content.site.edit'))->assertForbidden();

        $this->actingAs($manager)->get(route('admin.content.site.edit'))->assertOk()
            ->assertSee('تیله جیگری')
            ->assertSee('مجله تیله')
            ->assertSee('ترتیب نمایش')
            ->assertSee('مجله تیله، تیله جیگری، درباره تیله');
    }

    public function test_manager_can_update_jigari_magazine_and_nav_order_for_the_public_site(): void
    {
        $manager = $this->userWithRole('admin');
        $payload = [
            'nav_jigari_label' => 'عضویت', 'nav_about_label' => 'درباره ما', 'nav_magazine_label' => 'مجله',
            'nav_order' => 'magazine_jigari_about',
            'jigari_title' => 'تیله جیگری', 'jigari_intro' => 'معرفی تازه جیگری',
            'magazine_title' => 'مجله', 'magazine_intro' => 'معرفی تازه مجله',
            'home_title' => 'بازی مناسب، برای همین لحظه', 'home_intro' => 'چند سؤال کوتاه، سه بازی مناسب برای همین حالا',
            'home_cta_label' => 'چی بازی کنیم؟', 'brand_promise' => 'کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد',
            'home_alignment' => 'center', 'about_title' => 'راه کوتاه‌تر تا بازی کنار کودک',
            'about_lead' => 'تیله با سه انتخاب روشن، کمک می‌کند تا زودتر در کنار کودک‌تان تجربه‌های شیرین بسازید.',
            'about_cta_label' => 'چی بازی کنیم؟',
        ];

        $this->actingAs($manager)->put(route('admin.content.site.update'), $payload)->assertRedirect();

        $magazine = $this->get(route('magazine.index'))->assertOk();
        $magazine->assertSee('مجله')->assertSee('معرفی تازه مجله');
        $this->get(route('jigari.show'))->assertOk()->assertSee('معرفی تازه جیگری');
        $this->get(route('home'))->assertOk()->assertSee('href="'.route('magazine.index').'"', false);
    }

    public function test_legacy_nav_orders_still_render_after_update(): void
    {
        $manager = $this->userWithRole('admin');
        $this->actingAs($manager)->put(route('admin.content.site.update'), [
            'nav_jigari_label' => 'تیله جیگری', 'nav_about_label' => 'درباره تیله', 'nav_magazine_label' => 'مجله تیله',
            'nav_order' => 'about_jigari',
            'jigari_title' => 'تیله جیگری', 'jigari_intro' => app(SiteContent::class)->all()['jigari_intro'],
            'magazine_title' => 'مجله تیله', 'magazine_intro' => app(SiteContent::class)->all()['magazine_intro'],
            'home_title' => 'بازی مناسب، برای همین لحظه', 'home_intro' => 'چند سؤال کوتاه، سه بازی مناسب برای همین حالا',
            'home_cta_label' => 'چی بازی کنیم؟', 'brand_promise' => 'کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد',
            'home_alignment' => 'center', 'about_title' => 'راه کوتاه‌تر تا بازی کنار کودک',
            'about_lead' => 'تیله با سه انتخاب روشن، کمک می‌کند تا زودتر در کنار کودک‌تان تجربه‌های شیرین بسازید.',
            'about_cta_label' => 'چی بازی کنیم؟',
        ])->assertRedirect();

        $this->get(route('home'))->assertOk()->assertSee('تیله جیگری')->assertSee('درباره تیله')->assertSee('مجله تیله');
    }

    public function test_deploy_version_endpoint_reports_the_current_release(): void
    {
        $this->get('/teelle-asset-index.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee((string) config('teelle.deploy_version'));
    }

    public function test_sessions_older_than_max_age_force_relogin_without_touching_the_account(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user)->get(route('account.show'))->assertOk();
        $sessionId = session()->getId();

        $this->app['session']->put('teelle.session_started_at', now()->getTimestamp() - 8 * 24 * 60);

        $this->app['auth']->guard()->forgetUser();
        $this->get(route('account.show'))->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'active', 'email' => $user->email]);

        $this->app['session']->put('teelle.session_started_at', now()->getTimestamp());
        $this->actingAs($user)->get(route('account.show'))->assertOk();
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }
}
