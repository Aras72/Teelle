<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('content.review') ?? false;
    }

    public function rules(): array
    {
        return ['decision' => ['required', 'in:approved,changes_requested'], 'notes' => ['nullable', 'string', 'max:2000']];
    }
}
