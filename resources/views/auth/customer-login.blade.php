@extends('layouts.auth')

@section('content')

<div class="w-full max-w-md">
    <div class="glass-card rounded-2xl p-8 md:p-10">
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🎬</div>
            <h1 class="text-3xl font-bold text-white mb-2">Selamat Datang</h1>
            <p class="text-gray-400">Login untuk pesan tiket bioskop</p>
        </div>

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-3">
                <span>⚠️</span> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login.process') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" name="email"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-red-500 focus:border-transparent transition text-white placeholder-gray-500"
                    placeholder="nama@email.com" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-red-500 focus:border-transparent transition text-white placeholder-gray-500"
                    placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold text-white">
                Login
            </button>
        </form>

        <div class="text-center mt-6 text-gray-400 text-sm">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-red-400 hover:text-red-300 font-semibold transition">
                Daftar Sekarang
            </a>
        </div>

        <div class="mt-6 pt-6 border-t border-white/10 text-center">
            <a href="{{ route('admin.login') }}" class="text-xs text-gray-500 hover:text-gray-400 transition">
                Login sebagai Admin
            </a>
        </div>
    </div>
</div>

@endsection
