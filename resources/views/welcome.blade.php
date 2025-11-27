<!DOCTYPE html>
<html lang="id">
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
<body class="">

    <header class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 shadow-md">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex gap-2 items-center">
                <img src="/cuti-plus.png" class="w-7 h-7" alt="">
                <a href="#" class="text-2xl font-bold ">
                    CutiPlus
                </a>
            </div>
            <div>
                <a href="#fitur" class="text-sm font-medium mr-4 transition">Fitur</a>
                <a href="/login" class="text-sm font-medium bg-gray-700 py-2 px-4 rounded-lg hover:bg-gray-900 transition">Login</a>
            </div>
        </nav>
    </header>

    <section class="py-20 dark:bg-gray-900 ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-extrabold text-gray-800 dark:text-gray-200 sm:text-6xl">
                Kelola Cuti Karyawan <span class="">Tanpa Pusing</span>
            </h1>
            <p class="mt-4 text-xl text-gray-700 dark:text-gray-400 max-w-3xl mx-auto">
                Sistem manajemen cuti yang modern, intuitif, dan otomatis. Maksimalkan efisiensi HR dan buat karyawan senang.
            </p>
            <div class="mt-8">
                <!-- <a href="" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 shadow-xl transition transform hover:scale-105">
                    Coba Gratis Sekarang
                </a> -->
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-gray-700 dark:text-gray-400 font-semibold tracking-wide uppercase">Fitur Inti</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-800 dark:text-gray-200 sm:text-4xl">
                    Semua yang Anda Butuhkan untuk Manajemen Cuti
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-800 dark:text-gray-200">Pengajuan Cuti Digital</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Karyawan dapat mengajukan, melacak, dan membatalkan cuti dari perangkat mana pun. Notifikasi instan via email untuk persetujuan.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-800 dark:text-gray-200">Alur Persetujuan Otomatis</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Atur hierarki persetujuan (manajer, HR, dll.). Permintaan akan diteruskan secara otomatis sesuai struktur perusahaan.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.333 0 2.333.667 3 2m-3-2V3m-3 7c-.667-.333-1.667-1-3-2m6 2v7m0 0a2 2 0 100-4 2 2 0 000 4zm0 4h.01M16 12h.01M8 12h.01M12 16h.01M8 16h.01M16 16h.01" /></svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-800 dark:text-gray-200">Kalkulasi Saldo Akurat</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Saldo cuti tahunan, sakit, dan cuti khusus dikalkulasi secara real-time berdasarkan kebijakan perusahaan (prorata, carry-over).
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-800 dark:text-gray-200">Kalender Tim dan Pelaporan</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Lihat siapa yang cuti hari ini atau minggu ini dalam satu kalender yang jelas. Hasilkan laporan cuti bulanan/tahunan untuk audit HR.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-base text-gray-400">
                &copy; 2024 CutiPlus. Semua hak cipta dilindungi.
            </p>
        </div>
    </footer>
</body>
</html>