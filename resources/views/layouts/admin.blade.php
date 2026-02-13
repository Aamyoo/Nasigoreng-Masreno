<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nasi Goreng Mas Reno - Admin</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

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
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #f97316, #fb923c);
        }

        .alert-slide {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div
            class="w-64 bg-gradient-to-b from-primary-800 via-primary-700 to-primary-900 text-white flex-shrink-0 shadow-2xl">
            <!-- Logo Section -->
            <div class="p-6 border-b border-primary-600/30">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-accent-400 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-utensils text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight">Nasi Goreng</h1>
                        <p class="text-sm text-primary-200 font-medium">Mas Reno</p>
                    </div>
                </div>
                <div class="mt-3 px-3 py-1.5 bg-primary-900/40 rounded-lg backdrop-blur-sm">
                    <p class="text-xs text-primary-100 font-medium">Admin Panel</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-3">
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link flex items-center space-x-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active bg-primary-900/60 text-white' : 'text-primary-100 hover:bg-primary-900/40' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.menu.index') }}"
                        class="sidebar-link flex items-center space-x-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.menu*') ? 'active bg-primary-900/60 text-white' : 'text-primary-100 hover:bg-primary-900/40' }}">
                        <i class="fas fa-book-open w-5"></i>
                        <span class="font-medium">Menu</span>
                    </a>

                    <a href="{{ route('admin.reports') }}"
                        class="sidebar-link flex items-center space-x-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.reports') ? 'active bg-primary-900/60 text-white' : 'text-primary-100 hover:bg-primary-900/40' }}">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="font-medium">Laporan</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-link flex items-center space-x-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.users*') ? 'active bg-primary-900/60 text-white' : 'text-primary-100 hover:bg-primary-900/40' }}">
                        <i class="fas fa-users w-5"></i>
                        <span class="font-medium">Pengguna</span>
                    </a>
                </div>

                <!-- Logout Section -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <div class="mt-8 pt-6 border-t border-primary-600/30">
                        <button type="submit"
                            class="sidebar-link w-full flex items-center space-x-3 py-3 px-4 rounded-lg
                   text-primary-100 hover:bg-red-600/40 hover:text-white">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </div>
                </form>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">
                                @yield('page-title', 'Dashboard')
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                @yield('page-description', 'Selamat datang di panel ' . (Auth::user()->nama_lengkap ?? 'Admin'))
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3 px-4 py-2 bg-gray-50 rounded-lg">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ Auth::user()->nama_lengkap ?? 'Admin' }}
                                    </p>
                                    <p class="text-xs text-gray-500">Administrator</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <div class="flex-1 overflow-auto bg-gray-50">
                <div class="p-6">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div
                            class="alert-slide bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg shadow-sm mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                <div>
                                    <p class="font-semibold">Berhasil!</p>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="alert-slide bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg shadow-sm mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                                <div>
                                    <p class="font-semibold">Terjadi Kesalahan!</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        @yield('content')
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        © {{ date('Y') }} Nasi Goreng Mas Reno. All rights reserved.
                    </p>
                    <p class="text-sm text-gray-500">
                        Version 1.0.0
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-slide');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'all 0.3s ease-out';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>

</html>
