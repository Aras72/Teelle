<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveGameDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('content.edit') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $lines = fn ($value): array => array_values(array_filter(array_map('trim', preg_split('/\R/u', (string) $value) ?: [])));
        $this->merge([
            'instructions' => $lines($this->input('instructions_text')),
            'contraindications' => $lines($this->input('contraindications_text')),
        ]);
    }

    public function rules(): array
    {
        return [
            'slug' => [Rule::requiredIf(fn (): bool => $this->route('version') === null && $this->route('game') === null), 'nullable', 'alpha_dash:ascii', 'max:120', Rule::unique('games', 'slug')],
            'title' => ['required', 'string', 'max:180'], 'summary' => ['required', 'string', 'max:1000'],
            'instructions' => ['required', 'array', 'min:1', 'max:20'], 'instructions.*' => ['string', 'max:1000'],
            'safety_copy' => ['required', 'string', 'max:2000'], 'contraindications' => ['array', 'max:20'],
            'contraindications.*' => ['string', 'max:500'],
            'supervision_level' => ['required', 'in:within_reach,same_room,check_in'],
        ];
    }
}
