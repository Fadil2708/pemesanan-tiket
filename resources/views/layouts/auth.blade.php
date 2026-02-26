<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BioskopApp - Auth')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }
        .glass-card {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
</head>
<body class="auth-bg min-h-screen flex items-center justify-center p-4">
    @yield('content')
</body>
</html>
