<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')->updateOrInsert(['code' => 'admin'], ['title' => 'مدیر']);
        DB::table('roles')->updateOrInsert(['code' => 'support_admin'], ['title' => 'ادمین']);

        $permissions = [
            'users.view' => 'مشاهده کاربران و عضویت‌ها',
            'users.edit' => 'ویرایش مشخصات عمومی کاربران',
        ];

        foreach ($permissions as $code => $title) {
            DB::table('permissions')->updateOrInsert(['code' => $code], ['title' => $title]);
        }

        $supportAdminId = DB::table('roles')->where('code', 'support_admin')->value('id');
        $managerId = DB::table('roles')->where('code', 'admin')->value('id');

        foreach (array_keys($permissions) as $permissionCode) {
            $permissionId = DB::table('permissions')->where('code', $permissionCode)->value('id');
            DB::table('permission_role')->insertOrIgnore(['permission_id' => $permissionId, 'role_id' => $supportAdminId]);
            DB::table('permission_role')->insertOrIgnore(['permission_id' => $permissionId, 'role_id' => $managerId]);
        }
    }

    public function down(): void
    {
        $supportAdminId = DB::table('roles')->where('code', 'support_admin')->value('id');
        if ($supportAdminId) {
            DB::table('permission_role')->where('role_id', $supportAdminId)->delete();
            DB::table('role_user')->where('role_id', $supportAdminId)->delete();
            DB::table('roles')->where('id', $supportAdminId)->delete();
        }

        $permissionIds = DB::table('permissions')->whereIn('code', ['users.view', 'users.edit'])->pluck('id');
        DB::table('permission_role')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();
    }
};
