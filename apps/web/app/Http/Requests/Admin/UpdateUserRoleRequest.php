<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('roles.manage') === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'support_admin' => $this->boolean('support_admin'),
            'permissions' => array_values(array_unique(array_map('strval', is_array($this->input('permissions')) ? $this->input('permissions') : []))),
            'reason' => trim((string) $this->input('reason')),
        ]);
    }

    public function rules(): array
    {
        return [
            'support_admin' => ['required', 'boolean'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'distinct', Rule::in([
                'content.edit', 'articles.edit', 'coverage.view', 'analytics.view',
                'subscription.manage', 'users.view', 'users.edit', 'users.delete',
            ])],
            'reason' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }
}
