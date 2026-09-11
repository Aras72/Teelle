<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jigari;

use App\Http\Controllers\Controller;
use App\Http\Requests\JigariGameSearchRequest;
use App\Jigari\JigariCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class JigariSearchController extends Controller
{
    public function __invoke(JigariGameSearchRequest $request, JigariCatalog $catalog): View
    {
        $filters = $request->validated();

        return view('jigari.search', [
            'games' => $catalog->search($filters),
            'filters' => $filters,
            'ageBands' => DB::table('age_bands')->orderBy('minimum_age_months')->get(['code', 'title']),
            'situations' => DB::table('situations')->where('is_active', true)->orderBy('id')->get(['slug', 'title']),
            'locations' => DB::table('locations')->where('is_active', true)->orderBy('id')->get(['slug', 'title']),
            'materials' => DB::table('materials')->where('is_active', true)->orderBy('id')->get(['slug', 'title']),
            'players' => DB::table('player_requirements')->where('is_active', true)->orderBy('id')->get(['slug', 'title']),
        ]);
    }
}
