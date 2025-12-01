<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Hari Libur ') . $holiday->name }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('holidays.update', $holiday) }}">
                    @csrf
                    @method('PUT')

                    @include('holidays._form', ['holiday' => $holiday])

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('holidays.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition mr-4">Batal</a>
                        <x-primary-button>
                            {{ __('Update Hari Libur') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>