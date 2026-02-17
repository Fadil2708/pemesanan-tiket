<!DOCTYPE html>
<html>
<head>
    <title>BioskopApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        /* OUTER GLOW RING */
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
        /* ONLINE DOT */
        .online-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background-color: #22c55e; /* green */
            border: 2px solid #111; /* dark border biar kontras */
            border-radius: 9999px;
            box-shadow: 0 0 6px rgba(34,197,94,0.7);
        }

        /* OPTIONAL PULSE */
        @keyframes pulseOnline {
            0% { box-shadow: 0 0 0 0 rgba(34,197,94,0.7); }
            70% { box-shadow: 0 0 0 6px rgba(34,197,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        }

        .online-dot {
            animation: pulseOnline 2s infinite;
        }
        .glass-dropdown {
            backdrop-filter: blur(20px);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
        }
     </style>

</head>
<body class="bg-gray-950 text-white min-h-screen">

{{-- NAVBAR --}}
<nav class="relative z-50 bg-black border-b border-gray-800 px-8 py-4 flex justify-between items-center">

    <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide">
        🎬 BioskopApp
    </a>

    <div class="flex items-center gap-6 text-sm">

        @auth

        @php
            $colors = [
                ['#ef4444', '#f97316'], // red → orange
                ['#3b82f6', '#06b6d4'], // blue → cyan
                ['#8b5cf6', '#ec4899'], // purple → pink
                ['#10b981', '#3b82f6'], // green → blue
                ['#f59e0b', '#ef4444'], // yellow → red
                ['#14b8a6', '#6366f1'], // teal → indigo
            ];

            $index = crc32(auth()->user()->email) % count($colors);
            $gradient = $colors[$index];
        @endphp

        <div class="relative inline-block" x-data="{ open: false }">

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
                class="glass-dropdown absolute right-0 mt-4 w-52 z-50 backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl shadow-2xl overflow-hidden"
                >
                <a href="{{ route('dashboard') }}"
                class="block px-5 py-3 text-sm text-white hover:bg-white/10 transition">
                    Dashboard
                </a>

                <a href="{{ route('profile') }}"
                class="block px-5 py-3 text-sm text-white hover:bg-white/10 transition">
                    Edit Profile
                </a>

                <div class="border-t border-white/10"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-5 py-3 text-sm text-red-400 hover:bg-white/10 transition">
                        Logout
                    </button>
                </form>

            </div>

        </div>
        @endauth

    </div>

</nav>

{{-- CONTENT --}}
<main class="relative z-0 px-8 py-12">

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
