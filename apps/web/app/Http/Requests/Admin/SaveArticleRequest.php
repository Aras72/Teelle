<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('articles.edit') ?? false;
    }

    public function rules(): array
    {
        $article = $this->route('article');

        return [
            'slug' => ['required', 'alpha_dash:ascii', 'max:160', Rule::unique('articles', 'slug')->ignore($article?->id)],
            'title' => ['required', 'string', 'max:220'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'body_markdown' => ['required', 'string', 'max:100000'],
            'seo_title' => ['nullable', 'string', 'max:220'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'categories' => ['required', 'array', 'min:1', 'max:8'],
            'categories.*' => ['integer', 'distinct', Rule::exists('article_categories', 'id')->where('is_active', true)],
            'cover' => ['nullable', 'file', 'max:8192', 'mimes:jpg,jpeg,png,webp'],
            'cover_alt' => ['nullable', 'required_with:cover', 'string', 'max:500'],
        ];
    }
}
