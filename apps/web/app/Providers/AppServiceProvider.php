<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach (['content.edit', 'content.review', 'content.publish', 'coverage.view'] as $ability) {
            Gate::define($ability, fn (User $user): bool => $user->hasPermission($ability));
        }
    }
}
