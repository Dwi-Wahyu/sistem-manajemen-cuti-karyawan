<div x-data="{ sidebarOpen: false }" class="md:flex md:flex-col md:h-screen md:sticky md:top-0">
    <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 md:hidden">
        <div class="flex items-center">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="ml-4">
                <img src="/cuti-plus.png" class="w-8 h-8" alt="Logo">
            </a>
        </div>
        <div class="flex items-center">
            <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
        </div>
    </div>

    <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 transition duration-300 transform bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 md:translate-x-0 md:relative md:inset-0 md:flex md:flex-col md:h-full">

        <div class="flex-1 overflow-y-auto">
            <div class="flex items-center justify-center h-16 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 md:flex">
                <div class="flex items-center gap-2 font-bold text-xl text-gray-800 dark:text-white">
                    <img src="/cuti-plus.png" class="w-8 h-8" alt="">
                    <span>Cuti Plus</span>
                </div>
            </div>

            <nav class="mt-5 px-4 space-y-2">

                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>

                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('Dashboard') }}
                </a>

                <a href="{{ route('leave-requests.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('leave-requests.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('Cuti Saya') }}
                </a>

                @if(Auth::user()->isDivisionHead() || Auth::user()->isHrd())
                <a href="{{ route('approvals.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('approvals.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Persetujuan Cuti') }}
                </a>
                @endif

                @can('access-admin-panel')
                <div class="pt-4 pb-1">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin Panel</p>
                </div>

                <a href="{{ route('approvals.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('approvals.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Persetujuan Cuti') }}
                </a>



                <a href="{{ route('admin.divisions.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.divisions.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ __('Manajemen Divisi') }}
                </a>

                <div class="pt-4 pb-1">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Manajemen Pengguna</p>
                </div>

                <!-- Kepala Divisi -->
                <a href="{{ route('admin.users.index', ['role' => 'division_head']) }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 
                    {{ request()->routeIs('admin.users.index') && request('role') === 'division_head' ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">

                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __('Kepala Divisi') }}
                </a>

                <!-- Kepala Divisi -->
                <a href="{{ route('admin.users.index', ['role' => 'hrd']) }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 
                    {{ request()->routeIs('admin.users.index') && request('role') === 'hrd' ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">

                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __('HRD') }}
                </a>

                <!-- Karyawan -->
                <a href="{{ route('admin.users.index', ['role' => 'employee']) }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700 
                    {{ request()->routeIs('admin.users.index') && request('role') === 'employee' ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-900 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400' }}">

                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __('Karyawan') }}
                </a>
                @endcan


            </nav>
        </div>

        <!-- <div class="shrink-0 p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800" title="Log Out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
            <div class="mt-2 text-center">
                <a href="{{ route('profile.edit') }}" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Edit Profile</a>
            </div>
        </div> -->
    </div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black opacity-50 md:hidden"></div>
</div>