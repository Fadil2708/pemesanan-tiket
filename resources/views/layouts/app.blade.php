<!DOCTYPE html>
<html>
<head>
    <title>Pemesanan Tiket Film</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<nav style="padding:15px;background:#111;color:white;display:flex;justify-content:space-between;">
    <div>
        🎬 BioskopApp
    </div>

    <div>
        @auth
            Halo, {{ auth()->user()->name }}
            |
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button style="background:none;border:none;color:white;cursor:pointer;">
                    Logout
                </button>
            </form>
        @else
           @guest
                <a href="{{ route('login.role', 'customer') }}" class="mr-4">
                    Login Customer
                </a>

                <a href="{{ route('login.role', 'admin') }}">
                    Login Admin
                </a>
            @endguest
        @endauth
    </div>
</nav>

<div style="padding:40px;">
    @if(session('success'))
        <div style="background:green;color:white;padding:10px;margin:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:red;color:white;padding:10px;margin:10px;">
            {{ session('error') }}
        </div>
    @endif
    @yield('content')
</div>

</body>
</html>
