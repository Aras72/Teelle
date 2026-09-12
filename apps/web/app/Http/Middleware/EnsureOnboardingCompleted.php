<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->onboarding_completed_at === null) {
            return redirect()->route('onboarding.show');
        }

        return $next($request);
    }
}
