@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-gray-500 text-sm">Total Film</h3>
        <p class="text-3xl font-bold">{{ $totalFilms }}</p>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-gray-500 text-sm">Total Showtime</h3>
        <p class="text-3xl font-bold">{{ $totalShowtimes }}</p>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-gray-500 text-sm">Total Orders</h3>
        <p class="text-3xl font-bold">{{ $totalOrders }}</p>
    </div>

</div>

@endsection
