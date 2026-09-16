<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_user', function (Blueprint $table): void {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('granted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->primary(['permission_id', 'user_id']);
        });

        $supportRoleId = DB::table('roles')->where('code', 'support_admin')->value('id');
        if (! $supportRoleId) {
            return;
        }

        $baseCodes = ['content.edit', 'users.view', 'users.edit', 'users.delete'];
        $permissionIds = DB::table('permissions')->whereIn('code', $baseCodes)->pluck('id');
        $userIds = DB::table('role_user')->where('role_id', $supportRoleId)->pluck('user_id');
        foreach ($userIds as $userId) {
            foreach ($permissionIds as $permissionId) {
                DB::table('permission_user')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'user_id' => $userId,
                    'granted_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('permission_role')->where('role_id', $supportRoleId)->delete();
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_user');
        $supportRoleId = DB::table('roles')->where('code', 'support_admin')->value('id');
        if (! $supportRoleId) {
            return;
        }
        foreach (['users.view', 'users.edit', 'users.delete'] as $code) {
            $permissionId = DB::table('permissions')->where('code', $code)->value('id');
            if ($permissionId) {
                DB::table('permission_role')->insertOrIgnore(['permission_id' => $permissionId, 'role_id' => $supportRoleId]);
            }
        }
    }
};
