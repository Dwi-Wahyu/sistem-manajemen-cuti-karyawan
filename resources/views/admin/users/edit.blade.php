<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pengguna: ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        
                        {{-- Nama Lengkap --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="dark:text-gray-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        {{-- Username --}}
                        <div>
                            <x-input-label for="username" :value="__('Username')" class="dark:text-gray-300" />
                            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" :value="old('username', $user->username)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" :value="old('email', $user->email)" required autocomplete="email" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Role --}}
                            <div>
                                <x-input-label for="role" :value="__('Role / Peran')" class="dark:text-gray-300" />
                                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm" required>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r }}" {{ old('role', $user->role) == $r ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $r)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role')" />
                            </div>

                            {{-- Status Akun --}}
                            <div>
                                <x-input-label for="is_active" :value="__('Status Akun')" class="dark:text-gray-300" />
                                <select id="is_active" name="is_active" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm" required>
                                    <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif / Suspend</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                            </div>
                        </div>

                        {{-- Tanggal Bergabung --}}
                        <div>
                            <x-input-label for="join_date" :value="__('Tanggal Bergabung')" class="dark:text-gray-300" />
                            <x-text-input id="join_date" name="join_date" type="date" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" :value="old('join_date', $user->join_date ? $user->join_date->format('Y-m-d') : '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('join_date')" />
                        </div>

                        {{-- Section Manajemen Kuota Cuti --}}
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-md border border-gray-200 dark:border-gray-600 mt-6">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Manajemen Kuota Cuti') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Kuota Cuti Awal --}}
                                <div>
                                    <x-input-label for="initial_annual_leave_quota" :value="__('Total Kuota Tahunan Awal')" class="dark:text-gray-300" />
                                    <x-text-input id="initial_annual_leave_quota" name="initial_annual_leave_quota" type="number" min="0" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" :value="old('initial_annual_leave_quota', $user->initial_annual_leave_quota)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('initial_annual_leave_quota')" />
                                </div>

                                {{-- Kuota Cuti Saat Ini --}}
                                <div>
                                    <x-input-label for="current_annual_leave_quota" :value="__('Sisa Kuota Saat Ini')" class="dark:text-gray-300" />
                                    <x-text-input id="current_annual_leave_quota" name="current_annual_leave_quota" type="number" min="0" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" :value="old('current_annual_leave_quota', $user->current_annual_leave_quota)" required />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah manual jika ada penyesuaian khusus.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('current_annual_leave_quota')" />
                                </div>
                            </div>
                        </div>

                        {{-- Section Ganti Password --}}
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-md border border-yellow-200 dark:border-yellow-700 mt-6">
                            <h3 class="font-semibold text-yellow-800 dark:text-yellow-400 mb-2">{{ __('Ganti Password (Opsional)') }}</h3>
                            <p class="text-sm text-yellow-600 dark:text-yellow-300/80 mb-4">Biarkan kosong jika tidak ingin mengubah password pengguna.</p>

                            {{-- Password --}}
                            <div>
                                <x-input-label for="password" :value="__('Password Baru')" class="dark:text-gray-300" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" autocomplete="new-password" placeholder="********" />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>
                        </div>

                        {{-- Footer Action --}}
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <x-primary-button>{{ __('Perbarui Data') }}</x-primary-button>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>