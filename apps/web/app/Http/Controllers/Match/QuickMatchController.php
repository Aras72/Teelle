<?php

declare(strict_types=1);

namespace App\Http\Controllers\Match;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuickMatchAnswerRequest;
use App\Match\QuickMatchFlow;
use App\Match\QuickMatchSubmission;
use App\Models\MatchSession;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class QuickMatchController extends Controller
{
    public function show(Request $request, QuickMatchFlow $flow): View
    {
        $state = $flow->state($request->session());

        return view('match.show', [
            'state' => $state,
            'match' => $this->ownedCompletedMatch($request, $state),
            'step' => $state['current'],
            'progress' => $flow->progress($state),
            'totalSteps' => $flow->totalSteps($state),
            'options' => $this->options((string) $state['current']),
        ]);
    }

    public function answer(QuickMatchAnswerRequest $request, QuickMatchFlow $flow, QuickMatchSubmission $submission): RedirectResponse
    {
        try {
            $state = $flow->record($request->session(), (string) $request->validated('step'), $request->normalizedAnswer());
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['answer' => $exception->getMessage()]);
        }

        if ($state['current'] === null) {
            $match = $submission->submit($request->session(), $state, $request->user());
            $flow->markSubmitted($request->session(), $match->public_id);

            return redirect()->route('matches.show', $match);
        }

        return redirect()->route('match.show');
    }

    public function back(Request $request, QuickMatchFlow $flow): RedirectResponse
    {
        $flow->back($request->session());

        return redirect()->route('match.show');
    }

    public function restart(Request $request, QuickMatchFlow $flow): RedirectResponse
    {
        $flow->restart($request->session());

        return redirect()->route('match.show');
    }

    private function ownedCompletedMatch(Request $request, array $state): ?MatchSession
    {
        if (! is_string($state['match_public_id'])) {
            return null;
        }

        $query = MatchSession::query()->where('public_id', $state['match_public_id']);
        if ($request->user()) {
            return $query->where('user_id', $request->user()->id)->first();
        }
        $token = $request->session()->get('teelle.guest_token');
        if (! is_string($token)) {
            return null;
        }

        return $query->whereHas('guestIdentity', fn ($guest) => $guest->where('token_hash', hash('sha256', $token)))->first();
    }

    /** @return array<int, array{value: string, label: string, detail?: string}> */
    private function options(string $step): array
    {
        return match ($step) {
            'situation' => DB::table('situations')->where('is_active', true)->orderBy('id')->get()->map(fn ($item) => ['value' => $item->slug, 'label' => $item->title])->all(),
            'duration' => [['value' => '15', 'label' => 'تا ۱۵ دقیقه'], ['value' => '30', 'label' => '۱۵ تا ۳۰ دقیقه'], ['value' => '45', 'label' => 'بیشتر از ۳۰ دقیقه']],
            'location' => DB::table('locations')->where('is_active', true)->orderBy('id')->get()->map(fn ($item) => ['value' => $item->slug, 'label' => $item->title])->all(),
            'materials' => [['value' => 'none', 'label' => 'بدون وسیله'], ['value' => 'paper-pencil', 'label' => 'کاغذ و مداد'], ['value' => 'ball', 'label' => 'توپ'], ['value' => 'household-items', 'label' => 'وسایل خانه']],
            'players' => DB::table('player_requirements')->where('is_active', true)->orderBy('id')->get()->map(fn ($item) => ['value' => $item->slug, 'label' => $item->title])->all(),
            'caregiver_energy' => DB::table('energy_levels')->where('is_active', true)->orderBy('id')->get()->map(fn ($item) => ['value' => $item->slug, 'label' => $item->title])->all(),
            'mood' => DB::table('moods')->where('is_active', true)->orderBy('id')->get()->map(fn ($item) => ['value' => $item->slug, 'label' => $item->title])->all(),
            default => [],
        };
    }
}
