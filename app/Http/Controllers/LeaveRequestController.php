<?php

namespace App\Http\Controllers;

use App\Enums\LeaveRequestStatus;
use App\Http\Requests\StoreLeaveRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\LeaveRequestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LeaveRequestController extends Controller
{
    protected LeaveRequestService $leaveRequestService;

    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->leaveRequestService = $leaveRequestService;
    }

    /**
     * Menampilkan riwayat cuti pengguna yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data cuti milik user sendiri, urutkan dari yang terbaru
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->with(['type', 'leaderApprover', 'hrdApprover'])
            ->latest()
            ->paginate(10);

        return view('leave_requests.index', compact('leaveRequests'));
    }

    /**
     * Menampilkan form pengajuan cuti.
     */
    public function create()
    {
        $leaveTypes = LeaveType::all();
        $user = Auth::user();

        return view('leave_requests.create', compact('leaveTypes', 'user'));
    }

    /**
     * Memproses penyimpanan pengajuan cuti (VALIDASI UTAMA).
     */
    public function store(StoreLeaveRequest $request)
    {
        return $this->leaveRequestService->createLeaveRequest(
            $request->validated(),
            Auth::user()
        );
    }

    /**
     * Menampilkan detail pengajuan cuti.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);
        return view('leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Membatalkan pengajuan cuti (Hanya jika masih Pending).
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        if (! Gate::allows('cancel-leave-request', $leaveRequest)) {
            return back()->withErrors('Pembatalan gagal. Akses ditolak.');
        }

        $leaveRequest->status = LeaveRequestStatus::Cancelled;

        $saved = $leaveRequest->save();

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    // Metode destroy standar (opsional, jika ingin hapus data fisik)
    public function destroy(LeaveRequest $leaveRequest)
    {
        // Biasanya kita tidak menghapus data fisik (soft delete) atau cukup pakai cancel.
        // Kita gunakan cancel() saja untuk logic bisnis.
        return $this->cancel($leaveRequest);
    }

    public function generatePdf(LeaveRequest $leaveRequest)
    {
        // 1. Policy Check
        // Akan melempar 403 jika Policy gagal (Status belum Approved atau bukan pemilik)
        $this->authorize('download', $leaveRequest);

        // 2. Eager Load data yang dibutuhkan untuk tanda tangan
        $leaveRequest->load('user.division.head', 'hrdApprover', 'leaderApprover');

        // 3. Generate PDF dari Blade View
        $pdf = Pdf::loadView('pdfs.leave_letter', compact('leaveRequest'));

        // 4. Output: Download the file
        $filename = 'Surat_Cuti_' . $leaveRequest->user->name . '_' . $leaveRequest->start_date->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }
}
