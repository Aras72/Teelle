<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jigari;

use App\Analytics\SearchObservationRecorder;
use App\Http\Controllers\Controller;
use App\Http\Requests\JigariGameSearchRequest;
use App\Jigari\JigariCatalog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class JigariSearchController extends Controller
{
    public function __invoke(JigariGameSearchRequest $request, JigariCatalog $catalog, SearchObservationRecorder $observations): View
    {
        $filters = $request->validated();
        $games = $catalog->search($filters);
        $observations->record($filters, $games->total());
        $taxonomy = $this->filterTaxonomy();

        return view('jigari.search', [
            'games' => $games,
            'filters' => $filters,
            'ageBands' => DB::table('age_bands')->orderBy('minimum_age_months')->get(['code', 'title']),
            'situations' => $taxonomy->get('situation', collect()),
            'locations' => $taxonomy->get('location', collect()),
            'materials' => $taxonomy->get('material', collect()),
            'players' => $taxonomy->get('player', collect()),
        ]);
    }

    private function filterTaxonomy(): Collection
    {
        $query = DB::table('situations')
            ->selectRaw("'situation' as type, slug, title, id as sort_order")
            ->where('is_active', true)
            ->unionAll(DB::table('locations')->selectRaw("'location' as type, slug, title, id as sort_order")->where('is_active', true))
            ->unionAll(DB::table('materials')->selectRaw("'material' as type, slug, title, id as sort_order")->where('is_active', true))
            ->unionAll(DB::table('player_requirements')->selectRaw("'player' as type, slug, title, id as sort_order")->where('is_active', true));

        return DB::query()->fromSub($query, 'filter_taxonomy')
            ->orderBy('type')
            ->orderBy('sort_order')
            ->get(['type', 'slug', 'title'])
            ->groupBy('type');
    }
}
