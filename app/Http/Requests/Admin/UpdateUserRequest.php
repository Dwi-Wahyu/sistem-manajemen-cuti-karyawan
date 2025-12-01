<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        return $this->user()->can('update', $user);
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'is_active' => ['required', 'boolean'],
            'join_date' => ['required', 'date'],
            'initial_annual_leave_quota' => ['required', 'integer', 'min:0'],
            'current_annual_leave_quota' => ['required', 'integer', 'min:0'],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan user lain.',

            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan user lain.',

            'name.required' => 'Nama lengkap wajib diisi.',

            'role.required' => 'Role pengguna wajib dipilih.',

            'is_active.required' => 'Status aktif wajib dipilih.',

            'join_date.required' => 'Tanggal bergabung wajib diisi.',

            'initial_annual_leave_quota.required' => 'Kuota awal wajib diisi.',
            'current_annual_leave_quota.required' => 'Sisa kuota saat ini wajib diisi.',

            'password.min' => 'Password baru minimal harus 8 karakter.',
        ];
    }
}
