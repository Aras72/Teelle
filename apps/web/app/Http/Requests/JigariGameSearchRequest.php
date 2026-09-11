<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class JigariGameSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $query = str_replace(['ي', 'ك'], ['ی', 'ک'], (string) $this->input('q', ''));
        $query = preg_replace('/\s+/u', ' ', trim($query)) ?? '';

        $this->merge(['q' => $query]);
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:80'],
            'age_band' => ['nullable', 'string', Rule::exists('age_bands', 'code')],
            'situation' => ['nullable', 'string', Rule::exists('situations', 'slug')->where('is_active', true)],
            'duration' => ['nullable', 'integer', Rule::in([5, 10, 15, 20, 30, 45, 60])],
            'location' => ['nullable', 'string', Rule::exists('locations', 'slug')->where('is_active', true)],
            'materials' => ['nullable', 'array', 'max:4'],
            'materials.*' => ['string', 'distinct', Rule::exists('materials', 'slug')->where('is_active', true)],
            'players' => ['nullable', 'string', Rule::exists('player_requirements', 'slug')->where('is_active', true)],
        ];
    }

    public function messages(): array
    {
        return [
            'q.max' => 'عبارت جست‌وجو باید کوتاه‌تر از ۸۰ نویسه باشد',
            '*.exists' => 'یکی از فیلترهای انتخاب‌شده معتبر یا فعال نیست',
            'duration.in' => 'زمان انتخاب‌شده معتبر نیست',
            'materials.max' => 'حداکثر چهار وسیله را انتخاب کنید',
        ];
    }
}
