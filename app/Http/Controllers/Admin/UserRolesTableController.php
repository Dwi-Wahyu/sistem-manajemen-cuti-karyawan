<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;

class UserRolesTableController extends Controller
{
    public function index(Request $request)
    {
        // Memanggil UserPolicy@viewAny: hanya diizinkan Admin dan HRD
        $this->authorize('viewAny', User::class);

        // Role yang akan difilter
        $filterRoleString = $role ?? $request->role;

        // Konversi string role ke objek Enum
        $currentRoleEnum = null;
        if ($filterRoleString) {
            // Coba membuat Enum dari nilai string. Gunakan try-catch untuk validasi.
            try {
                $currentRoleEnum = UserRole::from($filterRoleString);
            } catch (\ValueError $e) {
                // Jika role tidak valid, abaikan filter atau lempar 404
                $filterRoleString = null;
            }
        }

        // Data pendukung untuk filter
        $divisions = Division::pluck('name', 'id');

        // Query dengan Filter dan Sortir
        $users = User::query()
            // Menggunakan nilai string dari Enum untuk filtering
            ->when($currentRoleEnum, fn($q, $enum) => $q->where('role', $enum->value))
            ->when($request->division_id, fn($q, $div) => $q->where('division_id', $div))
            ->when($request->status, function ($q, $status) {
                if ($status === 'active') {
                    $q->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            // Filter: Masa kerja < 1 tahun
            ->when($request->masa_kerja === 'ineligible', fn($q) => $q->where('join_date', '>', now()->subYear()))
            // Sortir
            ->when($request->sort_by, function ($q, $sort_by) use ($request) {
                $direction = $request->input('sort_direction', 'asc');
                $q->orderBy($sort_by, $direction);
            }, fn($q) => $q->orderBy('name', 'asc')) // Default sortir: Nama

            ->with('division')
            ->cursorPaginate(10);

        return view('admin.users.index', compact('users', 'divisions', 'currentRoleEnum'));
    }
}
