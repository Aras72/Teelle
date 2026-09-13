<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_reviews', function (Blueprint $table): void {
            $table->json('scope_decisions')->nullable()->after('scope_hash');
        });
    }

    public function down(): void
    {
        Schema::table('content_reviews', function (Blueprint $table): void {
            $table->dropColumn('scope_decisions');
        });
    }
};
