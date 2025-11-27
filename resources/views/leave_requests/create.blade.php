<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajukan Permohonan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Info Kuota --}}
            <div class="bg-blue-50 dark:bg-blue-900/50 border-l-4 border-blue-400 dark:border-blue-500 p-4 mb-6 shadow-sm rounded-r">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-100">
                            Sisa Kuota Cuti Tahunan Anda: <span class="font-bold text-blue-800 dark:text-white">{{ $user->current_annual_leave_quota }} Hari</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-500 text-red-700 dark:text-red-100 px-4 py-3 rounded relative">
                        <strong class="font-bold">Ada kesalahan!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-6">
                        
                        {{-- Jenis Cuti --}}
                        <div>
                            <x-input-label for="leave_type_id" :value="__('Jenis Cuti')" />
                            <select id="leave_type_id" name="leave_type_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm" required onchange="toggleAttachment(this)">
                                <option value="">-- Pilih Jenis Cuti --</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}" data-name="{{ $type->name }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} 
                                        {{ $type->name == 'Cuti Tahunan' ? '(Mengurangi Kuota)' : '(Perlu Dokumen)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Tanggal Mulai --}}
                            <div>
                                <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date')" required />
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div>
                                <x-input-label for="end_date" :value="__('Tanggal Selesai')" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date')" required />
                            </div>
                        </div>

                        {{-- Alasan --}}
                        <div>
                            <x-input-label for="reason" :value="__('Alasan Cuti')" />
                            <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm" required placeholder="Jelaskan keperluan cuti Anda...">{{ old('reason') }}</textarea>
                        </div>

                        {{-- Upload Surat Dokter (Hidden by default, shown via JS) --}}
                        <div id="attachment-container" class="hidden p-4 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="medical_certificate" :value="__('Upload Surat Dokter (Wajib untuk Cuti Sakit)')" />
                            <input id="medical_certificate" name="medical_certificate" type="file" accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800 transition-colors" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, PDF. Maks: 2MB.</p>
                        </div>

                        {{-- Informasi Kontak (Opsional) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <x-input-label for="address" :value="__('Alamat Selama Cuti (Opsional)')" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" placeholder="Alamat saat ini" />
                            </div>
                            <div>
                                <x-input-label for="phone_number" :value="__('Nomor Darurat (Opsional)')" />
                                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" placeholder="08..." />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Ajukan Cuti') }}</x-primary-button>
                            <a href="{{ route('leave-requests.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Batal') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleAttachment(select) {
            const selectedOption = select.options[select.selectedIndex];
            const typeName = selectedOption.getAttribute('data-name');
            const container = document.getElementById('attachment-container');
            
            // Tampilkan upload jika nama cuti mengandung kata 'Sakit'
            if (typeName && typeName.includes('Sakit')) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>