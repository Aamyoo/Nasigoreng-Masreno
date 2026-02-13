<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nasi Goreng Mas Reno - Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff1f1',
                            100: '#ffe1e1',
                            200: '#ffc7c7',
                            300: '#ffa0a0',
                            400: '#ff6b6b',
                            500: '#f83b3b',
                            600: '#e51d1d',
                            700: '#c11414',
                            800: '#a01414',
                            900: '#841818',
                        },
                        accent: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #f83b3b 0%, #f97316 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #e51d1d 0%, #ea580c 100%);
        }

        /* Smooth animations */
        .sidebar-link {
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-link:hover {
            transform: translateX(4px);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: linear-gradient(to bottom, #fff, rgba(255, 255, 255, 0.5));
            border-radius: 0 4px 4px 0;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-slide {
            animation: slideDown 0.4s ease-out;
        }

        /* Pulse animation for active indicator */
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .pulse-green {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="bg-gray-50 font-inter overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div
            class="w-72 bg-gradient-to-br from-primary-700 via-primary-600 to-accent-600 text-white shadow-2xl flex flex-col">
            <!-- Logo Section - Fixed -->
            <div class="p-6 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-cash-register text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white leading-tight">Nasi Goreng</h1>
                        <h2 class="text-lg font-semibold text-accent-100">Mas Reno</h2>
                        <div class="mt-1 px-2 py-0.5 bg-primary-900/40 rounded backdrop-blur-sm inline-block">
                            <p class="text-xs text-primary-100 font-medium">Kasir Panel</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation - Scrollable -->
            <nav class="flex-1 overflow-y-auto px-4 py-6">
                <div class="space-y-2">
                    <a href="{{ route('kasir.dashboard') }}"
                        class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.dashboard') ? 'active bg-white/20 shadow-lg' : 'hover:bg-white/10' }} group">
                        <i
                            class="fas fa-tachometer-alt w-5 text-center {{ request()->routeIs('kasir.dashboard') ? 'text-white' : 'text-accent-200 group-hover:text-white' }}"></i>
                        <span
                            class="font-medium {{ request()->routeIs('kasir.dashboard') ? 'text-white' : 'text-accent-100 group-hover:text-white' }}">Dashboard</span>
                    </a>

                    <a href="{{ route('kasir.transaction.create') }}"
                        class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.transaction*') ? 'active bg-white/20 shadow-lg' : 'hover:bg-white/10' }} group">
                        <i
                            class="fas fa-shopping-cart w-5 text-center {{ request()->routeIs('kasir.transaction*') ? 'text-white' : 'text-accent-200 group-hover:text-white' }}"></i>
                        <span
                            class="font-medium {{ request()->routeIs('kasir.transaction*') ? 'text-white' : 'text-accent-100 group-hover:text-white' }}">Transaksi</span>
                        <div class="ml-auto">
                            <div class="w-2 h-2 bg-green-400 rounded-full pulse-green"></div>
                        </div>
                    </a>

                    <a href="{{ route('kasir.reports') }}"
                        class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.reports') ? 'active bg-white/20 shadow-lg' : 'hover:bg-white/10' }} group">
                        <i
                            class="fas fa-chart-line w-5 text-center {{ request()->routeIs('kasir.reports') ? 'text-white' : 'text-accent-200 group-hover:text-white' }}"></i>
                        <span
                            class="font-medium {{ request()->routeIs('kasir.reports') ? 'text-white' : 'text-accent-100 group-hover:text-white' }}">Laporan</span>
                    </a>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8">
                    {{-- <p class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-3 px-4">Quick Actions
                    </p> --}}
                    <div class="space-y-2">
                        {{-- <button
                            class="sidebar-link w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-white/10 group text-left">
                            <i class="fas fa-plus-circle w-5 text-center text-accent-200 group-hover:text-white"></i>
                            <span class="font-medium text-accent-100 group-hover:text-white">Pesanan Baru</span>
                        </button>

                        <button
                            class="sidebar-link w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-white/10 group text-left">
                            <i class="fas fa-receipt w-5 text-center text-accent-200 group-hover:text-white"></i>
                            <span class="font-medium text-accent-100 group-hover:text-white">Cetak Struk</span>
                        </button> --}}
                    </div>
                </div>

                <!-- Logout Section -->
                <div class="mt-8 pt-6 border-t border-white/20">
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit"
                            class="sidebar-link w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-red-500/30 text-accent-200 hover:text-white group text-left">
                            <i class="fas fa-sign-out-alt w-5 text-center group-hover:text-white"></i>
                            <span class="font-medium group-hover:text-white">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header - Fixed -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 shadow-sm flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('page-title')</h1>
                        <p class="text-gray-600 mt-1">@yield('page-description', 'Kelola transaksi penjualan')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Live Clock -->
                        <div class="hidden sm:block text-right">
                            <div id="live-clock" class="text-lg font-bold text-gray-900"></div>
                            <div id="live-date" class="text-sm text-gray-500"></div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="text-center px-4 py-2 bg-primary-50 rounded-lg">
                                <div class="text-lg font-bold text-gray-900">24</div>
                                <div class="text-xs text-gray-600">Pesanan Hari Ini</div>
                            </div>
                            <div
                                class="text-center px-4 py-2 bg-gradient-to-r from-primary-50 to-accent-50 rounded-lg border-l border-primary-200">
                                <div class="text-lg font-bold text-primary-600">Rp 1.250.000</div>
                                <div class="text-xs text-gray-600">Total Penjualan</div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-3 px-4 py-2 bg-gray-50 rounded-lg">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-user-tie text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Kasir</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->name ?? 'Operator' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area - Scrollable -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Alert Messages -->
                @if (session('success'))
                    <div
                        class="alert-slide mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-green-800">Berhasil!</p>
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                            <button class="ml-auto text-green-500 hover:text-green-700 transition-colors"
                                onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="alert-slide mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-red-800">Terjadi Kesalahan!</p>
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                            <button class="ml-auto text-red-500 hover:text-red-700 transition-colors"
                                onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Main Content -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile menu button -->
    <button id="mobile-menu-button"
        class="fixed top-4 left-4 z-50 lg:hidden bg-gradient-to-r from-primary-600 to-accent-600 text-white p-3 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        // Live clock function
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const clockElement = document.getElementById('live-clock');
            const dateElement = document.getElementById('live-date');

            if (clockElement) clockElement.textContent = timeString;
            if (dateElement) dateElement.textContent = dateString;
        }

        // Update clock every second
        updateClock();
        setInterval(updateClock, 1000);

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                // Add mobile menu functionality here if needed
                alert('Mobile menu functionality - to be implemented');
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-slide');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-in-out, transform 0.5s ease-in-out';
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100px)';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>

</html>
