<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Division;
use App\Enums\LeaveRequestStatus;
use App\Enums\UserRole; // Pastikan Enum UserRole sudah di-import
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menentukan role user dan mendelegasikan pengambilan data ke method spesifik.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $data = [];

        // Ambil data personal
        $data = $this->getEmployeeMetrics($user);

        if ($user->isAdmin()) {
            $data = array_merge($data, $this->getAdminMetrics());
            return view('dashboard.admin', compact('data'));
        }

        // Prioritas kedua: HRD
        if ($user->isHrd()) {
            $data = array_merge($data, $this->getHrdMetrics());
            return view('dashboard.hrd', compact('data'));
        }

        // Prioritas ketiga: KETUA DIVISI
        if ($user->isDivisionHead() && $user->ledDivision) {
            $data = array_merge($data, $this->getLeaderMetrics($user));
            return view('dashboard.division_head', compact('data'));
        }

        // Default: KARYAWAN BIASA
        return view('dashboard.employee', compact('data'));
    }

    /**
     * Data Dasar untuk setiap karyawan.
     */
    private function getEmployeeMetrics(User $user): array
    {
        // Total cuti sakit (Asumsi di database menggunakan ID atau nama tipe cuti yang spesifik)
        // Jika Anda memiliki model LeaveType, Anda bisa mencari ID-nya terlebih dahulu.
        $sickLeaveTypeId = \App\Models\LeaveType::where('name', 'Cuti Sakit')->value('id');

        return [
            'my_quota' => $user->current_annual_leave_quota ?? 0,
            'my_division_name' => $user->division->name ?? 'Belum Ditentukan',
            'my_leader_name' => $user->division?->head->name ?? 'Belum Ditentukan',
            'my_total_submissions' => LeaveRequest::where('user_id', $user->id)->count(),

            // Jumlah total hari cuti sakit yang disetujui
            'my_total_sick_days' => LeaveRequest::where('user_id', $user->id)
                ->where('leave_type_id', $sickLeaveTypeId)
                ->where('status', LeaveRequestStatus::Approved)
                ->sum('total_days'),

            'my_last_request' => LeaveRequest::where('user_id', $user->id)
                ->with('type')
                ->latest()
                ->first(),
        ];
    }

    /**
     * Data Global untuk Role Admin.
     */
    private function getAdminMetrics(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Karyawan belum eligible (masa kerja < 1 tahun)
        $ineligibleEmployeesCount = User::where('join_date', '>', now()->subYear())->count();

        // --- Ambil 5 Log Terakhir ---
        $latestLogs = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return [
            'total_employees' => User::where('role', UserRole::Employee)->count(),
            'total_divisions' => Division::count(),

            'active_employees' => User::where('is_active', true)->count(),
            'inactive_employees' => User::where('is_active', false)->count(),

            // Total pengajuan bulan ini (dihitung dari tanggal dibuat)
            'total_submissions_month' => LeaveRequest::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),

            // Pengajuan pending global (sebelum leader/hrd)
            'global_pending' => LeaveRequest::whereIn('status', [LeaveRequestStatus::Pending, LeaveRequestStatus::ApprovedByLeader])->count(),

            'ineligible_count' => $ineligibleEmployeesCount,

            'latest_logs' => $latestLogs,
        ];
    }

    /**
     * Data Spesifik untuk Role HRD.
     */
    private function getHrdMetrics(): array
    {
        $currentMonth = now()->month;

        return [
            // Pending yang sudah lolos Leader, siap disetujui HRD
            'pending_final' => LeaveRequest::where('status', LeaveRequestStatus::ApprovedByLeader)->count(),

            // Pengajuan cuti bulan ini
            'total_submissions_month' => LeaveRequest::whereMonth('created_at', $currentMonth)->count(),

            // Karyawan sedang cuti bulan ini
            'on_leave_this_month' => LeaveRequest::where('status', LeaveRequestStatus::Approved)
                ->where(function ($query) {
                    $query->whereMonth('start_date', now()->month)
                        ->orWhereMonth('end_date', now()->month);
                })
                ->count(),

            'list_divisions' => Division::all(),
        ];
    }

    /**
     * Data Spesifik untuk Ketua Divisi.
     */
    private function getLeaderMetrics(User $user): array
    {
        $divId = $user->ledDivision->id ?? null;

        if (!$divId) {
            return [
                'team_pending' => 0,
                'team_count' => 0,
                'team_on_leave_this_week' => 0,
            ];
        }

        $next7Days = now()->addDays(7);

        return [
            // Pengajuan dari anggota tim yang menunggu verifikasi Leader
            'team_pending' => LeaveRequest::whereHas('user', fn($q) => $q->where('division_id', $divId))
                ->where('status', LeaveRequestStatus::Pending)
                ->where('user_id', '!=', $user->id) // Kecuali pengajuan sendiri
                ->count(),

            // Total Anggota di Divisi (termasuk Leader)
            'team_count' => User::where('division_id', $divId)->count(),

            // Anggota yang sedang cuti minggu ini (berstatus Approved)
            'team_on_leave_this_week' => LeaveRequest::whereHas('user', fn($q) => $q->where('division_id', $divId))
                ->where('status', LeaveRequestStatus::Approved)
                ->where(function ($query) use ($next7Days) {
                    $query->whereDate('start_date', '<=', $next7Days)
                        ->whereDate('end_date', '>=', now());
                })
                ->count(),
        ];
    }
}
