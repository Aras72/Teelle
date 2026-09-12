<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OnboardingController extends Controller
{
    public function show(Request $request): RedirectResponse|View
    {
        if ($request->user()->onboarding_completed_at !== null) {
            return redirect()->route('account.show');
        }

        return view('auth.onboarding');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['next' => ['required', 'in:match,account']]);

        if ($request->user()->onboarding_completed_at === null) {
            $request->user()->update(['onboarding_completed_at' => now()]);
        }

        return redirect()->route($data['next'] === 'match' ? 'match.show' : 'account.show')
            ->with('status', 'خوش آمدید؛ تیله آماده بازی است');
    }
}
