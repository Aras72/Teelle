<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('privacy_requests', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->enum('request_type', ['export', 'deletion']);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->index();
            $table->string('active_key', 16)->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('scheduled_for')->nullable()->index();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'request_type', 'active_key'], 'privacy_one_active_request');
            $table->index(['user_id', 'requested_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('privacy_requests');
    }
};
