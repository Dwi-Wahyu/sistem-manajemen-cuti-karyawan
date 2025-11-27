<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $data = [];

        // --- DATA UNTUK SEMUA USER (My Stats) ---
        $data['my_quota'] = $user->current_annual_leave_quota;
        $data['my_pending'] = LeaveRequest::where('user_id', $user->id)->where('status', 'pending')->count();
        $data['my_approved'] = LeaveRequest::where('user_id', $user->id)->where('status', 'approved')->count();
        $data['my_last_request'] = LeaveRequest::where('user_id', $user->id)->latest()->first();

        // --- DATA KHUSUS ADMIN & HRD ---
        if ($user->isAdmin() || $user->isHrd()) {
            $data['total_employees'] = User::count();
            $data['total_divisions'] = Division::count();
            
            // Pending Approvals Global
            $data['global_pending'] = LeaveRequest::whereIn('status', ['pending', 'approved_by_leader'])->count();
            
            // Yang sedang cuti hari ini
            $data['on_leave_today'] = LeaveRequest::where('status', 'approved')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->count();
        }

        // --- DATA KHUSUS KETUA DIVISI ---
        if ($user->isDivisionHead() && $user->ledDivision) {
            $divId = $user->ledDivision->id;
            
            // Menunggu verifikasi dari anggota tim sendiri
            $data['team_pending'] = LeaveRequest::whereHas('user', fn($q) => $q->where('division_id', $divId))
                ->where('status', 'pending')
                ->where('user_id', '!=', $user->id) // Kecuali punya sendiri
                ->count();

            // Anggota tim
            $data['team_count'] = User::where('division_id', $divId)->count();
        }

        return view('dashboard', compact('data'));
    }
}