<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Divisi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                
                <form method="POST" action="{{ route('admin.divisions.store') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        {{-- Nama Divisi --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Divisi')" class="dark:text-gray-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" class="dark:text-gray-300" />
                            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm" rows="3">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        {{-- Ketua Divisi --}}
                        <div>
                            <x-input-label for="head_user_id" :value="__('Ketua Divisi (Opsional)')" class="dark:text-gray-300" />
                            <select id="head_user_id" name="head_user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm">
                                <option value="">-- Pilih Ketua Divisi --</option>
                                @foreach ($availableHeads as $head)
                                    <option value="{{ $head->id }}" {{ old('head_user_id') == $head->id ? 'selected' : '' }}>
                                        {{ $head->name }} ({{ $head->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hanya menampilkan user dengan role 'Ketua Divisi' yang belum memimpin tim.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('head_user_id')" />
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                            <a href="{{ route('admin.divisions.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>