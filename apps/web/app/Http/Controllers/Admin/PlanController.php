<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Commerce\PlanPricingManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PlanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can('subscription.manage'), 403);

        return view('admin.plans.index', [
            'plans' => Plan::query()->whereIn('duration_months', [3, 6, 12])->orderBy('duration_months')->get(),
        ]);
    }

    public function update(UpdatePlanRequest $request, Plan $plan, PlanPricingManager $pricing): RedirectResponse
    {
        abort_unless(in_array((int) $plan->duration_months, [3, 6, 12], true), 404);
        $pricing->update($request->user(), $plan, $request->validated());

        return back()->with('status', 'قیمت و وضعیت پلن ذخیره شد');
    }
}
