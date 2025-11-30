<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeaveRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Division;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    /**
     * Menampilkan daftar pengajuan yang perlu disetujui oleh User saat ini.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $leaveTypes = LeaveType::all();
        $divisions = Division::all();

        $pendingRequests = LeaveRequest::query()
            ->with(['user', 'type'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->type_id, function ($query, $typeId) {
                $query->where('leave_type_id', $typeId);
            })
            ->when($request->division_id, function ($query, $divId) {
                $query->whereHas('user', function ($q) use ($divId) {
                    $q->where('division_id', $divId);
                });
            })

            ->where('status', '!=', LeaveRequestStatus::Cancelled)
            ->where('status', '!=', LeaveRequestStatus::Rejected)
            ->where('status', '!=', LeaveRequestStatus::Approved)

            ->where(function ($query) use ($user) {
                if ($user->isDivisionHead() && $user->ledDivision) {
                    $query->orWhere(function ($q) use ($user) {
                        $q->whereHas('user', function ($u) use ($user) {
                            $u->where('division_id', $user->ledDivision->id)
                                ->where('id', '!=', $user->id);
                        })->where('status', 'pending');
                    });
                }

                if ($user->isHrd() || $user->isAdmin()) {
                    $query->orWhere('status', 'approved_by_leader')
                        ->orWhere(function ($q) {
                            $q->where('status', 'pending')
                                ->whereHas('user', fn($u) => $u->where('role', 'division_head'));
                        });
                }
            })

            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->cursorPaginate(10)
            ->withQueryString(); // Agar parameter filter tetap ada saat pindah halaman (pagination)

        // Kirim $leaveTypes dan $divisions ke view
        return view('admin.approvals.index', compact('pendingRequests', 'leaveTypes', 'divisions'));
    }

    public function show($id)
    {
        $leaveRequest = \App\Models\LeaveRequest::with(['user.division', 'type'])->findOrFail($id);

        return view('admin.approvals.show', compact('leaveRequest'));
    }

    /**
     * Menyetujui Pengajuan (Approve)
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isFinalApproval = false;

        // Logika untuk Ketua Divisi
        if ($user->isDivisionHead() && !$user->isHrd() && !$user->isAdmin()) {
            // Cek status
            if ($leaveRequest->status !== LeaveRequestStatus::Pending) {
                return back()->withErrors('Status pengajuan tidak valid untuk disetujui Leader.');
            }

            $leaveRequest->update([
                'status' => LeaveRequestStatus::ApprovedByLeader,
                'leader_approver_id' => $user->id,
                'leader_approved_at' => now(),
            ]);
        }

        // Logika untuk HRD (Final Approval)
        else if ($user->isHrd() || $user->isAdmin()) {
            $isFinalApproval = true;

            $leaveRequest->update([
                'status' => LeaveRequestStatus::Approved,
                'hrd_approver_id' => $user->id,
                'hrd_approved_at' => now(),
            ]);

            // Potong Kuota Cuti
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

        // Update status ke Rejected
        $leaveRequest->update([
            'status' => LeaveRequestStatus::Rejected,
        ]);

        // Simpan catatan penolakan sesuai siapa yang menolak
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

        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'rejected',
            'description'  => 'Menolak pengajuan cuti dengan alasan: ' . $request->rejection_note,
            'subject_type' => get_class($leaveRequest),
            'subject_id'   => $leaveRequest->id,
            'ip_address'   => request()->ip(),
        ]);

        return back()->with('success', 'Pengajuan cuti telah ditolak.');
    }
}
