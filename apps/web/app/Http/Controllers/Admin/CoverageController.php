<?php

namespace App\Http\Controllers\Admin;

use App\Content\CoverageMatrix;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoverageController extends Controller
{
    public function __invoke(Request $request, CoverageMatrix $matrix): View
    {
        abort_unless($request->user()->hasPermission('coverage.view'), 403);
        $cells = $matrix->report();

        return view('admin.content.coverage', [
            'cells' => $cells,
            'criticalGaps' => $cells->filter(fn (object $cell): bool => $cell->is_critical && $cell->survivors < $cell->minimum_survivors)->count(),
            'generatedAt' => now(),
        ]);
    }
}
