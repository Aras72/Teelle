<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Jigari\JigariAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureJigariActive
{
    public function __construct(private readonly JigariAccess $access) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $this->access->activeFor($user)) {
            abort(403);
        }

        return $next($request);
    }
}
