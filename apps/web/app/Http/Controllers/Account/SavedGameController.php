<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Account\PublishedSavedGame;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\PlaySession;
use App\Models\SavedGame;
use App\Play\PublicMatchAccess;
use App\Play\PublishedResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SavedGameController extends Controller
{
    public function store(Request $request, PlaySession $play, PublicMatchAccess $access, PublishedResult $published): RedirectResponse
    {
        $access->assertPlay($request, $play);
        $play->load('result.matchSession', 'result.game');
        abort_unless($published->hasCompleteSet($play->result->matchSession) && $published->isAvailable($play->result), 404);

        SavedGame::query()->firstOrCreate(['user_id' => $request->user()->id, 'game_id' => $play->result->game_id]);

        return back()->with('status', 'این بازی برای بعد ذخیره شد');
    }

    public function destroy(Request $request, Game $game): RedirectResponse
    {
        SavedGame::query()->where('user_id', $request->user()->id)->where('game_id', $game->id)->delete();

        return back()->with('status', 'بازی از ذخیره‌ها برداشته شد');
    }

    public function cover(Request $request, Game $game, PublishedSavedGame $published): StreamedResponse
    {
        abort_unless(SavedGame::query()->where('user_id', $request->user()->id)->where('game_id', $game->id)->exists(), 404);
        $asset = $published->cover($game);
        abort_unless($asset, 404);

        return Storage::disk($asset->disk)->response($asset->path, null, [
            'Content-Type' => $asset->mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=300',
        ]);
    }
}
