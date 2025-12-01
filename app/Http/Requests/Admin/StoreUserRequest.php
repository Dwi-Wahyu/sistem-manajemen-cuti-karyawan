<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'initial_annual_leave_quota' => ['nullable', 'integer', 'min:0'],
            'join_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan, silakan pilih yang lain.',
            'username.max' => 'Username maksimal 255 karakter.',

            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar dalam sistem.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',

            'name.required' => 'Nama lengkap wajib diisi.',

            'role.required' => 'Role pengguna wajib dipilih.',
            'role.enum' => 'Role yang dipilih tidak valid.',

            'initial_annual_leave_quota.integer' => 'Kuota cuti harus berupa angka.',
            'initial_annual_leave_quota.min' => 'Kuota cuti tidak boleh kurang dari 0.',

            'join_date.required' => 'Tanggal bergabung wajib diisi.',
            'join_date.date' => 'Format tanggal bergabung tidak valid.',
        ];
    }
}
