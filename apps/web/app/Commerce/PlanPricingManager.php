<?php

declare(strict_types=1);

namespace App\Commerce;

use App\Content\AuditWriter;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class PlanPricingManager
{
    public function __construct(private readonly AuditWriter $audit) {}

    /** @param array{title: string, price_toman: int|string, is_active: bool} $data */
    public function update(User $actor, Plan $plan, array $data): Plan
    {
        return DB::transaction(function () use ($actor, $plan, $data): Plan {
            $before = $this->snapshot($plan);
            $plan->update([
                'title' => $data['title'],
                'price_minor' => (int) $data['price_toman'] * 10,
                'currency' => 'IRR',
                'is_active' => $data['is_active'],
            ]);
            $plan->refresh();
            $this->audit->write($actor, 'commerce.plan.updated', $plan, $before, $this->snapshot($plan));

            return $plan;
        });
    }

    /** @return array{code: string, title: string, duration_months: int, price_minor: int|null, currency: string, is_active: bool} */
    private function snapshot(Plan $plan): array
    {
        return [
            'code' => $plan->code,
            'title' => $plan->title,
            'duration_months' => (int) $plan->duration_months,
            'price_minor' => $plan->price_minor === null ? null : (int) $plan->price_minor,
            'currency' => $plan->currency,
            'is_active' => (bool) $plan->is_active,
        ];
    }
}
