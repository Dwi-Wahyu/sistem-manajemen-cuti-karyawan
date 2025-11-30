<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pengajuan Cuti Saya') }}
        </h2>
    </x-slot>

    {{-- Definisikan x-data untuk mengontrol modal --}}
    <div class="py-9" x-data="{ cancelModalOpen: false, cancelUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header: Sisa Kuota & Tombol Tambah --}}
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <div class="bg-white dark:bg-gray-800 px-6 py-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex items-center">
                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Sisa Cuti Tahunan:</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 text-2xl ml-3">{{ Auth::user()->current_annual_leave_quota }}</span>
                    <span class="text-gray-400 dark:text-gray-500 text-sm ml-1">Hari</span>
                </div>
                <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Cuti Baru
                </a>
            </div>

            {{-- Alert Success --}}
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/50 dark:border-green-600 dark:text-green-300 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
            @endif

            {{-- Tabel Riwayat --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Pengajuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($leaveRequests as $leave)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $leave->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $leave->type->name }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $leave->start_date->format('d/m') }} - {{ $leave->end_date->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                                    {{ $leave->total_days }} Hari
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $leave->status->badgeClasses() }}">
                                        {{ $leave->status->label() }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-3">

                                        {{-- 1. TOMBOL DETAIL (Selalu Tampil) --}}
                                        <a href="{{ route('leave-requests.show', $leave->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors flex items-center gap-1 hover:underline text-xs">

                                            Detail
                                        </a>

                                        {{-- 2. AKSI LAIN (Kondisional) --}}
                                        @if ($leave->status === \App\Enums\LeaveRequestStatus::Pending)
                                        {{-- Tombol Trigger Modal Batal --}}
                                        <button type="button"
                                            @click="cancelModalOpen = true; cancelUrl = '{{ route('leave-requests.cancel', $leave->id) }}'"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors flex items-center gap-1 hover:underline text-xs ml-2">

                                            Batal
                                        </button>
                                        @elseif($leave->status === \App\Enums\LeaveRequestStatus::Rejected)
                                        <div class="group relative inline-block text-red-500 cursor-help text-xs">
                                            <span class="border-b border-dotted border-red-500">Alasan</span>
                                            <div class="absolute bottom-full right-0 mb-2 w-56 p-3 bg-gray-900 text-white text-xs rounded-lg hidden group-hover:block z-20 shadow-xl border border-gray-700">
                                                <p class="font-bold mb-1 text-gray-300">Catatan Penolakan:</p>
                                                "{{ $leave->hrd_rejection_note ?? $leave->leader_rejection_note ?? 'Tidak ada catatan.' }}"
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p>Belum ada riwayat pengajuan cuti.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-4 pb-4">
                    {{ $leaveRequests->links() }}
                </div>
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
                                        Apakah Anda yakin ingin membatalkan pengajuan cuti ini? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                        <form :action="cancelUrl" method="POST">
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