<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        $isUpdate = $user !== null;

        return [
            'name' => ['required', 'string', 'max:120'],
            'username' => [
                'required', 'string', 'min:3', 'max:100', 'alpha_dash',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'required', 'email', 'max:120',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'role' => ['required', Rule::in(array_keys(UserRole::options()))],
            'password' => $isUpdate
                ? ['nullable', 'string', 'min:8', 'confirmed']
                : ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'username' => 'username',
            'email' => 'email',
            'phone' => 'nomor telepon',
            'role' => 'role',
            'password' => 'password',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $user = $this->route('user');
            $acting = $this->user();

            if ($user === null || $acting === null || (int) $user->id !== (int) $acting->id) {
                return;
            }

            $newRole = $this->input('role');
            $isActive = $this->input('is_active');
            $hasActiveCheckbox = $this->has('is_active');

            if ($newRole !== null && $newRole !== $acting->role?->value) {
                $validator->errors()->add('role', 'Anda tidak dapat mengubah role akun Anda sendiri.');
            }

            if ($hasActiveCheckbox && ! filter_var($isActive, FILTER_VALIDATE_BOOLEAN)) {
                $validator->errors()->add('is_active', 'Anda tidak dapat menonaktifkan akun yang sedang Anda gunakan sendiri.');
            }
        });
    }
}
