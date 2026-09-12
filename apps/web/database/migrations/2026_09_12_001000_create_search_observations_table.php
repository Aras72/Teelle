<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_observations', function (Blueprint $table): void {
            $table->id();
            $table->date('observed_on')->unique();
            $table->unsignedBigInteger('search_count')->default(0);
            $table->unsignedBigInteger('searches_with_results')->default(0);
            $table->unsignedBigInteger('zero_result_count')->default(0);
            $table->unsignedBigInteger('query_search_count')->default(0);
            $table->unsignedBigInteger('filtered_search_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_observations');
    }
};
