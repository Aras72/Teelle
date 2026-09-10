<?php

declare(strict_types=1);

namespace App\Http\Controllers\Play;

use App\Enums\PlayEventType;
use App\Http\Controllers\Controller;
use App\Models\MatchSession;
use App\Models\PlaySession;
use App\Play\PlayLifecycle;
use App\Play\PublicMatchAccess;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class PlayController extends Controller
{
    public function start(Request $request, MatchSession $match, int $rank, PublicMatchAccess $access, PlayLifecycle $lifecycle): RedirectResponse
    {
        $access->assertMatch($request, $match);
        $result = $match->results()->where('rank', $rank)->firstOrFail();
        try {
            $play = $lifecycle->start($result);
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['play' => $exception->getMessage()]);
        }

        return redirect()->route('plays.show', $play);
    }

    public function show(Request $request, PlaySession $play, PublicMatchAccess $access): View
    {
        $access->assertPlay($request, $play);
        $play->load('result.gameVersion');
        $rating = $play->events()->where('event_type', PlayEventType::Rated)->first()?->payload_json;

        return view('play.show', ['play' => $play, 'rating' => is_array($rating) ? ($rating['rating'] ?? null) : null]);
    }

    public function complete(Request $request, PlaySession $play, PublicMatchAccess $access, PlayLifecycle $lifecycle): RedirectResponse
    {
        $access->assertPlay($request, $play);
        try {
            $lifecycle->complete($play);
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['play' => $exception->getMessage()]);
        }

        return redirect()->route('plays.show', $play)->with('status', 'این بازی به خاطره‌های تیله اضافه شد');
    }

    public function rate(Request $request, PlaySession $play, PublicMatchAccess $access, PlayLifecycle $lifecycle): RedirectResponse
    {
        $access->assertPlay($request, $play);
        $data = $request->validate(['rating' => ['required', 'integer', 'between:1,5']]);
        try {
            $created = $lifecycle->rate($play, (int) $data['rating']);
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['rating' => $exception->getMessage()]);
        }

        return redirect()->route('plays.show', $play)
            ->with('status', $created ? 'ممنون؛ بازخوردتان ثبت شد' : 'بازخورد این بازی قبلاً ثبت شده بود');
    }
}
