<?php

declare(strict_types=1);

namespace App\Http\Controllers\Play;

use App\Http\Controllers\Controller;
use App\Models\MatchSession;
use App\Play\PublicMatchAccess;
use App\Play\PublishedResult;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MatchResultsController extends Controller
{
    public function __invoke(Request $request, MatchSession $match, PublicMatchAccess $access, PublishedResult $published): View
    {
        $access->assertMatch($request, $match);
        $results = $match->orderedResults()->with(['game', 'gameVersion.facts'])->get();
        $validResults = $published->hasCompleteSet($match);

        return view('results.show', compact('match', 'results', 'validResults'));
    }
}
