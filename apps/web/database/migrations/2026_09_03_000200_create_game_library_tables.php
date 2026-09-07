<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'in_review', 'approved', 'published', 'unpublished', 'archived'])->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('game_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('version_no');
            $table->enum('status', ['draft', 'in_review', 'approved'])->default('draft')->index();
            $table->string('title');
            $table->text('summary');
            $table->json('instructions');
            $table->text('safety_copy');
            $table->json('contraindications')->nullable();
            $table->enum('supervision_level', ['within_reach', 'same_room', 'check_in']);
            $table->char('content_hash', 64);
            $table->unsignedTinyInteger('review_quality')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->unique(['game_id', 'version_no']);
            $table->unique(['game_id', 'content_hash']);
            $table->unique(['game_id', 'id'], 'game_versions_game_id_id_unique');
        });

        Schema::table('games', function (Blueprint $table): void {
            $table->foreignId('current_published_version_id')
                ->nullable()
                ->after('status')
                ->constrained('game_versions')
                ->restrictOnDelete();
            $table->foreign(['id', 'current_published_version_id'], 'games_current_version_ownership_fk')
                ->references(['game_id', 'id'])
                ->on('game_versions')
                ->restrictOnDelete();
        });

        Schema::create('age_bands', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('title');
            $table->unsignedSmallInteger('minimum_age_months');
            $table->unsignedSmallInteger('maximum_age_months_exclusive');
            $table->timestamps();
        });

        foreach (['situations', 'locations', 'energy_levels', 'moods', 'player_requirements', 'tags'] as $taxonomy) {
            Schema::create($taxonomy, function (Blueprint $table): void {
                $table->id();
                $table->string('slug', 64)->unique();
                $table->string('title');
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        Schema::create('game_age_ranges', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('age_band_id')->nullable()->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('minimum_age_months');
            $table->unsignedSmallInteger('maximum_age_months_exclusive');
            $table->unique('game_version_id');
            $table->index(['minimum_age_months', 'maximum_age_months_exclusive'], 'game_age_range_bounds_index');
        });

        $pivots = [
            'game_situations' => ['situations', 'situation_id'],
            'game_locations' => ['locations', 'location_id'],
            'game_energy_levels' => ['energy_levels', 'energy_level_id'],
            'game_moods' => ['moods', 'mood_id'],
            'game_player_requirements' => ['player_requirements', 'player_requirement_id'],
            'game_tags' => ['tags', 'tag_id'],
        ];

        foreach ($pivots as $name => [$relatedTable, $foreignColumn]) {
            Schema::create($name, function (Blueprint $table) use ($relatedTable, $foreignColumn): void {
                $table->id();
                $table->foreignId('game_version_id')->constrained()->cascadeOnDelete();
                $table->foreignId($foreignColumn)->constrained($relatedTable)->restrictOnDelete();
                $table->unsignedTinyInteger('weight')->default(0);
                $table->boolean('is_constraint')->default(false);
                $table->unique(['game_version_id', $foreignColumn], $table->getTable().'_version_taxonomy_unique');
            });
        }

        Schema::create('materials', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('title');
            $table->enum('risk_class', ['none', 'low', 'moderate', 'high'])->default('none');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('game_materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained()->restrictOnDelete();
            $table->enum('requirement', ['required', 'optional', 'substitution']);
            $table->foreignId('substitute_for_material_id')->nullable()->constrained('materials')->restrictOnDelete();
            $table->string('quantity_note')->nullable();
            $table->unique(['game_version_id', 'material_id']);
        });

        Schema::create('safety_rules', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 64)->unique();
            $table->enum('severity', ['info', 'caution', 'warning', 'critical']);
            $table->enum('rule_type', ['flag', 'contraindication', 'supervision']);
            $table->text('copy');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('game_safety_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('safety_rule_id')->constrained()->restrictOnDelete();
            $table->boolean('hard_filter')->default(true);
            $table->unique(['game_version_id', 'safety_rule_id']);
        });

        Schema::create('media_assets', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('disk', 32);
            $table->string('path', 512);
            $table->string('mime', 128);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->char('checksum', 64)->unique();
            $table->enum('status', ['quarantined', 'validated', 'reviewed', 'rejected'])->default('quarantined')->index();
            $table->text('alt_text')->nullable();
            $table->timestamps();
            $table->unique(['disk', 'path']);
        });

        Schema::create('game_media', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_asset_id')->constrained()->restrictOnDelete();
            $table->enum('role', ['cover', 'detail', 'step']);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('crop_data')->nullable();
            $table->unique(['game_version_id', 'role', 'sort_order']);
            $table->unique(['game_version_id', 'media_asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_media');
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('game_safety_rules');
        Schema::dropIfExists('safety_rules');
        Schema::dropIfExists('game_materials');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('game_tags');
        Schema::dropIfExists('game_player_requirements');
        Schema::dropIfExists('game_moods');
        Schema::dropIfExists('game_energy_levels');
        Schema::dropIfExists('game_locations');
        Schema::dropIfExists('game_situations');
        Schema::dropIfExists('game_age_ranges');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('player_requirements');
        Schema::dropIfExists('moods');
        Schema::dropIfExists('energy_levels');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('situations');
        Schema::dropIfExists('age_bands');

        Schema::table('games', function (Blueprint $table): void {
            $table->dropForeign('games_current_version_ownership_fk');
            $table->dropForeign(['current_published_version_id']);
            $table->dropColumn('current_published_version_id');
        });

        Schema::dropIfExists('game_versions');
        Schema::dropIfExists('games');
    }
};
