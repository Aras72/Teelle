<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            DB::table('energy_levels')->update(['is_active' => false, 'updated_at' => now()]);

            foreach (['low' => 'کم', 'medium' => 'متوسط', 'high' => 'زیاد'] as $slug => $title) {
                DB::table('energy_levels')->updateOrInsert(
                    ['slug' => $slug],
                    ['title' => $title, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()],
                );
            }
        });
    }

    public function down(): void
    {
        DB::table('energy_levels')
            ->whereIn('slug', ['low', 'medium', 'high'])
            ->update(['is_active' => false, 'updated_at' => now()]);
    }
};
