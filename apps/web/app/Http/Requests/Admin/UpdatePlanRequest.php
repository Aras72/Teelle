<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('subscription.manage') === true;
    }

    protected function prepareForValidation(): void
    {
        $digits = ['۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'];
        $price = strtr((string) $this->input('price_toman'), $digits);

        $this->merge([
            'title' => trim((string) $this->input('title')),
            'price_toman' => str_replace([',', '٬', '،', ' '], '', $price),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'price_toman' => ['required', 'integer', 'min:1000', 'max:999999999'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
