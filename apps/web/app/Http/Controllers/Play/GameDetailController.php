<?php

declare(strict_types=1);

namespace App\Http\Controllers\Play;

use App\Http\Controllers\Controller;
use App\Models\MatchResult;
use App\Models\MatchSession;
use App\Play\PublicMatchAccess;
use App\Play\PublishedResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class GameDetailController extends Controller
{
    public function __invoke(Request $request, MatchSession $match, int $rank, PublicMatchAccess $access, PublishedResult $published): View
    {
        $access->assertMatch($request, $match);
        $result = $match->results()->where('rank', $rank)->with(['game', 'gameVersion.facts'])->firstOrFail();
        $available = $published->hasCompleteSet($match) && $published->isAvailable($result);

        return view('games.show', [
            'match' => $match,
            'result' => $result,
            'available' => $available,
            'requiredMaterials' => $this->materials($result, 'required'),
            'optionalMaterials' => $this->materials($result, 'optional'),
            'locations' => $this->locations($result),
            'safetyRules' => $this->safetyRules($result),
        ]);
    }

    private function materials(MatchResult $result, string $requirement): array
    {
        return DB::table('game_materials')->join('materials', 'materials.id', '=', 'game_materials.material_id')
            ->where('game_version_id', $result->game_version_id)->where('requirement', $requirement)
            ->pluck('materials.title')->all();
    }

    private function locations(MatchResult $result): array
    {
        return DB::table('game_locations')->join('locations', 'locations.id', '=', 'game_locations.location_id')
            ->where('game_version_id', $result->game_version_id)->pluck('locations.title')->all();
    }

    private function safetyRules(MatchResult $result): array
    {
        return DB::table('game_safety_rules')->join('safety_rules', 'safety_rules.id', '=', 'game_safety_rules.safety_rule_id')
            ->where('game_version_id', $result->game_version_id)->where('safety_rules.is_active', true)
            ->pluck('safety_rules.copy')->all();
    }
}
