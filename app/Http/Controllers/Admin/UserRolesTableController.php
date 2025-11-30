<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRolesTableController extends Controller
{
    // Tambahkan parameter $role = null agar kompatibel jika dipanggil via route resource biasa
    public function index(Request $request, ?string $role = null)
    {
        // Authorize (Sekarang Division Head sudah boleh lewat sini)
        $this->authorize('viewAny', User::class);

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Logika Role Filter Awal
        $filterRoleString = $role ?? $request->role;
        $currentRoleEnum = null;

        // KHUSUS DIVISION HEAD:
        // Jika user adalah Kepala Divisi, kita paksa dia hanya melihat 'employee'
        // dan mengabaikan request role dari URL (kecuali Anda ingin dia bisa filter lain)
        if ($currentUser->isDivisionHead()) {
            $currentRoleEnum = UserRole::Employee;
        }
        // JIKE BUKAN (Admin/HRD): Proses filter role seperti biasa
        elseif ($filterRoleString) {
            try {
                $currentRoleEnum = UserRole::from($filterRoleString);
            } catch (\ValueError $e) {
                $filterRoleString = null;
            }
        }

        // Data pendukung untuk filter
        $divisions = Division::pluck('name', 'id');

        // Query Builder
        $query = User::query();

        // --- FILTER KHUSUS DIVISION HEAD ---
        if ($currentUser->isDivisionHead()) {
            // Memastikan division_head punya divisi. Jika tidak, menampilkan daftar user kosong
            if ($currentUser->division_id) {
                $query->where('division_id', $currentUser->division_id);
            } else {
                // Kepala Divisi tapi belum diassign divisi
                $query->whereRaw('1 = 0');
            }

            // Opsional: Sembunyikan dirinya sendiri dari daftar
            $query->where('id', '!=', $currentUser->id);
        }
        // --- FILTER UNTUK ADMIN / HRD ---
        else {
            // Gunakan filter divisi dari Request (Dropdown)
            $query->when($request->division_id, fn($q, $div) => $q->where('division_id', $div));
        }

        // --- FILTER UMUM (Lanjutan) ---
        $users = $query
            // Filter Role (Gunakan Enum yang sudah ditentukan di atas)
            ->when($currentRoleEnum, fn($q, $enum) => $q->where('role', $enum))

            ->when($request->status, function ($q, $status) {
                if ($status === 'active') $q->where('is_active', true);
                elseif ($status === 'inactive') $q->where('is_active', false);
            })
            // Filter masa kerja
            ->when($request->masa_kerja === 'ineligible', fn($q) => $q->where('join_date', '>', now()->subYear()))
            // Sortir
            ->when($request->sort_by, function ($q, $sort_by) use ($request) {
                $direction = $request->input('sort_direction', 'asc');
                $q->orderBy($sort_by, $direction);
            }, fn($q) => $q->orderBy('name', 'asc'))

            ->with('division')
            ->cursorPaginate(10);

        return view('admin.users.index', compact('users', 'divisions', 'currentRoleEnum'));
    }
}
