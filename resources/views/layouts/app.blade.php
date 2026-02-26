<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BioskopApp - Pesan Tiket Bioskop Online')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animated-avatar {
            background-size: 200% 200%;
            animation: gradientMove 6s ease infinite;
        }
        .animated-avatar::before {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 9999px;
            background: inherit;
            filter: blur(12px);
            opacity: 0.6;
            z-index: -1;
            transition: opacity 0.3s ease;
        }
        .animated-avatar:hover::before {
            opacity: 0.9;
        }
        .online-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background-color: #22c55e;
            border: 2px solid #111;
            border-radius: 9999px;
            box-shadow: 0 0 6px rgba(34,197,94,0.7);
            animation: pulseOnline 2s infinite;
        }
        @keyframes pulseOnline {
            0% { box-shadow: 0 0 0 0 rgba(34,197,94,0.7); }
            70% { box-shadow: 0 0 0 6px rgba(34,197,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        }
        .glass-dropdown {
            backdrop-filter: blur(20px);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .glass-nav {
            backdrop-filter: blur(16px);
            background: rgba(0,0,0,0.8);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .film-card {
            transition: all 0.3s ease;
        }
        .film-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(220, 38, 38, 0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col">

{{-- NAVBAR --}}
<nav class="glass-nav fixed w-full top-0 z-50 px-4 md:px-8 py-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl md:text-2xl font-bold tracking-wide flex items-center gap-2">
            <span>🎬</span>
            <span class="hidden sm:inline">BioskopApp</span>
        </a>

        <div class="flex items-center gap-4 md:gap-6">
            @auth
                @php
                    $colors = [
                        ['#ef4444', '#f97316'],
                        ['#3b82f6', '#06b6d4'],
                        ['#8b5cf6', '#ec4899'],
                        ['#10b981', '#3b82f6'],
                        ['#f59e0b', '#ef4444'],
                        ['#14b8a6', '#6366f1'],
                    ];
                    $index = crc32(auth()->user()->email) % count($colors);
                    $gradient = $colors[$index];
                @endphp

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white uppercase shadow-lg transition hover:scale-110 overflow-hidden">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                class="w-full h-full object-cover rounded-full">
                        @else
                            <div class="animated-avatar w-full h-full flex items-center justify-center"
                                :style="{ background: 'linear-gradient(135deg, {{ $gradient[0] }}, {{ $gradient[1] }})' }">
                                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                            </div>
                        @endif
                    </button>
                    <span class="online-dot"></span>

                    {{-- DROPDOWN --}}
                    <div x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="glass-dropdown absolute right-0 mt-3 w-56 rounded-2xl shadow-2xl overflow-hidden">
                        <a href="{{ route('dashboard') }}"
                            class="block px-5 py-3 text-sm text-white hover:bg-white/10 transition flex items-center gap-3">
                            <span>🏠</span> Dashboard
                        </a>
                        <a href="{{ route('my.orders') }}"
                            class="block px-5 py-3 text-sm text-white hover:bg-white/10 transition flex items-center gap-3">
                            <span>🎟</span> Pesanan Saya
                        </a>
                        <a href="{{ route('profile') }}"
                            class="block px-5 py-3 text-sm text-white hover:bg-white/10 transition flex items-center gap-3">
                            <span>👤</span> Profile
                        </a>
                        <div class="border-t border-white/10"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-5 py-3 text-sm text-red-400 hover:bg-white/10 transition flex items-center gap-3">
                                <span>🚪</span> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('customer.login') }}"
                    class="text-sm font-semibold hover:text-red-500 transition">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="btn-primary px-5 py-2 rounded-lg text-sm font-semibold">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<main class="flex-grow pt-20">
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 md:px-8 mt-4">
            <div class="bg-green-500/20 border border-green-500 text-green-400 p-4 rounded-xl flex items-center gap-3">
                <span class="text-xl">✅</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 md:px-8 mt-4">
            <div class="bg-red-500/20 border border-red-500 text-red-400 p-4 rounded-xl flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-black border-t border-gray-800 py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-3 flex items-center gap-2">
                    <span>🎬</span> BioskopApp
                </h3>
                <p class="text-gray-400 text-sm">
                    Pesan tiket bioskop online dengan mudah dan cepat. Nikmati pengalaman nonton yang tak terlupakan.
                </p>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Menu</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="{{ route('my.orders') }}" class="hover:text-white transition">Pesanan Saya</a></li>
                    <li><a href="{{ route('profile') }}" class="hover:text-white transition">Profile</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Kontak</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li>📧 support@bioskopapp.com</li>
                    <li>📱 +62 812-3456-7890</li>
                    <li>📍 Jakarta, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} BioskopApp. All rights reserved.</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
