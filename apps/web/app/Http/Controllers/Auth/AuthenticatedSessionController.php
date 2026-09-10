<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Auth\GuestAccountContinuity;
use App\Http\Controllers\Controller;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request, GuestAccountContinuity $continuity): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'string'],
        ]);
        $credentials['email'] = Str::lower(trim($credentials['email']));
        $credentials['status'] = 'active';

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'اطلاعات ورود درست نیست']);
        }

        $request->session()->regenerate();
        $request->user()->forceFill(['last_login_at' => now()])->save();
        try {
            $continuity->merge($request->user(), $request->session());
        } catch (DomainException) {
            Auth::guard('web')->logout();
            $request->session()->regenerate();

            throw ValidationException::withMessages(['email' => 'ورود انجام نشد؛ دوباره تلاش کنید']);
        }

        return redirect()->intended(route('account.show'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'از حساب خارج شدید');
    }
}
