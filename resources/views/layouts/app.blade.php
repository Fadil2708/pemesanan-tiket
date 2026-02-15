<!DOCTYPE html>
<html>
<head>
    <title>BioskopApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

{{-- NAVBAR --}}
<nav class="bg-black border-b border-gray-800 px-8 py-4 flex justify-between items-center">

    <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide">
        🎬 BioskopApp
    </a>

    <div class="flex items-center gap-6 text-sm">

        @auth
            <span class="text-gray-400">
                Halo, {{ auth()->user()->name }}
            </span>

            <a href="{{ route('dashboard') }}"
               class="hover:text-red-500 transition">
                Dashboard
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition">
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('login.role', 'customer') }}"
               class="hover:text-red-500 transition">
                Login Customer
            </a>
        @endauth

    </div>

</nav>

{{-- CONTENT --}}
<main class="px-8 py-12">

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

</main>

</body>
</html>
