<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('permissions')->updateOrInsert(['code' => 'users.delete'], ['title' => 'غیرفعال‌سازی حساب کاربر']);
        $permissionId = DB::table('permissions')->where('code', 'users.delete')->value('id');
        foreach (DB::table('roles')->whereIn('code', ['support_admin', 'admin'])->pluck('id') as $roleId) {
            DB::table('permission_role')->insertOrIgnore(['permission_id' => $permissionId, 'role_id' => $roleId]);
        }
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('code', 'users.delete')->value('id');
        if ($permissionId) {
            DB::table('permission_role')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }
    }
};
