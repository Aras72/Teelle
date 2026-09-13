<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewContentRequest extends FormRequest
{
    private const SCOPES = ['copy', 'source', 'age', 'safety', 'cover'];

    public function authorize(): bool
    {
        return $this->user()?->can('content.review') ?? false;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'in:approved,changes_requested'],
            'notes' => [$this->string('decision')->toString() === 'changes_requested' ? 'required' : 'nullable', 'string', 'max:2000'],
            'checks' => ['required', 'array:'.implode(',', self::SCOPES)],
            'checks.copy' => ['required', 'in:approved,changes_requested'],
            'checks.source' => ['required', 'in:approved,changes_requested'],
            'checks.age' => ['required', 'in:approved,changes_requested'],
            'checks.safety' => ['required', 'in:approved,changes_requested'],
            'checks.cover' => ['required', 'in:approved,changes_requested'],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'برای درخواست اصلاح، توضیح روشن لازم است',
            'checks.required' => 'نتیجه هر پنج حوزه بازبینی باید ثبت شود',
            'checks.array' => 'نتیجه هر پنج حوزه بازبینی باید ثبت شود',
            'checks.*.required' => 'نتیجه هر پنج حوزه بازبینی باید ثبت شود',
            'checks.*.in' => 'نتیجه بازبینی هر حوزه معتبر نیست',
        ];
    }

    protected function prepareForValidation(): void
    {
        $checks = $this->input('checks');
        if (! is_array($checks) || array_diff(self::SCOPES, array_keys($checks)) !== []) {
            return;
        }

        $this->merge([
            'decision' => in_array('changes_requested', $checks, true) ? 'changes_requested' : 'approved',
        ]);
    }
}
