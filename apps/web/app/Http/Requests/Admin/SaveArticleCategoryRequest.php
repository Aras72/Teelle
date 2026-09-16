<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveArticleCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('articles.edit') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:120', Rule::unique('article_categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'integer', Rule::exists('article_categories', 'id'), Rule::notIn(array_filter([$category?->id]))],
            'sort_order' => ['required', 'integer', 'min:0', 'max:1000'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
