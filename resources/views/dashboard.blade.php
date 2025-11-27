<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- === BAGIAN 1: STATISTIK SAYA (Untuk Semua User) === --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Sisa Cuti -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 dark:border-blue-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Sisa Cuti Tahunan</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['my_quota'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Hari</span>
                    </div>
                </div>

                <!-- Menunggu Persetujuan -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500 dark:border-yellow-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Pengajuan Pending</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['my_pending'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Permintaan</span>
                    </div>
                </div>

                <!-- Total Disetujui -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 dark:border-green-600">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Disetujui</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['my_approved'] }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Riwayat</span>
                    </div>
                </div>
            </div>

            {{-- === BAGIAN 2: AREA ADMIN & HRD === --}}
            @if(Auth::user()->isAdmin() || Auth::user()->isHrd())
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Panel Admin & HRD</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        
                        <!-- Total Karyawan -->
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-indigo-100 dark:border-indigo-800">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-indigo-200 dark:bg-indigo-800 bg-opacity-75 text-indigo-700 dark:text-indigo-200">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-indigo-600 dark:text-indigo-300 truncate">Total Karyawan</div>
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $data['total_employees'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Divisi -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-purple-100 dark:border-purple-800">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-200 dark:bg-purple-800 bg-opacity-75 text-purple-700 dark:text-purple-200">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-purple-600 dark:text-purple-300 truncate">Total Divisi</div>
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $data['total_divisions'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Perlu Approval -->
                        <a href="{{ route('approvals.index') }}" class="bg-red-50 dark:bg-red-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-red-100 dark:hover:bg-red-900/30 transition cursor-pointer border border-red-100 dark:border-red-800">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-200 dark:bg-red-800 bg-opacity-75 text-red-700 dark:text-red-200">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-red-600 dark:text-red-300 truncate">Perlu Persetujuan</div>
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $data['global_pending'] }}</div>
                                </div>
                            </div>
                        </a>

                        <!-- Sedang Cuti Hari Ini -->
                        <div class="bg-green-50 dark:bg-green-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-green-100 dark:border-green-800">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-200 dark:bg-green-800 bg-opacity-75 text-green-700 dark:text-green-200">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-green-600 dark:text-green-300 truncate">Sedang Cuti Hari Ini</div>
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $data['on_leave_today'] }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            {{-- === BAGIAN 3: AREA KETUA DIVISI === --}}
            @if(Auth::user()->isDivisionHead() && Auth::user()->ledDivision)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Divisi: {{ Auth::user()->ledDivision->name }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Pending Approval Tim -->
                        <a href="{{ route('approvals.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition border border-gray-200 dark:border-gray-700">
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Verifikasi Anda</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['team_pending'] }}</div>
                            </div>
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-300 rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </a>

                        <!-- Total Anggota -->
                        <a href="{{ route('admin.divisions.show', Auth::user()->ledDivision->id) }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition border border-gray-200 dark:border-gray-700">
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Anggota Tim</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['team_count'] }}</div>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </a>

                    </div>
                </div>
            @endif

            {{-- === BAGIAN 4: STATUS TERAKHIR (Semua User) === --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pengajuan Terakhir Anda</h3>
                @if($data['my_last_request'])
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pengajuan</div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $data['my_last_request']->created_at->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Jenis</div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $data['my_last_request']->type->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                            <div class="mt-1">
                                @php
                                    $status = $data['my_last_request']->status;
                                    $color = match($status) {
                                        'approved' => 'green',
                                        'rejected' => 'red',
                                        'cancelled' => 'gray',
                                        default => 'yellow'
                                    };
                                    $label = match($status) {
                                        'approved_by_leader' => 'Menunggu HRD',
                                        'pending' => 'Menunggu Atasan',
                                        default => ucfirst($status)
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900 dark:text-{{ $color }}-200">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('leave-requests.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">Lihat Detail &rarr;</a>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 italic">Belum ada riwayat pengajuan cuti.</p>
                    <a href="{{ route('leave-requests.create') }}" class="mt-3 inline-block text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ajukan Sekarang &rarr;</a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>