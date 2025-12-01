<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Cuti - Kelola Cuti Karyawan Lebih Mudah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-neutral-50 dark:bg-gray-900 text-neutral-900 dark:text-gray-100 font-sans antialiased transition-colors duration-300">

    <header class="fixed w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-neutral-200 dark:border-gray-800 transition-colors duration-300">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
            <div class="flex gap-2 items-center">

                <img src="/cuti-plus.png" alt="" class="w-8 h-8">

                <span class="text-xl font-bold text-neutral-900 dark:text-white tracking-tight">
                    Cuti<span class="text-primary-600 dark:text-primary-500">Plus</span>
                </span>
            </div>

            <div class="flex items-center gap-6">
                <a href="#fitur" class="text-sm font-medium text-neutral-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors hidden sm:block">Fitur</a>
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-sm font-bold bg-primary-600 hover:bg-primary-700 text-white py-2.5 px-5 rounded-full transition-all shadow-lg shadow-primary-600/30 dark:shadow-primary-900/50">
                    Masuk
                </a>
                @endauth
                @endif
            </div>
        </nav>
    </header>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-secondary-400/20 dark:bg-secondary-600/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-primary-600/20 dark:bg-primary-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800 text-primary-700 dark:text-primary-300 text-xs font-semibold uppercase tracking-wide mb-6">
                        🚀 Solusi HR Modern #1
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-extrabold text-neutral-900 dark:text-white leading-tight mb-6">
                        Kelola Cuti Tim <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400">
                            Tanpa Drama
                        </span>
                    </h1>
                    <p class="text-lg text-neutral-500 dark:text-gray-400 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Tinggalkan formulir kertas dan spreadsheet yang membingungkan. CutiPlus memberikan pengalaman pengajuan cuti yang instan, transparan, dan otomatis.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 transition-all shadow-xl shadow-primary-600/20 hover:-translate-y-1">
                            Mulai Sekarang Gratis
                        </a>
                        <a href="#fitur" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold rounded-xl text-neutral-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-neutral-200 dark:border-gray-700 hover:bg-neutral-50 dark:hover:bg-gray-700 transition-all">
                            Pelajari Fitur
                        </a>
                    </div>

                    <div class="mt-10 flex items-center justify-center lg:justify-start gap-4">
                        <div class="flex -space-x-2">
                            <img class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900" src="https://i.pravatar.cc/100?img=1" alt="User">
                            <img class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900" src="https://i.pravatar.cc/100?img=2" alt="User">
                            <img class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900" src="https://i.pravatar.cc/100?img=3" alt="User">
                            <div class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900 bg-neutral-100 dark:bg-gray-800 flex items-center justify-center text-xs font-bold text-neutral-600 dark:text-gray-300">+2k</div>
                        </div>
                        <p class="text-sm text-neutral-500 dark:text-gray-400">Digunakan oleh <span class="font-bold text-neutral-900 dark:text-white">2,000+</span> karyawan</p>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-neutral-200 dark:border-gray-800 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Dashboard Preview" class="w-full h-auto opacity-90 dark:opacity-80">

                        <div class="absolute bottom-6 left-6 right-6 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md p-4 rounded-xl shadow-lg border border-white/50 dark:border-gray-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-neutral-900 dark:text-white">Pengajuan Cuti Disetujui</p>
                                    <p class="text-xs text-neutral-500 dark:text-gray-400">Baru saja • Oleh HR Manager</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-primary-600 dark:text-primary-400 font-bold tracking-wide uppercase text-sm mb-2">Fitur Unggulan</h2>
                <p class="text-3xl sm:text-4xl font-extrabold text-neutral-900 dark:text-white">
                    Satu Platform, Segudang Kemudahan
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="p-6 bg-neutral-50 dark:bg-gray-800 rounded-2xl border border-neutral-100 dark:border-gray-700 hover:border-primary-100 dark:hover:border-primary-700 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Pengajuan Kilat</h3>
                    <p class="text-neutral-500 dark:text-gray-400 text-sm leading-relaxed">
                        Ajukan cuti dalam hitungan detik. Cukup pilih tanggal, alasan, dan kirim. Semudah mengirim pesan chat.
                    </p>
                </div>

                <div class="p-6 bg-neutral-50 dark:bg-gray-800 rounded-2xl border border-neutral-100 dark:border-gray-700 hover:border-primary-100 dark:hover:border-primary-700 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Approval Otomatis</h3>
                    <p class="text-neutral-500 dark:text-gray-400 text-sm leading-relaxed">
                        Sistem notifikasi cerdas memastikan setiap permintaan sampai ke atasan yang tepat dan diproses tanpa delay.
                    </p>
                </div>

                <div class="p-6 bg-neutral-50 dark:bg-gray-800 rounded-2xl border border-neutral-100 dark:border-gray-700 hover:border-primary-100 dark:hover:border-primary-700 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Saldo Real-time</h3>
                    <p class="text-neutral-500 dark:text-gray-400 text-sm leading-relaxed">
                        Transparansi total. Karyawan dapat melihat sisa jatah cuti tahunan mereka kapan saja, di mana saja.
                    </p>
                </div>

                <div class="p-6 bg-neutral-50 dark:bg-gray-800 rounded-2xl border border-neutral-100 dark:border-gray-700 hover:border-primary-100 dark:hover:border-primary-700 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Kalender Tim</h3>
                    <p class="text-neutral-500 dark:text-gray-400 text-sm leading-relaxed">
                        Cegah kekurangan tenaga kerja. Lihat jadwal cuti tim dalam satu tampilan kalender yang intuitif.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white dark:bg-gray-900 border-t border-neutral-200 dark:border-gray-800 pt-16 pb-8 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <span class="text-2xl font-bold text-neutral-900 dark:text-white">
                        Cuti<span class="text-primary-600 dark:text-primary-500">Plus</span>
                    </span>
                    <p class="mt-4 text-neutral-500 dark:text-gray-400 text-sm">
                        Platform manajemen cuti modern untuk perusahaan yang peduli produktivitas dan kesejahteraan karyawan.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-neutral-900 dark:text-white mb-4">Produk</h4>
                    <ul class="space-y-2 text-sm text-neutral-500 dark:text-gray-400">
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Fitur</a></li>
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Harga</a></li>
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Integrasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-neutral-900 dark:text-white mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm text-neutral-500 dark:text-gray-400">
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-neutral-900 dark:text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-neutral-500 dark:text-gray-400">
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Privasi</a></li>
                        <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-neutral-100 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-neutral-400 dark:text-gray-500">
                    &copy; 2024 CutiPlus Inc. All rights reserved.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                    <a href="#" class="text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>