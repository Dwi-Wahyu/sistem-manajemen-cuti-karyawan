<?php

namespace App\Policies;

use App\Enums\LeaveRequestStatus;
use App\Models\User;
use App\Models\LeaveRequest; // Pastikan model ini di-import
use Illuminate\Auth\Access\Response;

class LeaveRequestPolicy
{
    // ... method before (opsional) ...

    /**
     * Tentukan apakah pengguna dapat melihat model pengajuan cuti yang diberikan.
     */
    public function view(User $user, LeaveRequest $leaveRequest): Response|bool
    {
        // Mengizinkan Admin untuk melihat semua (jika Anda menggunakan before method, ini opsional)
        if ($user->isAdmin()) {
            return true;
        }

        // IZIN: User dapat melihat pengajuan cuti jika user_id pada pengajuan 
        // sama dengan ID user yang sedang login.
        return $user->id === $leaveRequest->user_id
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses ke pengajuan cuti ini.');
    }

    public function download(User $user, LeaveRequest $leaveRequest): Response|bool
    {
        // Hanya yang sudah disetujui final
        if ($leaveRequest->status !== LeaveRequestStatus::Approved) {
            return Response::deny('Surat cuti hanya dapat diunduh setelah disetujui final (Approved).');
        }

        // Izin untuk Pemilik Pengajuan
        if ($user->id === $leaveRequest->user_id) {
            return Response::allow();
        }

        // Izin untuk Pimpinan (Admin, HRD, Leader yang berhak melihat)
        if (
            $user->isDivisionHead() && $user->ledDivision->id === $leaveRequest->user->division_id
        ) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki izin mengunduh dokumen ini.');
    }


    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        // Hanya Admin, HRD yang boleh menghapus permanen
        return $user->isAdmin() || $user->isHrd() || $leaveRequest->user() === $user->id;
    }
}
