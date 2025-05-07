<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Kendaraan - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="description" content="Admin dashboard for vehicle rental management system">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white flex-shrink-0">
            <div class="px-6 py-5 border-b border-blue-700">
                <h1 class="text-2xl font-bold">Rental Kendaraan</h1>
              
            </div>

            <nav class="mt-4" aria-label="Main navigation">
                <div class="px-4 py-2 text-blue-200 uppercase text-xs font-semibold">Menu Utama</div>
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-3 hover:bg-blue-700 flex items-center transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}"
                            aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kendaraan.index') }}"
                            class="block px-4 py-3 hover:bg-blue-700 flex items-center transition-colors duration-200 {{ request()->routeIs('kendaraan.*') ? 'bg-blue-700' : '' }}"
                            aria-current="{{ request()->routeIs('kendaraan.*') ? 'page' : 'false' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Kendaraan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pelanggan.index') }}"
                            class="block px-4 py-3 hover:bg-blue-700 flex items-center transition-colors duration-200 {{ request()->routeIs('pelanggan.*') ? 'bg-blue-700' : '' }}"
                            aria-current="{{ request()->routeIs('pelanggan.*') ? 'page' : 'false' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            Pelanggan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('penyewaan.index') }}"
                            class="block px-4 py-3 hover:bg-blue-700 flex items-center transition-colors duration-200 {{ request()->routeIs('penyewaan.*') ? 'bg-blue-700' : '' }}"
                            aria-current="{{ request()->routeIs('penyewaan.*') ? 'page' : 'false' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Penyewaan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.index') }}"
                            class="block px-4 py-3 hover:bg-blue-700 flex items-center transition-colors duration-200 {{ request()->routeIs('laporan.*') ? 'bg-blue-700' : '' }}"
                            aria-current="{{ request()->routeIs('laporan.*') ? 'page' : 'false' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Laporan
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex justify-between items-center px-6 py-4">
                    <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-label="Notifications">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative">
                            <button id="user-menu" class="flex items-center space-x-2 focus:outline-none"
                                aria-label="User menu" aria-haspopup="true" aria-expanded="false">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold"
                                    aria-hidden="true">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="dropdown-menu"
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Profil Saya</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Pengaturan</a>
                                <form method="POST" action="{{ route('logout') }}" role="none">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
                @stack('scripts')

            </main>

            <footer class="bg-gray-50 border-t border-gray-200 py-4">
                <div class="container mx-auto px-4 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} Rey Developer. All rights reserved.
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Toggle dropdown menu with better accessibility
        document.getElementById('user-menu').addEventListener('click', function (e) {
            e.preventDefault();
            const menu = document.getElementById('dropdown-menu');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            menu.classList.toggle('hidden');
            this.setAttribute('aria-expanded', !isExpanded);
        });

        // Close dropdown when clicking outside or pressing Escape
        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('dropdown-menu');
            const button = document.getElementById('user-menu');

            if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('dropdown-menu');
                const button = document.getElementById('user-menu');
                
                dropdown.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</body>

</html>