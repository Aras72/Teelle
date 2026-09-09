<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_sessions', function (Blueprint $table): void {
            $table->string('submission_key', 96)->nullable()->unique()->after('public_id');
        });
    }

    public function down(): void
    {
        Schema::table('match_sessions', function (Blueprint $table): void {
            $table->dropUnique(['submission_key']);
            $table->dropColumn('submission_key');
        });
    }
};
