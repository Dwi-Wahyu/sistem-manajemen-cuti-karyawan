<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Ketua Divisi') }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Tim {{ $data['my_division_name'] }}
            </h3>

            {{-- Metrik Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Total Pengajuan Cuti Masuk --}}
                <a href="{{ route('approvals.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer border border-yellow-200 dark:border-yellow-700">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Verifikasi Anda</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['team_pending'] }}</div>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-300 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </a>

                {{-- Daftar Anggota Divisi --}}
                <a href="{{ route('admin.divisions.show', Auth::user()->ledDivision->id) }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition border border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Anggota Divisi</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['team_count'] }}</div>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </a>

                {{-- Sedang Cuti Minggu Ini --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between border border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Cuti Minggu Ini (Approved)</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['team_on_leave_this_week'] }}</div>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Status Terakhir Leader --}}
            <x-last-request-status :data="$data" />
        </div>
    </div>
</x-app-layout>