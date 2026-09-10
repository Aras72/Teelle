<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\NotCommonPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class PasswordResetController extends Controller
{
    public function request(): View
    {
        return view('auth.forgot-password');
    }

    public function email(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'string', 'email:rfc', 'max:255']]);
        PasswordBroker::sendResetLink(['email' => Str::lower(trim($data['email']))]);

        return back()->with('status', 'اگر حسابی با این ایمیل وجود داشته باشد، پیوند بازیابی ارسال می‌شود');
    }

    public function resetForm(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->string('email')->toString()]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols(), new NotCommonPassword],
        ]);
        $data['email'] = Str::lower(trim($data['email']));

        $status = PasswordBroker::reset($data, function (User $user, string $password): void {
            DB::table('sessions')->where('user_id', $user->id)->delete();
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        if ($status !== PasswordBroker::PasswordReset) {
            throw ValidationException::withMessages(['email' => 'پیوند بازیابی معتبر نیست یا منقضی شده است']);
        }

        return redirect()->route('login')->with('status', 'رمز عبور تغییر کرد؛ حالا وارد شوید');
    }
}
