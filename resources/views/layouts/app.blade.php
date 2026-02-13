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
            <a href="/login" style="color:white;">Login</a>
        @endauth
    </div>
</nav>

<div style="padding:40px;">
    @yield('content')
</div>

</body>
</html>
