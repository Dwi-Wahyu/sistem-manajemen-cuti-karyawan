<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Administrator') }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Global Overview & System Health
            </h3>

            {{-- Metrik Utama Admin (Grid 4 Kolom) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- Total Karyawan Aktif --}}
                <div class="bg-indigo-50 dark:bg-indigo-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-indigo-200 dark:border-indigo-800">
                    <div class="text-sm font-medium text-indigo-600 dark:text-indigo-300">Karyawan Aktif</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['active_employees'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total: {{ $data['total_employees'] }}</div>
                </div>

                {{-- Total Pengajuan Bulan Ini --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 dark:border-blue-600">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengajuan Bulan Ini</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['total_submissions_month'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Status Apapun</div>
                </div>

                {{-- Pengajuan Pending Approval (Global) --}}
                <a href="{{ route('approvals.index') }}" class="bg-red-50 dark:bg-red-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-red-100 dark:hover:bg-red-900/30 transition cursor-pointer border border-red-200 dark:border-red-700">
                    <div>
                        <div class="text-sm font-medium text-red-600 dark:text-red-300">Pending Approval (Global)</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['global_pending'] }}</div>
                    </div>
                </a>

                {{-- Total Divisi --}}
                <a href="{{ route('admin.divisions.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500 dark:border-purple-600">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Divisi</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['total_divisions'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dapat Dikelola</div>
                </a>
            </div>



            {{-- Daftar Karyawan Belum Eligible --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Karyawan Belum Eligible Cuti Tahunan (< 1 Tahun)
                        </h4>
                        <div class="flex items-center gap-2 text-orange-600 dark:text-orange-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.238 0 1.954-1.42 1.254-2.433l-6.928-11.964c-.7-.912-2.188-.912-2.887 0l-6.928 11.964c-.7.913.016 2.433 1.254 2.433z"></path>
                            </svg>
                            <span class="text-2xl font-bold">{{ $data['ineligible_count'] }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Karyawan</span>
                        </div>
            </div>

            {{-- Status Terakhir Admin --}}
            <x-last-request-status :data="$data" />

            {{-- LOG AKTIVITAS TERAKHIR --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Aktivitas Sistem Terakhir
                    </h3>
                    <a href="{{ route('admin.activity-logs.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelaku</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($data['latest_logs'] as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                {{-- Waktu --}}
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    {{ $log->created_at->diffForHumans() }}
                                </td>

                                {{-- Pelaku --}}
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $log->user->name ?? 'System' }}
                                    </div>
                                </td>

                                {{-- Badge Aksi --}}
                                <td class="px-4 py-2 whitespace-nowrap">
                                    @php
                                    $color = match($log->action) {
                                    'created', 'approved', 'restored' => 'green',
                                    'updated' => 'blue',
                                    'deleted', 'rejected', 'cancelled' => 'red',
                                    default => 'gray'
                                    };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900 dark:text-{{ $color }}-300">
                                        {{ strtoupper($log->action) }}
                                    </span>
                                </td>

                                {{-- Deskripsi --}}
                                <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">
                                    <span class="truncate block max-w-xs" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada aktivitas tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>