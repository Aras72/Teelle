<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $actor = $this->user();
        $target = $this->route('user');

        if (! $actor?->hasPermission('users.edit') || ! $target) {
            return false;
        }

        return ! $target->roles()->where('code', 'admin')->exists() || $actor->hasPermission('roles.manage');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
            'phone_e164' => filled($this->input('phone_e164')) ? preg_replace('/\s+/', '', trim((string) $this->input('phone_e164'))) : null,
            'reason' => trim((string) $this->input('reason')),
        ]);
    }

    public function rules(): array
    {
        $target = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($target?->getKey())],
            'phone_e164' => ['nullable', 'regex:/^\+[1-9][0-9]{7,14}$/', Rule::unique('users', 'phone_e164')->ignore($target?->getKey())],
            'reason' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }
}
