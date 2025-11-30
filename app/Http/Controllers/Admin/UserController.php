<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan form untuk membuat pengguna baru (Create User).
     * Otorisasi: Admin
     */
    public function create(Request $request)
    {
        // Memanggil UserPolicy@create: hanya diizinkan Admin
        $this->authorize('create', User::class);

        // Ambil nilai 'role' dari query string, default null
        $selectedRoleString = $request->query('role');

        // Konversi string menjadi objek Enum, jika valid
        $defaultRole = null;
        if ($selectedRoleString) {
            try {
                $defaultRole = UserRole::tryFrom($selectedRoleString);
            } catch (\Exception $e) {
                // Jika role tidak valid, $defaultRole tetap null
            }
        }

        $roles = UserRole::cases();
        $divisions = Division::pluck('name', 'id');

        return view('admin.users.create', compact('defaultRole', 'roles', 'divisions'));
    }

    /**
     * Simpan pengguna baru ke database (Store User).
     * Otorisasi: Admin
     */
    public function store(Request $request)
    {
        // Memanggil UserPolicy@create: hanya diizinkan Admin
        $this->authorize('create', User::class);

        // Validasi Sesuai Ketentuan Proyek
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
            'role' => ['required', Rule::in(['admin', 'hrd', 'division_head', 'employee'])],
            'initial_annual_leave_quota' => 'nullable|integer|min:0',
            'join_date' => 'required|date',
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'join_date' => $request->join_date,
            // Set kuota awal
            'initial_annual_leave_quota' => $request->initial_annual_leave_quota ?? 12,
            'current_annual_leave_quota' => $request->initial_annual_leave_quota ?? 12,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil dibuat.');
    }

    /**
     * Tampilkan form untuk edit pengguna (Edit User).
     * Otorisasi: Admin
     */
    public function edit(User $user)
    {
        // Memanggil UserPolicy@update: hanya diizinkan Admin
        $this->authorize('update', $user);
        $roles = ['admin', 'hrd', 'division_head', 'employee'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Perbarui data pengguna di database (Update User).
     * Otorisasi: Admin
     */
    public function update(Request $request, User $user)
    {
        // Memanggil UserPolicy@update: hanya diizinkan Admin
        $this->authorize('update', $user);

        // Validasi Edit User
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => 'required|string|max:255',
            'role' => ['required', Rule::in(['admin', 'hrd', 'division_head', 'employee'])],
            'is_active' => 'required|boolean',
            'join_date' => 'required|date',
            'initial_annual_leave_quota' => 'required|integer|min:0',
            'current_annual_leave_quota' => 'required|integer|min:0',
            'password' => 'nullable|string|min:8',
        ]);

        $data = $request->only(['username', 'email', 'name', 'role', 'is_active', 'join_date', 'initial_annual_leave_quota', 'current_annual_leave_quota']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna (Delete User).
     * Otorisasi: Admin (dengan pengecekan role yang boleh dihapus di Policy)
     */
    public function destroy(User $user)
    {
        // Memanggil UserPolicy@delete: hanya diizinkan Admin, dan cek apakah role-nya boleh dihapus
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
