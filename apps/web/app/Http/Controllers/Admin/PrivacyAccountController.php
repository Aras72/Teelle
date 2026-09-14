<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyRequest;
use App\Privacy\PrivacyRequestManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PrivacyAccountController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasPermission('users.manage'), 403);

        $deletions = PrivacyRequest::query()
            ->with('user')
            ->where('request_type', 'deletion')
            ->latest('requested_at')
            ->paginate(30);

        return view('admin.accounts.index', compact('deletions'));
    }

    public function reactivate(Request $request, PrivacyRequest $privacyRequest, PrivacyRequestManager $manager): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('users.manage'), 403);
        $reactivated = $manager->reactivate($request->user(), $privacyRequest);

        return back()->with('status', $reactivated
            ? 'حساب بازگردانی شد و کاربر دوباره می‌تواند وارد شود'
            : 'این درخواست دیگر در مهلت بازگردانی نیست');
    }
}
