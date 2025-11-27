<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveRequestController extends Controller
{
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
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input Dasar
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'medical_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        $leaveType = LeaveType::find($request->leave_type_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Hitung durasi hari kerja (Senin-Jumat)
        $totalDays = 0;
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $totalDays++;
            }
        }

        if ($totalDays <= 0) {
            return back()->withErrors(['start_date' => 'Anda tidak dapat mengajukan cuti hanya pada akhir pekan (Sabtu/Minggu).'])->withInput();
        }

        // --- VALIDASI ATURAN BISNIS ---

        // A. Cek Overlap: Apakah sudah ada pengajuan di tanggal yang sama?
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['start_date' => 'Anda sudah memiliki pengajuan cuti pada rentang tanggal tersebut.'])->withInput();
        }

        // B. Logika Khusus: CUTI TAHUNAN
        if ($leaveType->name === 'Cuti Tahunan') {
            // 1. Cek Kuota
            if ($user->current_annual_leave_quota < $totalDays) {
                return back()->withErrors(['leave_type_id' => 'Sisa kuota cuti tahunan Anda tidak mencukupi (Sisa: ' . $user->current_annual_leave_quota . ' hari).'])->withInput();
            }
            // 2. Cek Aturan H-3
            // Tanggal mulai harus minimal 3 hari dari hari ini
            if ($startDate->diffInDays(now(), false) > -3) {
                 return back()->withErrors(['start_date' => 'Pengajuan Cuti Tahunan minimal H-3 sebelum tanggal mulai.'])->withInput();
            }
        }

        // C. Logika Khusus: CUTI SAKIT
        $filePath = null;
        if ($leaveType->name === 'Cuti Sakit') {
            // 1. Wajib Upload Surat Dokter
            if (!$request->hasFile('medical_certificate')) {
                return back()->withErrors(['medical_certificate' => 'Surat keterangan dokter wajib diunggah untuk Cuti Sakit.'])->withInput();
            }
            // 2. Maksimal pengajuan H+3 setelah sakit
            // Jika hari ini tanggal 10, sakit mulai tanggal 5 (selisih 5 hari), maka ditolak.
            // diffInDays return positif jika startDate di masa lalu
            if (now()->diffInDays($startDate) > 3 && $startDate->isPast()) {
                 return back()->withErrors(['start_date' => 'Pengajuan Cuti Sakit maksimal dilakukan 3 hari setelah tanggal mulai sakit.'])->withInput();
            }

            // Simpan File
            $filePath = $request->file('medical_certificate')->store('medical_certificates', 'public');
        }

        // --- SIMPAN KE DATABASE ---
        
        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending', // Status awal selalu pending
            'medical_certificate_path' => $filePath,
            
            // Simpan informasi tambahan
            'contact_address_during_leave' => $request->address ?? $user->address,
            'emergency_number' => $request->phone_number ?? $user->phone_number,
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * Menampilkan detail pengajuan cuti.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest); // Gunakan Policy (User hanya bisa lihat punya sendiri)
        return view('leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Membatalkan pengajuan cuti (Hanya jika masih Pending).
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        // Gunakan Gate 'cancel-leave-request' yang kita buat di Langkah 3
        if (! \Illuminate\Support\Facades\Gate::allows('cancel-leave-request', $leaveRequest)) {
            return back()->withErrors('Pembatalan gagal. Hanya pengajuan berstatus Pending milik Anda sendiri yang dapat dibatalkan.');
        }

        $leaveRequest->update(['status' => 'cancelled']);

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }
    
    // Metode destroy standar (opsional, jika ingin hapus data fisik)
    public function destroy(LeaveRequest $leaveRequest)
    {
       // Biasanya kita tidak menghapus data fisik (soft delete) atau cukup pakai cancel.
       // Kita gunakan cancel() saja untuk logic bisnis.
       return $this->cancel($leaveRequest);
    }
}