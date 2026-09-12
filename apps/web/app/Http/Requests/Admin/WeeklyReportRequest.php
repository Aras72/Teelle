<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

final class WeeklyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('analytics.view') === true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    /** @return array{0: CarbonImmutable, 1: CarbonImmutable} */
    public function period(): array
    {
        $validated = $this->validated();
        $to = isset($validated['to'])
            ? CarbonImmutable::createFromFormat('Y-m-d', $validated['to'])->endOfDay()
            : now()->toImmutable()->endOfDay();
        $from = isset($validated['from'])
            ? CarbonImmutable::createFromFormat('Y-m-d', $validated['from'])->startOfDay()
            : $to->subDays(6)->startOfDay();

        if ($from->isAfter($to) || $from->diffInDays($to) > 31) {
            throw ValidationException::withMessages([
                'from' => 'بازه گزارش باید مرتب و حداکثر ۳۱ روزه باشد',
            ]);
        }

        return [$from, $to];
    }
}
