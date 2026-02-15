@extends('layouts.auth')

@section('content')

<div class="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-black">

    <div class="bg-white/5 backdrop-blur-lg border border-white/10 shadow-2xl rounded-2xl p-10 w-full max-w-md text-white">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-wide">
                🎬 BioskopApp
            </h1>
            <p class="text-gray-400 mt-2">
                Register {{ ucfirst($role) }}
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.process') }}">
            @csrf

            <input type="hidden" name="role" value="{{ $role }}">

            <div class="mb-4">
                <input type="text"
                       name="name"
                       placeholder="Nama Lengkap"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            <div class="mb-4">
                <input type="email"
                       name="email"
                       placeholder="Email address"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            <div class="mb-4">
                <input type="text"
                       name="phone"
                       placeholder="Nomor HP"
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            <div class="mb-6">
                <input type="password"
                       name="password"
                       placeholder="Password"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 transition py-3 rounded-lg font-semibold tracking-wide shadow-lg">
                REGISTER
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-400">
            Sudah punya akun?
            <a href="{{ route('login.role', $role) }}"
               class="text-red-500 hover:underline">
                Login
            </a>
        </div>

    </div>

</div>

@endsection
