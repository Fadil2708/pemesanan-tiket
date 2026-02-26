<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - BioskopApp')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .dark-mode {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }
        .dark-mode .sidebar {
            background-color: #16213e;
        }
        .dark-mode .header {
            background-color: #0f3460;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-xl">

        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <span>🎬</span> Admin Panel
            </h2>
            <p class="text-xs text-gray-400 mt-1">BioskopApp</p>
        </div>

        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-red-400' : 'text-gray-300' }}">
                <span>📊</span> Dashboard
            </a>

            <a href="{{ route('films.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition
               {{ request()->routeIs('films.*') ? 'bg-gray-800 text-red-400' : 'text-gray-300' }}">
                <span>🎬</span> Film
            </a>

            <a href="{{ route('showtimes.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition
               {{ request()->routeIs('showtimes.*') ? 'bg-gray-800 text-red-400' : 'text-gray-300' }}">
                <span>🕒</span> Showtime
            </a>

            <a href="{{ route('orders.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition
                {{ request()->routeIs('orders.*') ? 'bg-gray-800 text-red-400' : 'text-gray-300' }}">
                    <span>🎟</span> Orders
            </a>

        </nav>

        <div class="p-4 border-t border-gray-700">

            <div class="bg-gray-800 rounded-lg p-4 mb-3">
                <div class="text-xs text-gray-400 mb-1">Login sebagai:</div>
                <div class="font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-red-600 hover:bg-red-700 py-2.5 rounded-lg text-sm font-semibold transition flex items-center justify-center gap-2">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col">

        {{-- TOP BAR --}}
        <header class="bg-white shadow-sm px-10 py-4 flex justify-between items-center header">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('title', 'Admin')
                </h1>
                @if(session('success'))
                    <p class="text-sm text-green-600 mt-1">✅ {{ session('success') }}</p>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500 text-right">
                    <div>{{ now()->format('d M Y') }}</div>
                    <div class="text-xs">{{ now()->format('H:i') }} WIB</div>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                    🌐 Lihat Website
                </a>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-10 overflow-auto">
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

</div>

</body>
</html>
