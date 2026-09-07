<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_version_id')->constrained()->restrictOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->restrictOnDelete();
            $table->enum('decision', ['approved', 'changes_requested', 'invalidated']);
            $table->char('scope_hash', 64);
            $table->text('notes')->nullable();
            $table->string('invalidation_reason')->nullable();
            $table->timestamp('reviewed_at')->index();
            $table->timestamps();
            $table->index(['game_version_id', 'decision', 'reviewed_at']);
        });

        Schema::create('game_publications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_id')->constrained()->restrictOnDelete();
            $table->foreignId('game_version_id')->constrained()->restrictOnDelete();
            $table->foreignId('published_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('unpublished_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('published_at')->index();
            $table->timestamp('unpublished_at')->nullable()->index();
            $table->string('unpublish_reason')->nullable();
            $table->timestamps();
            $table->unique(['game_id', 'game_version_id', 'published_at'], 'game_publication_history_unique');
            $table->index(['game_id', 'unpublished_at']);
            $table->foreign(['game_id', 'game_version_id'], 'game_publications_version_ownership_fk')
                ->references(['game_id', 'id'])
                ->on('game_versions')
                ->restrictOnDelete();
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->string('actor_type', 32);
            $table->string('action', 96)->index();
            $table->string('target_type', 96);
            $table->string('target_id', 64);
            $table->json('before_json')->nullable();
            $table->json('after_json')->nullable();
            $table->ulid('request_id')->nullable()->index();
            $table->string('reason')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
            $table->index(['target_type', 'target_id', 'occurred_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::unprepared("CREATE TRIGGER audit_logs_block_update BEFORE UPDATE ON audit_logs FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_logs are append-only'");
            DB::unprepared("CREATE TRIGGER audit_logs_block_delete BEFORE DELETE ON audit_logs FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_logs are append-only'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS audit_logs_block_update');
            DB::unprepared('DROP TRIGGER IF EXISTS audit_logs_block_delete');
        }

        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('game_publications');
        Schema::dropIfExists('content_reviews');
    }
};
