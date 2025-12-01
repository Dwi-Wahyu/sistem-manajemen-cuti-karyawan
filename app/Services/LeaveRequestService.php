<?php

namespace App\Services;

use App\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Holiday;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;

class LeaveRequestService
{
    /**
     * Handle create
     */
    public function createLeaveRequest(array $data, $user)
    {
        $leaveType = LeaveType::findOrFail($data['leave_type_id']);

        $startDate = Carbon::parse($data['start_date']);
        $endDate   = Carbon::parse($data['end_date']);

        // Hitung hari kerja (sudah memperhitungkan libur)
        $totalDays = $this->countWorkDays($startDate, $endDate);

        if ($totalDays <= 0) {
            return $this->error('Total hari cuti adalah 0 (mungkin tanggal yang dipilih adalah akhir pekan atau hari libur).');
        }

        // Cek overlap cuti
        if ($this->hasOverlap($user->id, $startDate, $endDate)) {
            return $this->error('Anda sudah memiliki pengajuan cuti pada rentang tanggal tersebut.');
        }

        $filePath = null;

        if ($leaveType->name === 'Cuti Tahunan') {
            $error = $this->validateAnnualLeave($user, $totalDays, $startDate);
            if ($error) return $this->error($error);
        }

        if ($leaveType->name === 'Cuti Sakit') {
            $result = $this->validateSickLeave(request(), $startDate);
            if ($result['error']) return $this->error($result['error']);
            $filePath = $result['file'];
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $data['reason'],
            'status' => 'pending',
            'medical_certificate_path' => $filePath,
            'contact_address_during_leave' => $data['address'] ?? $user->address,
            'emergency_number'         => $data['phone_number'] ?? $user->phone_number,
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    /**
     * Handle UPDATE
     */
    public function updateLeaveRequest(array $data, $user, LeaveRequest $leaveRequest)
    {
        $leaveType = LeaveType::findOrFail($data['leave_type_id']);
        $startDate = Carbon::parse($data['start_date']);
        $endDate   = Carbon::parse($data['end_date']);

        // Hitung Ulang Hari Kerja
        $totalDays = $this->countWorkDays($startDate, $endDate);
        if ($totalDays <= 0) return $this->error('Durasi cuti 0 hari kerja.');

        // Cek Overlap (Kecuali ID pengajuan ini sendiri)
        if ($this->hasOverlap($user->id, $startDate, $endDate, $leaveRequest->id)) {
            return $this->error('Tanggal bentrok dengan pengajuan lain.');
        }

        // Validasi Khusus
        if ($leaveType->name === 'Cuti Tahunan') {
            // Logic validasi kuota saat update bisa dilewatkan atau disesuaikan
            // Di sini kita cek validasi standar saja
            $err = $this->validateAnnualLeave($user, $totalDays, $startDate);
            if ($err) return $this->error($err);
        }

        // Handle File Upload (Ganti file lama jika ada yang baru)
        $filePath = $leaveRequest->medical_certificate_path;

        if ($leaveType->name === 'Cuti Sakit') {
            // Untuk update, file tidak wajib (false)
            $result = $this->validateSickLeave($data, $startDate, false);

            if ($result['error']) return $this->error($result['error']);

            // Jika ada file baru diupload
            if ($result['file']) {
                // Hapus file lama
                if ($leaveRequest->medical_certificate_path) {
                    Storage::disk('public')->delete($leaveRequest->medical_certificate_path);
                }
                $filePath = $result['file'];
            }
        }

        // Update & Reset Status
        $leaveRequest->update([
            'leave_type_id' => $leaveType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $data['reason'],
            'medical_certificate_path' => $filePath,
            'contact_address_during_leave' => $data['address'] ?? $leaveRequest->contact_address_during_leave,
            'emergency_number'         => $data['phone_number'] ?? $leaveRequest->emergency_number,

            // RESET STATUS KE PENDING & HAPUS LOG APPROVAL
            'status' => LeaveRequestStatus::Pending,
            'leader_approver_id' => null,
            'leader_approved_at' => null,
            'leader_rejection_note' => null,
            'hrd_approver_id' => null,
            'hrd_approved_at' => null,
            'hrd_rejection_note' => null,
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Perubahan disimpan. Status kembali menjadi Menunggu Konfirmasi.');
    }

    /* ================================================================
     * VALIDASI KHUSUS
     * ================================================================ */

    private function validateAnnualLeave($user, $totalDays, $startDate)
    {
        if ($user->current_annual_leave_quota < $totalDays) {
            return "Sisa kuota cuti tahunan Anda tidak cukup.";
        }

        // Menggunakan false pada diffInDays agar bisa mendeteksi tanggal masa lalu (minus)
        if ($startDate->diffInDays(now(), false) > -3) {
            return "Pengajuan Cuti Tahunan minimal H-3 sebelum tanggal mulai.";
        }

        return null;
    }

    private function validateSickLeave($request, $startDate)
    {
        if (!$request->hasFile('medical_certificate')) {
            return [
                'error' => 'Surat keterangan dokter wajib diunggah.',
                'file' => null
            ];
        }

        if (now()->diffInDays($startDate) > 3 && $startDate->isPast()) {
            return [
                'error' => 'Pengajuan Cuti Sakit maksimal dilakukan 3 hari setelah tanggal mulai sakit.',
                'file' => null
            ];
        }

        return [
            'error' => null,
            'file' => $request->file('medical_certificate')->store('medical_certificates', 'public')
        ];
    }

    /* ================================================================
     * HELPER
     * ================================================================ */

    /**
     * Menghitung jumlah hari kerja, melewati Weekend DAN Hari Libur (Holidays)
     */
    private function countWorkDays($start, $end)
    {
        $days = 0;
        $period = CarbonPeriod::create($start, $end);

        // Ambil daftar tanggal libur yang berada dalam rentang pengajuan
        // Menggunakan format string Y-m-d untuk pencocokan yang akurat
        $holidays = Holiday::whereBetween('date', [
            $start->format('Y-m-d'),
            $end->format('Y-m-d')
        ])
            ->pluck('date') // Mengambil kolom date
            ->map(fn($date) => $date->format('Y-m-d')) // Pastikan format string
            ->toArray();

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            // Cek 1: Bukan Sabtu/Minggu
            // Cek 2: Tidak ada di dalam daftar holidays
            if (!$date->isWeekend() && !in_array($dateString, $holidays)) {
                $days++;
            }
        }

        return $days;
    }

    private function hasOverlap($userId, $start, $end)
    {
        return LeaveRequest::where('user_id', $userId)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();
    }

    private function error($message)
    {
        return back()->withErrors(['error' => $message])->withInput();
    }
}
