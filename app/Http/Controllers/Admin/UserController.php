<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna (List Users).
     * Otorisasi: Admin/HRD (via UserPolicy@viewAny)
     */
    public function index(Request $request)
    {
        // Memanggil UserPolicy@viewAny: hanya diizinkan Admin dan HRD
        $this->authorize('viewAny', User::class); 

        // Data pendukung untuk filter
        $divisions = Division::pluck('name', 'id');
        $roles = ['admin', 'hrd', 'division_head', 'employee'];

        // Query dengan Filter dan Sortir
        $users = User::query()
            // Filter Role, Divisi, dan Status
            ->when($request->role, fn($q, $role) => $q->where('role', $role))
            ->when($request->division_id, fn($q, $div) => $q->where('division_id', $div))
            ->when($request->status, function($q, $status) {
                if ($status === 'active') {
                    $q->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            // Filter: Masa kerja < 1 tahun
            ->when($request->masa_kerja === 'ineligible', fn($q) => $q->where('join_date', '>', now()->subYear()))
            
            // Sortir
            ->when($request->sort_by, function($q, $sort_by) use ($request) {
                $direction = $request->input('sort_direction', 'asc');
                $q->orderBy($sort_by, $direction);
            }, fn($q) => $q->orderBy('name', 'asc')) // Default sortir: Nama

            ->with('division')
            ->paginate(15); 

        return view('admin.users.index', compact('users', 'divisions', 'roles'));
    }

    /**
     * Tampilkan form untuk membuat pengguna baru (Create User).
     * Otorisasi: Admin
     */
    public function create()
    {
        // Memanggil UserPolicy@create: hanya diizinkan Admin
        $this->authorize('create', User::class); 
        $roles = ['admin', 'hrd', 'division_head', 'employee'];
        return view('admin.users.create', compact('roles'));
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