<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'medical_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type_id.required' => 'Jenis cuti wajib dipilih.',
            'leave_type_id.exists' => 'Jenis cuti tidak valid.',

            'start_date.required' => 'Tanggal mulai cuti wajib diisi.',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid.',

            'end_date.required' => 'Tanggal selesai cuti wajib diisi.',
            'end_date.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',

            'reason.required' => 'Alasan cuti wajib diisi.',
            'reason.max' => 'Alasan cuti maksimal 1000 karakter.',

            'medical_certificate.file' => 'Lampiran harus berupa file.',
            'medical_certificate.mimes' => 'Lampiran harus berformat JPG, JPEG, PNG, atau PDF.',
            'medical_certificate.max' => 'Ukuran lampiran maksimal 2 MB.',
        ];
    }

    /**
     * Menambahkan validasi custom setelah rules standar dijalankan.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('start_date') || $validator->errors()->has('end_date')) {
                return;
            }

            try {
                $start = Carbon::parse($this->start_date);
                $end = Carbon::parse($this->end_date);
            } catch (\Exception $e) {
                $validator->errors()->add('start_date', 'Format tanggal tidak valid.');
                return;
            }

            $period = CarbonPeriod::create($start, $end);
            $days = 0;

            foreach ($period as $date) {
                if (!$date->isWeekend()) $days++;
            }

            if ($days <= 0) {
                $validator->errors()->add('start_date', 'Tanggal tidak boleh hanya berisi akhir pekan.');
            }
        });
    }
}
