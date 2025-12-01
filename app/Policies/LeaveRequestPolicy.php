<?php

namespace App\Policies;

use App\Enums\LeaveRequestStatus;
use App\Models\User;
use App\Models\LeaveRequest; // Pastikan model ini di-import
use Illuminate\Auth\Access\Response;

class LeaveRequestPolicy
{
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

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        // Validasi Kepemilikan
        // User yang login HARUS sama dengan user yang membuat pengajuan.
        if ($user->id !== $leaveRequest->user_id) {
            return false;
        }

        // Validasi Status
        // Hanya boleh edit jika statusnya masih 'Pending' atau 'Rejected'.
        // Jika sudah 'ApprovedByLeader' atau 'Approved', tidak boleh diubah lagi.
        return in_array($leaveRequest->status, [
            LeaveRequestStatus::Pending,
            LeaveRequestStatus::Rejected
        ]);
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
        // Hanya Admin, HRD, Karyawan yang membuat yang boleh menghapus permanen
        return $user->isAdmin() || $user->isHrd() || $leaveRequest->user() === $user->id;
    }

    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        // Cek Pemilik
        if ($leaveRequest->user_id !== $user->id) {
            return false;
        }

        // Jangan biarkan user membatalkan jika sudah disetujui/ditolak
        return $leaveRequest->status === LeaveRequestStatus::Pending;
    }
}
