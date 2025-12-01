<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between w-full items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{-- Logika untuk menampilkan judul dinamis --}}
                @if (isset($currentRoleEnum))
                {{ __('Manajemen') }} {{ $currentRoleEnum->title() }}
                @else
                {{ __('Manajemen Semua Pengguna') }}
                @endif
            </h2>

            {{-- Tombol Tambah Dinamis --}}
            <div class="col-span-full lg:col-span-1 flex items-end justify-start lg:justify-end">
                @can('create', App\Models\User::class)
                @php
                // Ambil nilai 'role' dari route parameter atau query string yang aktif saat ini.
                // Gunakan $currentRoleEnum yang sudah dikirim dari controller jika tersedia.
                $activeRole = $currentRoleEnum->value ?? request('role');

                // Tentukan parameter yang akan dikirim ke route 'create'.
                $params = $activeRole ? ['role' => $activeRole] : [];

                // Tentukan label tombol (jika ada filter, labelnya spesifik)
                $buttonLabel = isset($currentRoleEnum) ? 'Tambah ' . $currentRoleEnum->title() : 'Tambah Pengguna';

                @endphp

                <a href="{{ route('admin.users.create', $params) }}"
                    class="inline-flex gap-1 items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ __($buttonLabel) }}
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Message --}}
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/50 dark:border-green-600 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/50 dark:border-red-600 dark:text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form Filter dan Sortir --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-inner border border-gray-100 dark:border-gray-600">
                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    {{-- Filter Divisi --}}
                    {{-- Tampilkan filter hanya jika user bukan kepala divisi --}}
                    @unless (Auth::user()->isDivisionHead())
                    <div>
                        <x-input-label for="division_id" :value="__('Divisi')" class="dark:text-gray-300" />
                        <select name="division_id" id="division_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm">
                            <option value="">Semua Divisi</option>
                            @foreach ($divisions as $id => $name)
                            <option value="{{ $id }}" {{ request('division_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endunless

                    {{-- Filter Status --}}
                    <div>
                        <x-input-label for="status" :value="__('Status Aktif')" class="dark:text-gray-300" />
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm">
                            <option value="">Semua</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    {{-- Filter Masa Kerja --}}
                    <div>
                        <x-input-label for="masa_kerja" :value="__('Masa Kerja')" class="dark:text-gray-300" />
                        <select name="masa_kerja" id="masa_kerja" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm">
                            <option value="">Semua</option>
                            <option value="ineligible" {{ request('masa_kerja') == 'ineligible' ? 'selected' : '' }}>Masa Kerja < 1 Tahun</option>
                        </select>
                    </div>

                    {{-- Sortir Berdasarkan --}}
                    <div>
                        <x-input-label for="sort_by" :value="__('Sortir')" class="dark:text-gray-300" />
                        <select name="sort_by" id="sort_by" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm">
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                            <option value="join_date" {{ request('sort_by') == 'join_date' ? 'selected' : '' }}>Tgl Gabung</option>
                        </select>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="col-span-full lg:col-span-1 flex items-end">
                        <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto border rounded-lg shadow dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email/Username</th>

                            @unless ($currentRoleEnum)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                            @endunless

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kuota Cuti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->email }}<br>
                                <span class="text-xs text-gray-400 dark:text-gray-500">({{ $user->username }})</span>
                            </td>

                            @unless ($currentRoleEnum)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </td>
                            @endunless

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->division->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->current_annual_leave_quota }} / {{ $user->initial_annual_leave_quota }} hari</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">Edit</a>

                                @can('delete', $user)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}? Penghapusan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Hapus</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links('pagination.custom') }}
            </div>
        </div>
    </div>
</x-app-layout>