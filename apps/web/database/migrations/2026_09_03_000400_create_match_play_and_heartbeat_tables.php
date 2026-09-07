<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_sessions', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('guest_identity_id')->nullable()->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('age_months')->nullable();
            $table->json('context_json');
            $table->string('ruleset_version', 32);
            $table->enum('outcome', ['collecting', 'evaluated', 'matched', 'no_result', 'expired'])->default('collecting')->index();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->index(['guest_identity_id', 'created_at']);
        });

        Schema::create('match_session_children', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('match_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('child_profile_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('age_months');
            $table->unique(['match_session_id', 'child_profile_id'], 'match_children_session_profile_unique');
        });

        Schema::create('match_results', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('match_session_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rank');
            $table->foreignId('game_id')->constrained()->restrictOnDelete();
            $table->foreignId('game_version_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('score');
            $table->json('explanation_json');
            $table->timestamps();
            $table->unique(['match_session_id', 'rank']);
            $table->unique(['match_session_id', 'game_id']);
            $table->foreign(['game_id', 'game_version_id'], 'match_results_version_ownership_fk')
                ->references(['game_id', 'id'])
                ->on('game_versions')
                ->restrictOnDelete();
        });

        Schema::create('play_sessions', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('match_result_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('guest_identity_id')->nullable()->constrained()->restrictOnDelete();
            $table->enum('state', ['matched', 'started', 'completed', 'abandoned'])->default('matched')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('abandoned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('play_events', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('play_session_id')->constrained()->restrictOnDelete();
            $table->enum('event_type', ['started', 'completed', 'rated']);
            $table->string('idempotency_key', 96)->unique();
            $table->json('payload_json')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamp('recorded_at')->useCurrent();
            $table->unique(['play_session_id', 'event_type']);
            $table->index(['event_type', 'recorded_at']);
        });

        Schema::create('heartbeat_projections', function (Blueprint $table): void {
            $table->string('key', 64)->primary();
            $table->unsignedBigInteger('started_count')->default(0);
            $table->timestamp('last_event_recorded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('coverage_observations', function (Blueprint $table): void {
            $table->id();
            $table->char('context_bucket', 64);
            $table->string('reason_code', 64);
            $table->date('observed_on');
            $table->unsignedBigInteger('count')->default(0);
            $table->timestamps();
            $table->unique(['context_bucket', 'reason_code', 'observed_on'], 'coverage_observation_bucket_unique');
        });

        Schema::create('saved_games', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->restrictOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'game_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE match_sessions ADD CONSTRAINT match_sessions_exactly_one_actor CHECK ((user_id IS NULL) <> (guest_identity_id IS NULL))');
            DB::statement('ALTER TABLE play_sessions ADD CONSTRAINT play_sessions_exactly_one_actor CHECK ((user_id IS NULL) <> (guest_identity_id IS NULL))');
            DB::statement('ALTER TABLE match_sessions ADD CONSTRAINT match_sessions_supported_age CHECK (age_months IS NULL OR (age_months >= 6 AND age_months < 156))');
            DB::statement('ALTER TABLE match_session_children ADD CONSTRAINT match_children_supported_age CHECK (age_months >= 6 AND age_months < 156)');
            DB::unprepared("CREATE TRIGGER play_events_block_update BEFORE UPDATE ON play_events FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'play_events are append-only'");
            DB::unprepared("CREATE TRIGGER play_events_block_delete BEFORE DELETE ON play_events FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'play_events are append-only'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS play_events_block_update');
            DB::unprepared('DROP TRIGGER IF EXISTS play_events_block_delete');
        }

        Schema::dropIfExists('saved_games');
        Schema::dropIfExists('coverage_observations');
        Schema::dropIfExists('heartbeat_projections');
        Schema::dropIfExists('play_events');
        Schema::dropIfExists('play_sessions');
        Schema::dropIfExists('match_results');
        Schema::dropIfExists('match_session_children');
        Schema::dropIfExists('match_sessions');
    }
};
