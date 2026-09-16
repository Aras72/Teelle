<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class QuickMatchAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['age_years', 'age_months'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => strtr((string) $this->input($field), [
                    '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
                    '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
                    '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
                    '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
                ])]);
            }
        }
    }

    public function rules(): array
    {
        return match ((string) $this->input('step')) {
            'age' => ['step' => ['required', 'in:age'], 'age_years' => ['required', 'integer', 'min:0', 'max:12'], 'age_months' => ['required', 'integer', 'min:0', 'max:11']],
            'situation' => ['step' => ['required', 'in:situation'], 'answer' => ['required', Rule::exists('situations', 'slug')->where('is_active', true)]],
            'duration' => ['step' => ['required', 'in:duration'], 'answer' => ['required', 'integer', Rule::in([15, 30, 45])]],
            'location' => ['step' => ['required', 'in:location'], 'answer' => ['required', Rule::exists('locations', 'slug')->where('is_active', true)]],
            'materials' => ['step' => ['required', 'in:materials'], 'answer' => ['present', 'array', 'min:1', 'max:4'], 'answer.*' => ['string', 'distinct', Rule::in(['none', 'paper-pencil', 'ball', 'household-items'])]],
            'players' => ['step' => ['required', 'in:players'], 'answer' => ['required', Rule::exists('player_requirements', 'slug')->where('is_active', true)]],
            'caregiver_energy' => ['step' => ['required', 'in:caregiver_energy'], 'answer' => ['required', Rule::exists('energy_levels', 'slug')->where('is_active', true)]],
            'mood' => ['step' => ['required', 'in:mood'], 'answer' => ['required', Rule::exists('moods', 'slug')->where('is_active', true)]],
            default => ['step' => ['required', 'in:age,situation,duration,location,materials,players,caregiver_energy,mood']],
        };
    }

    /** @return array<int, callable> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($this->input('step') === 'age') {
                $age = ((int) $this->input('age_years') * 12) + (int) $this->input('age_months');
                if ($age < 6 || $age >= 156) {
                    $validator->errors()->add('age_years', 'سن باید از ۶ ماه تا پیش از ۱۳ سالگی باشد');
                }
            }
            if ($this->input('step') === 'materials') {
                $materials = (array) $this->input('answer', []);
                if (in_array('none', $materials, true) && count($materials) > 1) {
                    $validator->errors()->add('answer', '«بدون وسیله» را نمی‌توان همراه وسیله دیگری انتخاب کرد');
                }
            }
        }];
    }

    public function normalizedAnswer(): mixed
    {
        $step = (string) $this->validated('step');

        return match ($step) {
            'age' => ((int) $this->validated('age_years') * 12) + (int) $this->validated('age_months'),
            'duration' => (int) $this->validated('answer'),
            'materials' => array_values(array_diff($this->validated('answer'), ['none'])),
            'players' => match ($this->validated('answer')) {
                'child-and-adult' => ['requirement' => 'child-and-adult', 'children_count' => 1, 'adult_present' => true],
                'multiple-children' => ['requirement' => 'multiple-children', 'children_count' => 2, 'adult_present' => true],
                default => ['requirement' => 'no-adult', 'children_count' => 1, 'adult_present' => false],
            },
            default => $this->validated('answer'),
        };
    }
}
