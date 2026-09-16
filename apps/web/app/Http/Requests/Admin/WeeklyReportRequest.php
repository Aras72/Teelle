<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;

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
            : $to->startOfMonth();

        return [$from, $to];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! $this->filled('from') || ! $this->filled('to')) {
                return;
            }

            $from = (string) $this->input('from');
            $to = (string) $this->input('to');
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) === 1 && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) === 1 && $from > $to) {
                $validator->errors()->add('from', 'تاریخ شروع باید پیش از تاریخ پایان باشد');
            }
        });
    }
}
