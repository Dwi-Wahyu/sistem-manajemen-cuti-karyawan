<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Divisi: ') . $division->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Info Divisi --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $division->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $division->description ?? 'Tidak ada deskripsi.' }}</p>
                        <div class="mt-4">
                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase">Ketua Divisi:</span>
                            <span class="ml-2 text-gray-900 dark:text-gray-200 font-medium">{{ $division->head->name ?? 'Belum ditentukan' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.divisions.edit', $division) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">Edit Info</a>
                </div>
            </div>

            {{-- Manajemen Anggota --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Anggota Tim ({{ $division->members->count() }})</h3>

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form Tambah Anggota --}}
                <div class="mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <form action="{{ route('admin.divisions.addMember', $division) }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        <div class="flex-grow">
                            <x-input-label for="user_id" :value="__('Tambahkan Karyawan Baru ke Tim Ini')" class="dark:text-gray-300" />
                            <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm" required>
                                <option value="">-- Pilih Karyawan (Tanpa Divisi) --</option>
                                @foreach ($availableEmployees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <x-primary-button>{{ __('Tambah') }}</x-primary-button>
                    </form>
                    @if($availableEmployees->isEmpty())
                        <p class="text-xs text-red-500 dark:text-red-400 mt-2">* Tidak ada karyawan 'free agent' (belum punya divisi) yang tersedia.</p>
                    @endif
                </div>

                {{-- Tabel Anggota --}}
                <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peran</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($division->members as $member)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if($division->head_user_id == $member->id)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">Ketua</span>
                                        @else
                                            Anggota
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        @if($division->head_user_id !== $member->id)
                                            <form action="{{ route('admin.divisions.removeMember', ['division' => $division->id, 'user' => $member->id]) }}" method="POST" onsubmit="return confirm('Keluarkan {{ $member->name }} dari divisi ini?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">Keluarkan</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-xs italic">Ketua (Ganti via Edit)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($division->members->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 italic">Belum ada anggota di divisi ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>