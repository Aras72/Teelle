<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('teelle:prune-daily-free-play', function (): void {
    $deleted = DB::table('daily_free_play_claims')
        ->where('usage_date', '<', now()->toDateString())
        ->delete();

    $this->info("{$deleted} claimهای منقضی پاک شد.");
})->purpose('پاک‌کردن Claimهای Start رایگان پیش از روز جاری تهران');

Schedule::command('teelle:prune-daily-free-play')
    ->name('daily-free-play-claims:prune')
    ->dailyAt('00:15')
    ->timezone('Asia/Tehran')
    ->withoutOverlapping();
