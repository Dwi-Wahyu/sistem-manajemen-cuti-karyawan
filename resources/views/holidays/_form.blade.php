@props(['holiday'])

<div class="space-y-4">

    {{-- Field Nama Hari Libur --}}
    <div>
        <x-input-label for="name" :value="__('Nama Hari Libur')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
            :value="old('name', optional($holiday)->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    {{-- Field Tanggal --}}
    <div>
        <x-input-label for="date" :value="__('Tanggal')" />
        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
            :value="old('date', optional($holiday)->date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('date')" />
    </div>

    {{-- Checkbox Cuti Bersama --}}
    <div class="block">
        <label for="is_joint_leave" class="inline-flex items-center">
            <input id="is_joint_leave" name="is_joint_leave" type="checkbox" value="1"
                class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-900"
                {{ old('is_joint_leave', optional($holiday)->is_joint_leave) ? 'checked' : '' }}>
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Ini adalah Cuti Bersama (Joint Leave)') }}</span>
        </label>
        <p class="text-xs text-gray-500 dark:text-gray-600 mt-1">Cuti bersama biasanya mengurangi jatah cuti tahunan.</p>
    </div>

</div>