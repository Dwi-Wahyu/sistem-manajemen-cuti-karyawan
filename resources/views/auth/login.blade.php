<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Cuti Plus</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen flex items-center justify-center dark:bg-gray-900 bg-neutral-50 p-4">

        <div class="w-full max-w-6xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row h-full md:min-h-[500px]">

            <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-center">

                <div class="mb-8">
                    <a href="/" class="flex items-center gap-2 mb-2">
                        <img src="/cuti-plus.png" alt="" class="w-10 h-10">
                        <span class="text-2xl font-bold tracking-tighter">Cuti Plus</span>
                    </a>
                    <h1 class="text-3xl font-bold">{{ __('Selamat Datang!') }}</h1>
                    <p class="text-neutral-300 mt-2">{{ __('Silakan masukkan detail akun Anda untuk masuk.') }}</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="login" class="block text-sm font-medium text-neutral-200 mb-1">{{ __('Username') }}</label>
                        <input id="login"
                            class="w-full px-4 py-3 rounded-xl bg-neutral-50 border border-neutral-200 text-neutral-900 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none placeholder-neutral-400"
                            type="text"
                            name="login"
                            :value="old('login')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan username Anda" />
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-sm font-medium text-neutral-200">{{ __('Password') }}</label>
                        </div>
                        <input id="password"
                            class="w-full px-4 py-3 rounded-xl bg-neutral-50 border border-neutral-200 text-neutral-900 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none placeholder-neutral-400"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-neutral-300 text-primary-600 shadow-sm focus:ring-primary-500" name="remember">
                            <span class="ms-2 text-sm text-neutral-300">{{ __('Ingat Saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                        <a class="text-sm font-medium text-primary-600 hover:text-primary-700 hover:underline transition-colors" href="{{ route('password.request') }}">
                            {{ __('Lupa Password?') }}
                        </a>
                        @endif
                    </div>


                    <button type="submit" class="w-full py-3.5 px-4 font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                        {{ __('Masuk Sekarang') }}
                    </button>
                </form>
            </div>

            <div class="hidden md:block md:w-1/2 relative bg-neutral-900">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80"
                    alt="Modern Office"
                    class="absolute inset-0 w-full h-full object-cover opacity-80" />

                <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-neutral-900/40 to-transparent"></div>

                <div class="absolute bottom-0 left-0 p-12 w-full text-white">
                    <div class="backdrop-blur-md bg-white/10 border border-white/20 p-6 rounded-2xl shadow-xl">
                        <div class="flex text-yellow-400 mb-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <p class="text-lg leading-relaxed italic mb-4 opacity-90">
                            "Dengan Cuti Plus, mengelola jadwal libur dan produktivitas tim jadi jauh lebih mudah. Efisiensi meningkat, birokrasi berkurang."
                        </p>
                        <div>
                            <h4 class="font-bold text-white text-lg">Liam Smith</h4>
                            <p class="text-neutral-300 text-sm">HR Manager, Global Tech</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>