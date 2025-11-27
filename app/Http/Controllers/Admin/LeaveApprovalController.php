<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    /**
     * Menampilkan daftar pengajuan yang perlu disetujui oleh User saat ini.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $pendingRequests = LeaveRequest::query()
            ->with(['user', 'type'])
            ->where('status', '!=', 'cancelled') // Abaikan yang dibatalkan
            ->where('status', '!=', 'rejected')  // Abaikan yang sudah ditolak
            ->where('status', '!=', 'approved')  // Abaikan yang sudah selesai
            ->where(function($query) use ($user) {
                
                // KONDISI 1: User adalah KETUA DIVISI
                if ($user->isDivisionHead() && $user->ledDivision) {
                    $query->orWhere(function($q) use ($user) {
                        $q->whereHas('user', function($u) use ($user) {
                            $u->where('division_id', $user->ledDivision->id)
                              ->where('id', '!=', $user->id);
                        })
                        ->where('status', 'pending');
                    });
                }

                // KONDISI 2: User adalah HRD (atau Admin)
                if ($user->isHrd() || $user->isAdmin()) {
                    $query->orWhere('status', 'approved_by_leader')
                          ->orWhere(function($q) {
                              $q->where('status', 'pending')
                                ->whereHas('user', fn($u) => $u->where('role', 'division_head'));
                          });
                }
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('admin.approvals.index', compact('pendingRequests'));
    }

    /**
     * Menyetujui Pengajuan (Approve)
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isFinalApproval = false;

        // 1. Logika untuk Ketua Divisi
        if ($user->isDivisionHead() && !$user->isHrd() && !$user->isAdmin()) {
            if ($leaveRequest->status !== 'pending') {
                return back()->withErrors('Status pengajuan tidak valid untuk disetujui Leader.');
            }
            
            $leaveRequest->update([
                'status' => 'approved_by_leader',
                'leader_approver_id' => $user->id,
                'leader_approved_at' => now(),
            ]);
        }
        
        // 2. Logika untuk HRD (Final Approval)
        else if ($user->isHrd() || $user->isAdmin()) {
            $isFinalApproval = true;
            
            $leaveRequest->update([
                'status' => 'approved',
                'hrd_approver_id' => $user->id,
                'hrd_approved_at' => now(),
            ]);

            // PENTING: Kurangi Kuota Cuti Tahunan User
            if ($leaveRequest->type->is_quota_deductible) {
                $applicant = $leaveRequest->user;
                if ($applicant->current_annual_leave_quota >= $leaveRequest->total_days) {
                    $applicant->decrement('current_annual_leave_quota', $leaveRequest->total_days);
                }
            }
        }

        $message = $isFinalApproval 
            ? 'Pengajuan cuti telah disetujui secara final.' 
            : 'Pengajuan cuti telah diverifikasi dan diteruskan ke HRD.';

        return back()->with('success', $message);
    }

    /**
     * Menolak Pengajuan (Reject)
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_note' => 'required|string|min:5',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $leaveRequest->update([
            'status' => 'rejected',
        ]);

        if ($user->isHrd() || $user->isAdmin()) {
            $leaveRequest->update([
                'hrd_approver_id' => $user->id,
                'hrd_rejection_note' => $request->rejection_note
            ]);
        } else {
            $leaveRequest->update([
                'leader_approver_id' => $user->id,
                'leader_rejection_note' => $request->rejection_note
            ]);
        }

        return back()->with('success', 'Pengajuan cuti telah ditolak.');
    }
}