<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('editorial_collections', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('slug', 120)->unique();
            $table->string('title', 120);
            $table->text('summary');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->index();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('editorial_collection_game', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('editorial_collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('position');
            $table->unique(['editorial_collection_id', 'game_id'], 'collection_game_unique');
            $table->unique(['editorial_collection_id', 'position'], 'collection_position_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('editorial_collection_game');
        Schema::dropIfExists('editorial_collections');
    }
};
