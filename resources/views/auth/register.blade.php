@extends('layouts.auth')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-black">

    <div class="bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl p-10 w-full max-w-md text-white">

        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-red-500 tracking-wide">
                🎬 Buat Akun
            </h1>
            <p class="text-gray-400 mt-2 text-sm">
                Daftar dan mulai pesan tiket favoritmu
            </p>
        </div>

        {{-- Error Message --}}
        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.process') }}">
            @csrf

            {{-- Name --}}
            <div class="mb-4">
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Nama Lengkap"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Email Address"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            {{-- Phone --}}
            <div class="mb-4">
                <input type="text"
                       name="phone"
                       value="{{ old('phone') }}"
                       placeholder="Nomor HP (Optional)"
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <input type="password"
                       name="password"
                       placeholder="Password"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <input type="password"
                       name="password_confirmation"
                       placeholder="Konfirmasi Password"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:border-red-500 focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 transition py-3 rounded-lg font-semibold tracking-wide shadow-lg">
                DAFTAR
            </button>

        </form>

        <div class="text-center mt-6 text-sm text-gray-400">
            Sudah punya akun?
            <a href="{{ route('customer.login') }}"
               class="text-red-500 hover:underline">
                Login
            </a>
        </div>

    </div>

</div>

@endsection
