<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('content.edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120', 'dimensions:min_width=400,min_height=400,max_width=4096,max_height=4096'],
            'alt_text' => ['required', 'string', 'min:5', 'max:500'],
            'role' => ['required', 'in:cover,detail,step'], 'sort_order' => ['required', 'integer', 'min:0', 'max:100'],
            'crop_json' => ['required', 'json', 'max:2000'],
        ];
    }
}
