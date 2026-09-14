<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureContentStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }
        if (! $user->hasAnyPermission(['content.edit', 'content.review', 'content.publish', 'subscription.manage', 'users.manage'])) {
            abort(403);
        }

        return $next($request);
    }
}
