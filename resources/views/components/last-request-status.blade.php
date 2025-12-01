@props(['data'])
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6 border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pengajuan Terakhir Anda</h3>
    @if(isset($data['my_last_request']) && $data['my_last_request'])
    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg gap-4">
        <div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pengajuan</div>
            <div class="font-medium text-gray-900 dark:text-white">{{ $data['my_last_request']->created_at->format('d M Y') }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Jenis</div>
            <div class="font-medium text-gray-900 dark:text-white">{{ $data['my_last_request']->type->name }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
            <div class="mt-1">
                <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $data['my_last_request']->status->badgeClasses() }}">
                    {{ $data['my_last_request']->status->label() }}
                </span>
            </div>
        </div>
        <div>
            <a href="{{ route('leave-requests.show', $data['my_last_request']->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">Lihat Detail &rarr;</a>
        </div>
    </div>
    @else
    <p class="text-gray-500 dark:text-gray-400 italic">Belum ada riwayat pengajuan cuti.</p>
    <a href="{{ route('leave-requests.create') }}" class="mt-3 inline-block text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ajukan Sekarang &rarr;</a>
    @endif
</div>