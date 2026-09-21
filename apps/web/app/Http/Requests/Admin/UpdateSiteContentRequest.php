<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateSiteContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('site.manage') === true;
    }

    public function rules(): array
    {
        return [
            'nav_jigari_label' => ['required', 'string', 'max:40'], 'nav_about_label' => ['required', 'string', 'max:40'],
            'nav_magazine_label' => ['required', 'string', 'max:40'],
            'nav_order' => ['required', Rule::in(['jigari_about_magazine', 'jigari_magazine_about', 'magazine_jigari_about', 'about_jigari_magazine', 'about_magazine_jigari', 'magazine_about_jigari'])],
            'jigari_title' => ['required', 'string', 'max:80'], 'jigari_intro' => ['required', 'string', 'max:220'],
            'magazine_title' => ['required', 'string', 'max:80'], 'magazine_intro' => ['required', 'string', 'max:220'],
            'home_title' => ['required', 'string', 'max:80'], 'home_intro' => ['required', 'string', 'max:140'],
            'home_cta_label' => ['required', 'string', 'max:40'], 'brand_promise' => ['required', 'string', 'max:120'],
            'home_alignment' => ['required', Rule::in(['center', 'start'])],
            'about_title' => ['required', 'string', 'max:80'], 'about_lead' => ['required', 'string', 'max:220'],
            'about_cta_label' => ['required', 'string', 'max:40'],
        ];
    }
}
