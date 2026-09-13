<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_free_play_claims', function (Blueprint $table): void {
            $table->id();
            $table->date('usage_date');
            $table->char('ip_day_hash', 64);
            $table->foreignId('play_session_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['usage_date', 'ip_day_hash'], 'daily_free_play_ip_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_free_play_claims');
    }
};
