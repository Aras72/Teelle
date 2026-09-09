<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_facts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('game_version_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('duration_min_minutes');
            $table->unsignedSmallInteger('duration_max_minutes');
            $table->unsignedSmallInteger('prep_time_minutes')->default(0);
            $table->enum('space_required', ['lap', 'small', 'room', 'large', 'outdoor']);
            $table->enum('noise_level', ['quiet', 'moderate', 'loud']);
            $table->enum('mess_level', ['none', 'light', 'messy']);
            $table->unsignedTinyInteger('minimum_children')->default(1);
            $table->unsignedTinyInteger('maximum_children')->default(1);
            $table->unsignedTinyInteger('minimum_adults')->default(1);
            $table->boolean('required_adult')->default(true);
            $table->enum('child_energy', ['low', 'medium', 'high']);
            $table->enum('caregiver_energy', ['low', 'medium', 'high']);
            $table->enum('interaction_type', ['side_by_side', 'cooperative', 'competitive', 'pretend', 'conversation']);
            $table->enum('caregiver_involvement', ['active', 'shared', 'light']);
            $table->enum('setup_complexity', ['none', 'simple', 'moderate']);
            $table->string('source_title');
            $table->string('source_url', 2048);
            $table->string('cultural_origin', 120);
            $table->timestamps();
            $table->index(['duration_min_minutes', 'duration_max_minutes']);
            $table->index(['space_required', 'noise_level', 'mess_level']);
        });

        Schema::create('coverage_matrix_cells', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('age_band_id')->constrained()->restrictOnDelete();
            $table->foreignId('situation_id')->constrained()->restrictOnDelete();
            $table->boolean('is_critical')->default(true)->index();
            $table->unsignedTinyInteger('minimum_survivors')->default(3);
            $table->timestamps();
            $table->unique(['age_band_id', 'situation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coverage_matrix_cells');
        Schema::dropIfExists('game_facts');
    }
};
