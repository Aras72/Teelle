<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Account\PublishedSavedGame;
use App\Http\Controllers\Controller;
use App\Jigari\JigariAccess;
use App\Models\Household;
use App\Models\PrivacyRequest;
use App\Models\SavedGame;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AccountController extends Controller
{
    public function show(Request $request, PublishedSavedGame $published, JigariAccess $jigari): View
    {
        $saved = SavedGame::query()->where('user_id', $request->user()->id)
            ->with('game.currentPublishedVersion')->latest()->get()
            ->map(fn (SavedGame $item): array => ['item' => $item, 'available' => $published->isAvailable($item->game)]);
        $history = $request->user()->playSessions()->with('result.gameVersion')->latest('started_at')->get();
        $jigariActive = $jigari->activeFor($request->user());
        $household = Household::query()->where('owner_user_id', $request->user()->id)
            ->withCount(['childProfiles' => fn ($query) => $query->where('status', 'active')])
            ->first();
        $childCount = $household?->child_profiles_count ?? 0;
        $privacyRequests = PrivacyRequest::query()->where('user_id', $request->user()->id)
            ->latest('requested_at')->limit(10)->get();
        $pendingDeletion = $privacyRequests->first(fn (PrivacyRequest $item): bool => $item->request_type === 'deletion' && $item->status === 'pending');

        return view('account.show', compact('saved', 'history', 'jigariActive', 'childCount', 'privacyRequests', 'pendingDeletion'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'timezone' => ['required', 'timezone:all'],
        ]);
        $request->user()->update($data);

        return back()->with('status', 'تنظیمات حساب ذخیره شد');
    }
}
