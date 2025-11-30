<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Persetujuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/50 dark:border-green-600 dark:text-green-300 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
            @endif

            {{--
                FILTER SECTION
            --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <form method="GET" action="{{ route('approvals.index') }}">
                    {{-- Grid Layout: Responsif --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                        {{-- Search Nama --}}
                        <div class="col-span-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Nama</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama karyawan..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        {{-- Filter Jenis Cuti --}}
                        <div class="col-span-1">
                            <label for="type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Cuti</label>
                            <select name="type_id" id="type_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Jenis</option>
                                @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Tanggal (Dari - Sampai) --}}
                        <div class="col-span-1 md:col-span-2 grid grid-cols-2 gap-2">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tgl</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tgl</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        {{-- Filter Divisi (Khusus Admin/HRD) --}}
                        @if(Auth::user()->isHrd() || Auth::user()->isAdmin())
                        <div class="col-span-1">
                            <label for="division_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Divisi</label>
                            <select name="division_id" id="division_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Divisi</option>
                                @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        {{-- Spacer agar tombol tetap di kanan jika bukan HRD --}}
                        <div class="hidden lg:block"></div>
                        @endif



                        @if(request()->hasAny(['search', 'type_id', 'division_id', 'date_from', 'date_to']))
                        <a href="{{ route('approvals.index') }}" class="inline-flex justify-center self-end rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition">
                            Reset Filter
                        </a>
                        @endif
                        <button type="submit" class="inline-flex justify-center rounded-md self-end border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            @if($pendingRequests->isEmpty())
            <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                <p class="text-lg">
                    @if(request()->hasAny(['search', 'type_id', 'division_id']))
                    Tidak ditemukan data yang cocok dengan filter Anda.
                    @else
                    Tidak ada pengajuan cuti yang perlu ditinjau saat ini.
                    @endif
                </p>
            </div>
            @else

            {{-- GRID LAYOUT --}}
            <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($pendingRequests as $req)

                <a href="{{ route('approvals.show', $req->id) }}"
                    class="block group border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-lg hover:border-indigo-300 dark:hover:border-indigo-500 transition bg-white dark:bg-gray-800 relative overflow-hidden">



                    {{-- Header Card: User --}}
                    <div class="flex items-center gap-3 mb-3 pr-6">
                        <div class="h-10 w-10 shrink-0 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-lg">
                            {{ substr($req->user->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 leading-tight truncate">{{ $req->user->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ ucfirst(str_replace('_', ' ', $req->user->role)) }}</p>
                        </div>
                    </div>

                    {{-- Body Card: Info Penting --}}
                    <div class="space-y-1 text-sm border-t border-gray-100 dark:border-gray-700 pt-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Jenis:</span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ $req->type->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Tanggal:</span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ $req->start_date->format('d M') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Durasi:</span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ $req->total_days }} Hari</span>
                        </div>
                    </div>

                    @if($req->status == 'approved_by_leader')
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-blue-500"></div>
                    @endif
                </a>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center flex-col">
                {{ $pendingRequests->links('pagination.custom') }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>