<?php

namespace App\Http\Controllers;

use App\Enums\LeaveRequestStatus;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\LeaveRequestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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

    public function edit(LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        $leaveTypes = LeaveType::all();
        $user = Auth::user();

        return view('leave_requests.edit', compact('leaveRequest', 'leaveTypes', 'user'));
    }

    public function update(UpdateLeaveRequest $request, LeaveRequest $leaveRequest)
    {
        // Data yang tervalidasi bisa diambil via $request->validated()
        $data = $request->validated();

        // Panggil service untuk update logic
        return $this->leaveRequestService->updateLeaveRequest($data, Auth::user(), $leaveRequest);
    }

    public function cancel(LeaveRequest $leaveRequest)
    {
        $this->authorize('cancel', $leaveRequest);

        $leaveRequest->status = LeaveRequestStatus::Cancelled;

        $saved = $leaveRequest->save();

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $this->authorize('delete', $leaveRequest);

        // Hapus File Lampiran (Bersih-bersih penyimpanan)
        if ($leaveRequest->medical_certificate_path) {
            if (Storage::disk('public')->exists($leaveRequest->medical_certificate_path)) {
                Storage::disk('public')->delete($leaveRequest->medical_certificate_path);
            }
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Data pengajuan cuti berhasil dihapus permanen.');
    }

    public function generatePdf(LeaveRequest $leaveRequest)
    {
        // Policy Check
        // Akan melempar 403 jika Policy gagal (Status belum Approved atau bukan pemilik)
        $this->authorize('download', $leaveRequest);

        // Eager Load data yang dibutuhkan untuk tanda tangan
        $leaveRequest->load('user.division.head', 'hrdApprover', 'leaderApprover');

        // Generate PDF dari Blade View
        $pdf = Pdf::loadView('pdfs.leave_letter', compact('leaveRequest'));

        // Output: Download the file
        $filename = 'Surat_Cuti_' . $leaveRequest->user->name . '_' . $leaveRequest->start_date->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }
}
