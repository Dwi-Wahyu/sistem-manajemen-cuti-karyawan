<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Aktivitas Sistem') }}
        </h2>
    </x-slot>

    <div class="py-9" x-data="{ detailOpen: false, selectedLog: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter Section --}}
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid md:grid-cols-3 mb-6 gap-4 grid-cols-1">

                {{-- Filter User --}}
                <div class="w-full">
                    <select name="user_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                        <option value="">Semua User</option>
                        @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Action --}}
                <div class="w-full">
                    <select name="action" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                        <option value="">Semua Aksi</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('action') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Reset Button --}}
                @if(request()->hasAny(['user_id', 'action']))
                <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none">
                    Reset
                </a>
                @endif
            </form>

            {{-- Table Logs --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelaku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->created_at->format('d M Y H:i:s') }}
                                    <span class="block text-xs">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $log->user->name ?? 'System/Deleted User' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $log->user->role->title() ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $color = match($log->action) {
                                    'created', 'approved', 'restored' => 'green',
                                    'updated' => 'blue',
                                    'deleted', 'rejected', 'cancelled' => 'red',
                                    default => 'gray'
                                    };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900 dark:text-{{ $color }}-300 border border-{{ $color }}-200">
                                        {{ strtoupper($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    <p class="truncate max-w-xs" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </p>
                                    <span class="text-xs text-gray-400">
                                        Ref: {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($log->properties)
                                    <button @click="detailOpen = true; selectedLog = {{ json_encode($log) }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        Lihat Data
                                    </button>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada aktivitas tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL LOG --}}
        <div x-show="detailOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" @click="detailOpen = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all">

                    {{-- Header Modal --}}
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                            Detail Perubahan Data
                        </h3>
                        <button @click="detailOpen = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Body Modal --}}
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">

                        {{-- Info Metadata --}}
                        <div class="mb-4">
                            <div class="mb-2">
                                <span class="block text-gray-500 dark:text-gray-400">IP Address</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="selectedLog.ip_address || '-'"></span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">User Agent</span>
                                <span class="font-medium text-gray-900 dark:text-white break-all"
                                    x-text="selectedLog.user_agent || '-'">
                                </span>
                            </div>
                        </div>

                        {{-- Tampilan Perubahan (Old vs New) --}}
                        <template x-if="selectedLog.properties">
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-800">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Field</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-red-500 uppercase">Lama (Old)</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-green-500 uppercase">Baru (New)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 font-mono text-xs">
                                        {{--
                                            Logic: 
                                            1. Jika 'updated', loop attributes dan bandingkan dengan old.
                                            2. Jika 'created', old kosong, attributes isi.
                                            3. Jika 'deleted', old isi, attributes kosong.
                                        --}}
                                        <template x-for="(value, key) in (selectedLog.properties.attributes || selectedLog.properties.old)" :key="key">
                                            <tr>
                                                <td class="px-4 py-2 font-semibold text-gray-700 dark:text-gray-300" x-text="key"></td>

                                                {{-- Nilai Lama --}}
                                                <td class="px-4 py-2 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/10">
                                                    <span x-text="selectedLog.properties.old ? selectedLog.properties.old[key] : '-'"></span>
                                                </td>

                                                {{-- Nilai Baru --}}
                                                <td class="px-4 py-2 text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/10">
                                                    <span x-text="selectedLog.properties.attributes ? selectedLog.properties.attributes[key] : '-'"></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-3 flex justify-end">
                        <button type="button" @click="detailOpen = false" class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>