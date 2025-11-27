<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Persetujuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/50 dark:border-green-600 dark:text-green-300 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/50 dark:border-red-600 dark:text-red-300 px-4 py-3 rounded relative mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                @if($pendingRequests->isEmpty())
                    <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                        <p class="text-lg">Tidak ada pengajuan cuti yang perlu ditinjau saat ini.</p>
                        <p class="text-sm">Kerja bagus!</p>
                    </div>
                @else
                    <div class="grid gap-6">
                        @foreach ($pendingRequests as $req)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition bg-white dark:bg-gray-800">
                                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                                    
                                    {{-- Info User & Cuti --}}
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="font-bold text-lg text-gray-800 dark:text-gray-100">{{ $req->user->name }}</span>
                                            <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                                {{ ucfirst(str_replace('_', ' ', $req->user->role)) }}
                                            </span>
                                            @if($req->status == 'approved_by_leader')
                                                <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 font-bold border border-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-800">
                                                    Sudah Verifikasi Leader
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
                                            <p><span class="font-medium dark:text-gray-300">Divisi:</span> {{ $req->user->division->name ?? '-' }}</p>
                                            <p><span class="font-medium dark:text-gray-300">Jenis:</span> {{ $req->type->name }}</p>
                                            <p><span class="font-medium dark:text-gray-300">Tanggal:</span> {{ $req->start_date->format('d M') }} - {{ $req->end_date->format('d M Y') }}</p>
                                            <p><span class="font-medium dark:text-gray-300">Durasi:</span> {{ $req->total_days }} Hari Kerja</p>
                                        </div>

                                        <div class="mt-3 bg-gray-50 dark:bg-gray-700/50 p-3 rounded text-sm italic text-gray-700 dark:text-gray-300 border-l-4 border-indigo-300 dark:border-indigo-500">
                                            "{{ $req->reason }}"
                                        </div>

                                        @if($req->medical_certificate_path)
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($req->medical_certificate_path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    Lihat Surat Dokter
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="flex flex-col gap-2 min-w-[150px]">
                                        {{-- Tombol Approve --}}
                                        <form action="{{ route('approvals.approve', $req) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-500 focus:outline-none focus:ring ring-green-300 dark:ring-green-800 transition ease-in-out duration-150" onclick="return confirm('Setujui pengajuan ini? {{ Auth::user()->isHrd() ? '(Kuota user akan dipotong otomatis)' : '' }}')">
                                                Setujui
                                            </button>
                                        </form>

                                        {{-- Tombol Reject (Pemicu Modal/Prompt Sederhana) --}}
                                        <button type="button" onclick="openRejectModal({{ $req->id }})" class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-500 focus:outline-none focus:ring ring-red-300 dark:ring-red-800 transition ease-in-out duration-150">
                                            Tolak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $pendingRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Script Sederhana untuk Prompt Reject --}}
    <script>
        function openRejectModal(id) {
            const reason = prompt("Masukkan alasan penolakan (Wajib):");
            if (reason !== null) {
                if (reason.trim().length < 5) {
                    alert("Alasan penolakan minimal 5 karakter.");
                    return;
                }
                
                // Buat form dinamis untuk submit rejection
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/approvals/${id}/reject`; // Pastikan path URL benar sesuai route list
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_note';
                reasonInput.value = reason;

                form.appendChild(csrfInput);
                form.appendChild(reasonInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>