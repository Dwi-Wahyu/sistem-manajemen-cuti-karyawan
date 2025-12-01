<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info Divisi & Leader --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Divisi Anda:</p>
                        <p class="font-bold text-lg text-indigo-600 dark:text-indigo-400">{{ $data['my_division_name'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Ketua Divisi:</p>
                        <p class="font-medium text-gray-900 dark:text-white text-lg">{{ $data['my_leader_name'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Metrik Utama Karyawan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Sisa Cuti Tahunan --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 dark:border-blue-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Sisa Kuota Cuti Tahunan</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['my_quota'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Hari</span>
                    </div>
                </div>

                {{-- Jumlah Cuti Sakit yang Diajukan (Total Hari) --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500 dark:border-yellow-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Hari Cuti Sakit (Disetujui)</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['my_total_sick_days'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Hari</span>
                    </div>
                </div>

                {{-- Total Semua Pengajuan Cuti --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 dark:border-green-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Pengajuan Cuti</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['my_total_submissions'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Riwayat</span>
                    </div>
                </div>
            </div>

            {{-- Status Terakhir --}}
            <x-last-request-status :data="$data" />
        </div>
    </div>
</x-app-layout>