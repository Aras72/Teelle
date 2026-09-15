<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Auth\GuestAccountContinuity;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\NotCommonPassword;
use DomainException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request, GuestAccountContinuity $continuity): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols(), new NotCommonPassword],
            'privacy_accepted' => ['accepted'],
        ]);

        unset($data['privacy_accepted']);
        $data['email'] = Str::lower(trim($data['email']));
        $data['privacy_accepted_at'] = now();
        $data['privacy_policy_version'] = '2026-09-15';
        $user = DB::transaction(function () use ($data): User {
            $user = User::query()->create($data);
            $householdId = DB::table('households')->insertGetId([
                'public_id' => (string) Str::ulid(), 'owner_user_id' => $user->id,
                'title' => null, 'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('household_members')->insert([
                'household_id' => $householdId, 'user_id' => $user->id, 'role' => 'owner',
                'created_at' => now(), 'updated_at' => now(),
            ]);

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();
        try {
            $continuity->merge($user, $request->session());
        } catch (DomainException) {
            Auth::guard('web')->logout();
            $request->session()->regenerate();

            throw ValidationException::withMessages(['email' => 'ساخت حساب انجام شد؛ برای ادامه دوباره وارد شوید']);
        }

        return redirect()->route('verification.notice')->with('status', 'حساب شما ساخته شد؛ ایمیل تأیید را بررسی کنید');
    }
}
