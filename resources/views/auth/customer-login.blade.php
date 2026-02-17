@extends('layouts.auth')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-black">

    <div class="bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl p-10 w-full max-w-md text-white">

        <h1 class="text-4xl font-bold text-center text-red-500 mb-6">
            🎬 Customer Login
        </h1>

        @if($errors->any())
            <div class="bg-red-500/20 p-3 rounded mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login.process') }}">
            @csrf

            <input type="email" name="email"
                class="w-full mb-4 px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:ring-2 focus:ring-red-500"
                placeholder="Email" required>

            <input type="password" name="password"
                class="w-full mb-6 px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:ring-2 focus:ring-red-500"
                placeholder="Password" required>

            <button class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg font-semibold">
                LOGIN
            </button>
        </form>

        <div class="text-center mt-6 text-gray-400 text-sm">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-red-500 hover:underline">
                Register
            </a>
        </div>

    </div>

</div>

@endsection
