@extends('layouts.auth')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-950 via-black to-gray-900">

    <div class="bg-gray-900/70 backdrop-blur-xl border border-gray-800 shadow-2xl rounded-2xl p-10 w-full max-w-md text-white">

        <h1 class="text-4xl font-bold text-center text-blue-500 mb-6">
            🛠 Admin Panel
        </h1>

        @if($errors->any())
            <div class="bg-red-500/20 p-3 rounded mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.process') }}">
            @csrf

            <input type="email" name="email"
                class="w-full mb-4 px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:ring-2 focus:ring-blue-500"
                placeholder="Admin Email" required>

            <input type="password" name="password"
                class="w-full mb-6 px-4 py-3 rounded-lg bg-black/40 border border-gray-700 focus:ring-2 focus:ring-blue-500"
                placeholder="Password" required>

            <button class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-semibold">
                LOGIN ADMIN
            </button>
        </form>

    </div>

</div>

@endsection
