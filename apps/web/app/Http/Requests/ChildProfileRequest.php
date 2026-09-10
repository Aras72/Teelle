<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

final class ChildProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nickname' => ['nullable', 'string', 'max:40'],
            'birth_month' => ['required', 'string', function (string $attribute, mixed $value, Closure $fail): void {
                if (! is_string($value) || ! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $value)) {
                    $fail('ماه تولد را به‌درستی انتخاب کنید');

                    return;
                }

                $birth = CarbonImmutable::createFromFormat('!Y-m', $value, 'Asia/Tehran');
                $now = CarbonImmutable::now('Asia/Tehran')->startOfMonth();
                $ageMonths = (($now->year - $birth->year) * 12) + $now->month - $birth->month;

                if ($ageMonths < 6 || $ageMonths >= 156) {
                    $fail('سن کودک باید از ۶ ماهگی تا پیش از ۱۳ سالگی باشد');
                }
            }],
            'relationship_code' => ['required', 'in:parent,grandparent,relative,caregiver'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nickname.max' => 'نام کوچک یا لقب باید حداکثر ۴۰ نویسه باشد',
            'relationship_code.required' => 'نسبت خود با کودک را انتخاب کنید',
            'relationship_code.in' => 'نسبت انتخاب‌شده معتبر نیست',
        ];
    }
}
