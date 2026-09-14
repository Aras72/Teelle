<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'member' => 'عضو',
            'content_editor' => 'ویرایشگر محتوا',
            'reviewer' => 'بازبین',
            'admin' => 'مدیر',
        ];

        $permissions = [
            'content.edit' => 'ویرایش محتوای پیش‌نویس',
            'content.review' => 'بازبینی محتوا',
            'content.publish' => 'انتشار و توقف انتشار',
            'taxonomy.manage' => 'مدیریت طبقه‌بندی',
            'coverage.view' => 'مشاهده پوشش محتوا',
            'analytics.view' => 'مشاهده آمار تجمیعی',
            'subscription.manage' => 'مدیریت وضعیت اشتراک',
            'roles.manage' => 'مدیریت نقش‌ها',
            'users.manage' => 'مدیریت بازگردانی حساب‌ها',
        ];

        foreach ($roles as $code => $title) {
            DB::table('roles')->updateOrInsert(['code' => $code], ['title' => $title]);
        }

        foreach ($permissions as $code => $title) {
            DB::table('permissions')->updateOrInsert(['code' => $code], ['title' => $title]);
        }

        $grants = [
            'content_editor' => ['content.edit', 'coverage.view'],
            'reviewer' => ['content.edit', 'content.review', 'content.publish', 'coverage.view', 'analytics.view'],
            'admin' => array_keys($permissions),
        ];

        foreach ($grants as $roleCode => $permissionCodes) {
            $roleId = DB::table('roles')->where('code', $roleCode)->value('id');

            foreach ($permissionCodes as $permissionCode) {
                $permissionId = DB::table('permissions')->where('code', $permissionCode)->value('id');
                DB::table('permission_role')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        }
    }
}
