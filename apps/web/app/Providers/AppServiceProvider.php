<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('match', function (Request $request): Limit {
            $state = $request->session()->get('teelle.quick_match');
            $submissionKey = is_array($state) ? ($state['submission_key'] ?? null) : null;
            $identity = $request->user()?->getAuthIdentifier() ?? (is_string($submissionKey) ? $submissionKey : $request->ip());
            $actorKey = hash('sha256', (string) $identity);

            return Limit::perMinute(30)->by($actorKey);
        });
        RateLimiter::for('play', function (Request $request): Limit {
            $identity = $request->user()?->getAuthIdentifier()
                ?? $request->session()->get('teelle.guest_token')
                ?? $request->ip();

            return Limit::perMinute(60)->by(hash('sha256', (string) $identity));
        });

        foreach (['content.edit', 'content.review', 'content.publish', 'coverage.view'] as $ability) {
            Gate::define($ability, fn (User $user): bool => $user->hasPermission($ability));
        }
    }
}
