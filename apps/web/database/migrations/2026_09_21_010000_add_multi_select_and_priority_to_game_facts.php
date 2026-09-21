<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DEC-059: فقط-افزایشی. هیچ ستون یا ردیف موجود حذف یا بازنویسی نمی‌شود.
        Schema::table('game_facts', function (Blueprint $table): void {
            $table->json('alternatives')->nullable()->after('setup_complexity');
            $table->string('content_priority', 20)->default('normal')->after('cultural_origin');
            $table->string('priority_reason', 500)->nullable()->after('content_priority');
        });

        Schema::table('content_import_batches', function (Blueprint $table): void {
            $table->foreignId('media_asset_id')->nullable()->after('payload_json')
                ->constrained('media_assets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('content_import_batches', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('media_asset_id');
        });
        Schema::table('game_facts', function (Blueprint $table): void {
            $table->dropColumn(['alternatives', 'content_priority', 'priority_reason']);
        });
    }
};
