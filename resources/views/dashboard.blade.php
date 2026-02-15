@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-10">
        Welcome, {{ auth()->user()->name }} 👋
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    
        {{-- CARD 3 --}}
        <div class="bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-800">
            <h3 class="text-gray-400 text-sm mb-2">Pesanan Saya</h3>
            <p class="text-4xl font-bold text-red-500">
                {{ $myOrders }}
            </p>
            <a href="{{ route('my.orders') }}" class="hover:text-red-500 transition">
                Pesanan Saya
            </a>
        </div>

    </div>

    <div class="mt-12">
        <a href="{{ route('home') }}"
           class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg font-semibold transition">
            🎬 Lihat Film
        </a>
    </div>

</div>

@endsection
