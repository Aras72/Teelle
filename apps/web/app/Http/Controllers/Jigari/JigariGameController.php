<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jigari;

use App\Http\Controllers\Controller;
use App\Jigari\JigariCatalog;
use App\Models\Game;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class JigariGameController extends Controller
{
    public function __invoke(Game $game, JigariCatalog $catalog): View
    {
        $game = $catalog->findAvailable($game);
        $version = $game->currentPublishedVersion;

        return view('jigari.game', [
            'game' => $game,
            'version' => $version,
            'materials' => DB::table('game_materials')->join('materials', 'materials.id', '=', 'game_materials.material_id')
                ->where('game_version_id', $version->id)->pluck('materials.title')->all(),
            'locations' => DB::table('game_locations')->join('locations', 'locations.id', '=', 'game_locations.location_id')
                ->where('game_version_id', $version->id)->pluck('locations.title')->all(),
            'safetyRules' => DB::table('game_safety_rules')->join('safety_rules', 'safety_rules.id', '=', 'game_safety_rules.safety_rule_id')
                ->where('game_version_id', $version->id)->where('safety_rules.is_active', true)->pluck('safety_rules.copy')->all(),
        ]);
    }
}
