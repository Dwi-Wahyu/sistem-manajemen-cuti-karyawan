<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard HRD') }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Monitoring & Persetujuan
            </h3>

            {{-- Metrik Utama HRD --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- Pending Final Approval --}}
                <a href="{{ route('approvals.index') }}" class="bg-red-50 dark:bg-red-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-red-100 dark:hover:bg-red-900/30 transition cursor-pointer border border-red-200 dark:border-red-700">
                    <div>
                        <div class="text-sm font-medium text-red-600 dark:text-red-300">Pending Final Approval</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['pending_final'] }}</div>
                    </div>
                    <!-- <div class="p-3 bg-red-200 dark:bg-red-800 bg-opacity-75 text-red-700 dark:text-red-200 rounded-full">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div> -->
                </a>

                {{-- Total Pengajuan Bulan Ini --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 dark:border-indigo-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Pengajuan Bulan Ini</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['total_submissions_month'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Permintaan</span>
                    </div>
                </div>

                {{-- Sedang Cuti Bulan Ini --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 dark:border-green-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Cuti Bulan Ini</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['on_leave_this_month'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Karyawan</span>
                    </div>
                </div>
            </div>

            {{-- Daftar Divisi --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Daftar Divisi</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($data['list_divisions'] as $division)
                    <a href="{{ route('admin.divisions.show', $division->id) }}" class="block p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $division->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ketua: {{ $division->head->name ?? 'N/A' }}</p>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Status Terakhir HRD --}}
            <x-last-request-status :data="$data" />
        </div>
    </div>
</x-app-layout>