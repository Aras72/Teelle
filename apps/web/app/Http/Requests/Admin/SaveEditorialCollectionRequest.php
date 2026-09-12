<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SaveEditorialCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('content.edit') === true;
    }

    public function rules(): array
    {
        $collection = $this->route('collection');

        return [
            'title' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'alpha_dash:ascii', Rule::unique('editorial_collections', 'slug')->ignore($collection?->id)],
            'summary' => ['required', 'string', 'min:20', 'max:1000'],
            'game_ids' => ['present', 'array', 'max:30'],
            'game_ids.*' => ['integer', 'distinct', 'exists:games,id'],
        ];
    }
}
