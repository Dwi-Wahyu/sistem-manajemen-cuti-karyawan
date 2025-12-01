<?php

namespace App\Http\Requests;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan user adalah pemilik pengajuan ini (Authorization)
        // Kita ambil parameter route 'leaveRequest' (sesuaikan dengan nama parameter di route Anda)
        $leaveRequest = $this->route('leaveRequest');

        // Authorization tambahan bisa dilakukan di Policy, tapi di sini kita return true
        // asalkan user login.
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            // Pastikan end_date >= start_date
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            // File opsional saat update (nullable)
            'medical_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type_id.required' => 'Jenis cuti wajib dipilih.',
            'leave_type_id.exists' => 'Jenis cuti tidak valid.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'medical_certificate.mimes' => 'Lampiran harus berformat JPG, JPEG, PNG, atau PDF.',
            'medical_certificate.max' => 'Ukuran lampiran maksimal 4 MB.',
        ];
    }

    /**
     * Validasi lanjutan setelah rules standar lolos.
     */
    // app/Http/Requests/UpdateLeaveRequest.php

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Jika ada error validasi dasar, skip logika ini
            if ($validator->errors()->count() > 0) {
                return;
            }

            $user = $this->user();

            // Ambil parameter dengan berbagai kemungkinan nama
            $routeParam = $this->route('leaveRequest')
                ?? $this->route('leave_request')
                ?? $this->route('id');

            // Pastikan kita mendapatkan ID (integer), bukan Object Model
            $leaveRequestId = ($routeParam instanceof \App\Models\LeaveRequest)
                ? $routeParam->id
                : $routeParam;

            // Jika ID tetap tidak ketemu (misal nama parameter route lain lagi), hentikan agar tidak error
            if (!$leaveRequestId) {
                return;
            }
            // -------------------------

            // Parsing Tanggal (Tetap sama)
            try {
                $start = Carbon::parse($this->start_date);
                $end = Carbon::parse($this->end_date);
            } catch (\Exception $e) {
                return;
            }

            // Validasi Hari Kerja (Tetap sama)
            $period = CarbonPeriod::create($start, $end);
            $workDays = 0;
            foreach ($period as $date) {
                if (!$date->isWeekend()) {
                    $workDays++;
                }
            }

            if ($workDays <= 0) {
                $validator->errors()->add('start_date', 'Rentang tanggal tidak memiliki hari kerja (hanya akhir pekan).');
            }

            // Validasi Overlap (Gunakan $leaveRequestId yang sudah aman)
            $hasOverlap = LeaveRequest::where('user_id', $user->id)
                ->where('id', '!=', $leaveRequestId) // <--- GUNAKAN VARIABEL ID BARU
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

            if ($hasOverlap) {
                $validator->errors()->add('start_date', 'Tanggal pengajuan bentrok dengan cuti lain yang sudah ada.');
            }
        });
    }
}
