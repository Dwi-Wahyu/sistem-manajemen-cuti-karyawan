<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('leave-requests.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Pengajuan Cuti') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-9" x-data="{ cancelModalOpen: false }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Message --}}
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/50 dark:border-green-600 dark:text-green-300 px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
            @endif

            {{-- Kartu Detail Utama --}}
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-700">

                {{-- Header Detail --}}
                <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            Informasi Pengajuan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Diajukan pada {{ $leaveRequest->created_at->format('d F Y, H:i') }}
                        </p>
                    </div>

                    {{-- Badge Status --}}
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $leaveRequest->status->badgeClasses() }}">
                        {{ $leaveRequest->status->label() }}
                    </span>
                </div>

                {{-- Body Detail --}}
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Cuti</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 font-semibold">
                                {{ $leaveRequest->type->name }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Durasi</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 font-semibold">
                                {{ $leaveRequest->total_days }} Hari Kerja
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                {{ $leaveRequest->start_date->format('l, d F Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                {{ $leaveRequest->end_date->format('l, d F Y') }}
                            </dd>
                        </div>

                        {{-- Alamat & Kontak Darurat (Opsional) --}}
                        @if($leaveRequest->contact_address_during_leave)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Selama Cuti</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                {{ $leaveRequest->contact_address_during_leave }}
                            </dd>
                        </div>
                        @endif

                        @if($leaveRequest->emergency_number)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Darurat</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                {{ $leaveRequest->emergency_number }}
                            </dd>
                        </div>
                        @endif

                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Alasan Cuti</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-100 dark:border-gray-700 italic">
                                "{{ $leaveRequest->reason }}"
                            </dd>
                        </div>

                        {{-- Lampiran --}}
                        @if($leaveRequest->medical_certificate_path)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Lampiran</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                <a href="{{ Storage::url($leaveRequest->medical_certificate_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    Lihat Lampiran
                                </a>
                            </dd>
                        </div>
                        @endif

                        {{-- Tampilkan Alasan Penolakan Jika Ditolak --}}
                        @if($leaveRequest->status === \App\Enums\LeaveRequestStatus::Rejected)
                        <div class="md:col-span-2 mt-4">
                            <dt class="text-sm font-medium text-red-500 dark:text-red-400 mb-2">Ditolak Dengan Alasan:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-800">
                                <p class="font-medium text-red-800 dark:text-red-300 mb-1">
                                    Oleh:
                                    @if($leaveRequest->hrd_rejection_note) HRD @else Atasan Langsung @endif
                                </p>
                                <p class="italic text-gray-700 dark:text-gray-300">
                                    "{{ $leaveRequest->hrd_rejection_note ?? $leaveRequest->leader_rejection_note }}"
                                </p>
                            </dd>
                        </div>
                        @endif

                    </dl>
                </div>

                {{-- Footer: Tombol Aksi (Hanya jika Pending) --}}
                @if($leaveRequest->status === \App\Enums\LeaveRequestStatus::Pending)
                <div class="px-4 py-4 sm:px-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button type="button" @click="cancelModalOpen = true" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batalkan Pengajuan
                    </button>
                </div>
                @endif
            </div>
        </div>

        {{-- Modal Konfirmasi Pembatalan --}}
        <div x-show="cancelModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" @click="cancelModalOpen = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:max-w-sm w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Batalkan Pengajuan?</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Apakah Anda yakin ingin membatalkan pengajuan cuti ini? Tindakan ini tidak dapat dikembalikan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                        <form action="{{ route('leave-requests.cancel', $leaveRequest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">
                                Ya, Batalkan
                            </button>
                        </form>
                        <button type="button" @click="cancelModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto transition">
                            Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>