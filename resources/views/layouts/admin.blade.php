<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - BioskopApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col">

        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold">🎬 Admin Panel</h2>
        </div>

        <nav class="flex-1 p-6 space-y-3 text-sm">

            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800
               {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-red-400' : '' }}">
                📊 Dashboard
            </a>

            <a href="{{ route('films.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800
               {{ request()->routeIs('films.*') ? 'bg-gray-800 text-red-400' : '' }}">
                🎬 Film
            </a>

            <a href="{{ route('showtimes.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800
               {{ request()->routeIs('showtimes.*') ? 'bg-gray-800 text-red-400' : '' }}">
                🕒 Showtime
            </a>

            <a href="{{ route('orders.index') }}"
                class="block px-3 py-2 rounded hover:bg-gray-800
                {{ request()->routeIs('orders.*') ? 'bg-gray-800 text-red-400' : '' }}">
                    🎟 Orders
            </a>

            <a href="#"
               class="block px-3 py-2 rounded hover:bg-gray-800">
                👥 Users
            </a>

        </nav>

        <div class="p-6 border-t border-gray-700">
            <div class="text-xs mb-3 text-gray-400">
                Login sebagai:
            </div>
            <div class="font-semibold mb-4">
                {{ auth()->user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-red-600 hover:bg-red-700 py-2 rounded text-sm">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col">

        {{-- TOP BAR --}}
        <header class="bg-white shadow px-10 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">
                @yield('title', 'Admin')
            </h1>

            <div class="text-sm text-gray-500">
                {{ now()->format('d M Y') }}
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-10">
            @if(session('success'))
                <div class="bg-green-500 text-white p-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

</div>

</body>
</html>
