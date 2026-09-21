<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * بازه استاندارد عمر نشست: هفت روز.
 *
 * مالک در DEC-058 مقرر کرد نشست کاربران پس از یک بازه استاندارد ریست شود و
 * نیاز به ورود دوباره پیدا کنند؛ اما ثبت‌نام، پروفایل کودک و کل داده‌های
 * حساب دست‌نخورده بمانند. این میان‌افزار نشست‌های قدیمی‌تر از هفت روز را
 * بازتولید می‌کند (Logout مجازی) بدون هیچ تغییری در داده‌های کاربر.
 */
class EnsureFreshSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $maxAge = (int) config('teelle.session_max_age_minutes', 10080);

        if ($maxAge > 0 && $request->hasSession()) {
            $lastActivity = (int) $request->session()->get('teelle.session_started_at', 0);

            if ($lastActivity > 0 && (now()->getTimestamp() - $lastActivity) > $maxAge * 60) {
                $request->session()->invalidate();
                $request->session()->migrate();
                $request->session()->regenerateToken();
            }
        }

        return $next($request);
    }
}
