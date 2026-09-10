<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Account\PublishedSavedGame;
use App\Http\Controllers\Controller;
use App\Models\SavedGame;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AccountController extends Controller
{
    public function show(Request $request, PublishedSavedGame $published): View
    {
        $saved = SavedGame::query()->where('user_id', $request->user()->id)
            ->with('game.currentPublishedVersion')->latest()->get()
            ->map(fn (SavedGame $item): array => ['item' => $item, 'available' => $published->isAvailable($item->game)]);
        $history = $request->user()->playSessions()->with('result.gameVersion')->latest('started_at')->get();

        return view('account.show', ['saved' => $saved, 'history' => $history]);
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
