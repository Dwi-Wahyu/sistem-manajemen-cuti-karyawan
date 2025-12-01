<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Permohonan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Info --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Mengedit pengajuan ini akan mereset status persetujuan menjadi <strong>Menunggu Konfirmasi (Pending)</strong>.
                        </p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 text-white border-red-400 p-4 mb-6
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class=" bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">

                <form method="POST" action="{{ route('leave-requests.update', $leaveRequest->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Method Spoofing --}}

                    <div class="space-y-6">

                        {{-- Jenis Cuti --}}
                        <div>
                            <x-input-label for="leave_type_id" :value="__('Jenis Cuti')" />
                            <select id="leave_type_id" name="leave_type_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required onchange="toggleAttachment(this)">
                                @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}" data-name="{{ $type->name }}"
                                    {{ old('leave_type_id', $leaveRequest->leave_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Tanggal Mulai --}}
                            <div>
                                <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                                    :value="old('start_date', $leaveRequest->start_date->format('Y-m-d'))" required />
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div>
                                <x-input-label for="end_date" :value="__('Tanggal Selesai')" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                                    :value="old('end_date', $leaveRequest->end_date->format('Y-m-d'))" required />
                            </div>
                        </div>

                        {{-- Alasan --}}
                        <div>
                            <x-input-label for="reason" :value="__('Alasan Cuti')" />
                            <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('reason', $leaveRequest->reason) }}</textarea>
                        </div>

                        {{-- Upload Surat Dokter --}}
                        <div id="attachment-container" class="{{ str_contains($leaveRequest->type->name, 'Sakit') ? '' : 'hidden' }} p-4 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">
                            <x-input-label for="medical_certificate" :value="__('Upload Surat Dokter (Isi jika ingin mengganti)')" />

                            @if($leaveRequest->medical_certificate_path)
                            <div class="mb-2 text-sm text-indigo-600">
                                <a href="{{ Storage::url($leaveRequest->medical_certificate_path) }}" target="_blank" class="hover:underline">Lihat File Saat Ini</a>
                            </div>
                            @endif

                            <input id="medical_certificate" name="medical_certificate" type="file" accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800 transition-colors" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, PDF. Maks: 4MB.</p>
                        </div>

                        <div class="flex items-center gap-4 justify-end">
                            <a href="{{ route('leave-requests.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                            <x-primary-button type="submit">{{ __('Simpan Perubahan') }}</x-primary-button>
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

            if (typeName && typeName.includes('Sakit')) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>