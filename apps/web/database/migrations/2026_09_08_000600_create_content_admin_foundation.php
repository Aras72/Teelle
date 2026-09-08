<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_versions', function (Blueprint $table): void {
            $table->foreignId('created_by')->nullable()->after('game_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('submitted_by')->nullable()->after('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('submitted_at')->nullable()->after('approved_at');
        });

        Schema::table('media_assets', function (Blueprint $table): void {
            $table->foreignId('uploaded_by')->nullable()->after('public_id')->constrained('users')->restrictOnDelete();
            $table->string('original_name')->nullable()->after('path');
        });

        Schema::create('content_import_batches', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('actor_user_id')->constrained('users')->restrictOnDelete();
            $table->enum('status', ['previewed', 'confirmed', 'rolled_back', 'failed'])->default('previewed')->index();
            $table->json('payload_json');
            $table->json('manifest_json')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rolled_back_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_import_batches');
        Schema::table('media_assets', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('uploaded_by');
            $table->dropColumn('original_name');
        });
        Schema::table('game_versions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('submitted_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn('submitted_at');
        });
    }
};
