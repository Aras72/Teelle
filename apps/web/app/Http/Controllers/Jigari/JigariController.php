<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jigari;

use App\Http\Controllers\Controller;
use App\Jigari\JigariAccess;
use App\Models\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class JigariController extends Controller
{
    public function __invoke(Request $request, JigariAccess $access): View
    {
        try {
            $plans = Plan::query()
                ->whereIn('duration_months', [3, 6, 12])
                ->where('is_active', true)
                ->whereNotNull('price_minor')
                ->orderBy('duration_months')
                ->get();
            $active = $request->user() ? $access->activeFor($request->user()) : false;
        } catch (QueryException) {
            $plans = collect();
            $active = false;
        }

        return view('jigari.show', ['plans' => $plans, 'active' => $active]);
    }
}
