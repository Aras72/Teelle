<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_content_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::table('permissions')->insertOrIgnore([
            'code' => 'site.manage',
            'title' => 'مدیریت متن‌ها و چیدمان عمومی',
        ]);

        $roleId = DB::table('roles')->where('code', 'admin')->value('id');
        $permissionId = DB::table('permissions')->where('code', 'site.manage')->value('id');
        if ($roleId && $permissionId) {
            DB::table('permission_role')->insertOrIgnore(['role_id' => $roleId, 'permission_id' => $permissionId]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_content_settings');
        $permissionId = DB::table('permissions')->where('code', 'site.manage')->value('id');
        if ($permissionId) {
            DB::table('permission_role')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }
    }
};
