<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('article_categories')->nullOnDelete();
            $table->string('slug', 120)->unique();
            $table->string('title', 180);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('slug', 160)->unique();
            $table->string('title', 220);
            $table->text('excerpt');
            $table->longText('body_markdown');
            $table->string('seo_title', 220)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->enum('status', ['draft', 'in_review', 'published'])->default('draft')->index();
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('cover_disk', 32)->nullable();
            $table->string('cover_path', 512)->nullable();
            $table->string('cover_mime', 128)->nullable();
            $table->text('cover_alt')->nullable();
            $table->timestamps();
        });

        Schema::create('article_category', function (Blueprint $table): void {
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('article_category_id')->constrained()->restrictOnDelete();
            $table->primary(['article_id', 'article_category_id']);
        });

        foreach ([
            ['slug' => 'bedtime-stories', 'title' => 'قصه‌های شب', 'description' => 'قصه‌ها و روایت‌های مناسب پایان روز', 'sort_order' => 10],
            ['slug' => 'play-guides', 'title' => 'راهنمای بازی', 'description' => 'راهنماهای ساده برای بازی بهتر کنار کودک', 'sort_order' => 20],
            ['slug' => 'together-time', 'title' => 'ایده‌های باهم‌بودن', 'description' => 'ایده‌های کاربردی برای ساختن لحظه‌های مشترک', 'sort_order' => 30],
        ] as $category) {
            DB::table('article_categories')->insert($category + [
                'public_id' => (string) Str::ulid(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ([
            'articles.edit' => 'ویرایش مجله و دسته‌بندی‌ها',
            'articles.publish' => 'تأیید و انتشار مجله',
        ] as $code => $title) {
            DB::table('permissions')->updateOrInsert(['code' => $code], ['title' => $title]);
        }

        $managerId = DB::table('roles')->where('code', 'admin')->value('id');
        foreach (['articles.edit', 'articles.publish'] as $code) {
            $permissionId = DB::table('permissions')->where('code', $code)->value('id');
            if ($managerId && $permissionId) {
                DB::table('permission_role')->insertOrIgnore(['role_id' => $managerId, 'permission_id' => $permissionId]);
            }
        }

        $articleEditId = DB::table('permissions')->where('code', 'articles.edit')->value('id');
        $supportRoleId = DB::table('roles')->where('code', 'support_admin')->value('id');
        if ($articleEditId && $supportRoleId && Schema::hasTable('permission_user')) {
            foreach (DB::table('role_user')->where('role_id', $supportRoleId)->pluck('user_id') as $userId) {
                DB::table('permission_user')->insertOrIgnore([
                    'permission_id' => $articleEditId,
                    'user_id' => $userId,
                    'granted_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
        $ids = DB::table('permissions')->whereIn('code', ['articles.edit', 'articles.publish'])->pluck('id');
        DB::table('permission_user')->whereIn('permission_id', $ids)->delete();
        DB::table('permission_role')->whereIn('permission_id', $ids)->delete();
        DB::table('permissions')->whereIn('id', $ids)->delete();
    }
};
