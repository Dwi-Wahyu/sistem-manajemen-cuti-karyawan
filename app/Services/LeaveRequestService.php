<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LeaveRequestService
{
    /**
     * Entry point utama dari controller
     */
    public function createLeaveRequest(array $data, $user)
    {
        $leaveType = LeaveType::findOrFail($data['leave_type_id']);

        $startDate = Carbon::parse($data['start_date']);
        $endDate   = Carbon::parse($data['end_date']);

        // Hitung hari kerja
        $totalDays = $this->countWorkDays($startDate, $endDate);

        if ($totalDays <= 0) {
            return $this->error('Anda tidak dapat mengajukan cuti hanya pada akhir pekan.');
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

        // Simpan ke database
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

    /* ================================================================
     * VALIDASI KHUSUS
     * ================================================================ */

    private function validateAnnualLeave($user, $totalDays, $startDate)
    {
        if ($user->current_annual_leave_quota < $totalDays) {
            return "Sisa kuota cuti tahunan Anda tidak cukup.";
        }

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

    private function countWorkDays($start, $end)
    {
        $days = 0;
        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            if (!$date->isWeekend()) {
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
