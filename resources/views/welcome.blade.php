<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Nasi Goreng Mas Reno') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

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
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero-overlay {
            /* background: linear-gradient(135deg, rgba(248, 59, 59, 0.4) 0%, rgba(249, 115, 22, 0.3) 100%); */
        }

        .btn-login {
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(248, 59, 59, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(248, 59, 59, 0.4);
        }

        .content-wrapper {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-delay-1 {
            animation-delay: 0.2s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        .animate-delay-2 {
            animation-delay: 0.4s;
            opacity: 0;
            animation-fill-mode: forwards;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Main Container -->
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image Container -->
        <!-- GANTI SRC DI BAWAH INI DENGAN PATH GAMBAR ANDA -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/bgwelcome.jpg') }}" alt="Background" class="w-full h-full object-cover">
            <!-- Overlay Gradient -->
            <div class="hero-overlay absolute inset-0"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-4xl mx-auto px-6 py-12 text-center">
            <!-- Logo / Brand (Optional) -->
            <div class="mb-8 animate-fadeInUp">
                <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-bold drop-shadow-2xl">
                    Nasi Goreng Mas Reno
                </h1>
            </div>

            <!-- Main Heading -->
            <div
                class="content-wrapper rounded-3xl px-8 py-12 md:px-16 md:py-16 shadow-2xl border border-white/20 animate-fadeInUp animate-delay-1">
                <h2 class="text-white text-3xl md:text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                    Selamat Datang di
                    <br>
                    <span class="text-accent-300">Sistem Manajemen Restoran</span>
                </h2>

                <p class="text-white/90 text-lg md:text-xl mb-8 max-w-2xl mx-auto leading-relaxed">
                    Kelola menu, pesanan, dan laporan restoran Anda dengan mudah dan efisien
                </p>

                <!-- Login Button -->
                @if (Route::has('login'))
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fadeInUp animate-delay-2">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="btn-login inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white text-lg font-semibold rounded-full hover:from-primary-700 hover:to-accent-700 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="btn-login inline-flex items-center px-10 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white text-lg font-semibold rounded-full hover:from-primary-700 hover:to-accent-700 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Masuk ke Sistem
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center px-10 py-4 bg-white/20 backdrop-blur-sm text-white text-lg font-semibold rounded-full hover:bg-white/30 transition-all border-2 border-white/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Daftar Baru
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif

                <!-- Additional Info (Optional) -->
                <div class="mt-12 pt-8 border-t border-white/20">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-white">
                        <div class="flex flex-col items-center">
                            <div class="bg-white/10 p-4 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-lg mb-1">Kelola Menu</h3>
                            <p class="text-white/70 text-sm">Atur menu dengan mudah</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="bg-white/10 p-4 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-lg mb-1">Laporan</h3>
                            <p class="text-white/70 text-sm">Pantau penjualan real-time</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="bg-white/10 p-4 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-lg mb-1">Manajemen User</h3>
                            <p class="text-white/70 text-sm">Kelola tim dengan aman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer (Optional) -->
        <div class="absolute bottom-0 left-0 right-0 z-10 py-4 text-center">
            <p class="text-white/60 text-sm">
                © {{ date('Y') }} Nasi Goreng Mas Reno. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
