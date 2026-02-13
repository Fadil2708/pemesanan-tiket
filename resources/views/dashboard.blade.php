@extends('layouts.app')

@section('content')
<div style="padding:40px;">

    <h2>Welcome, {{ auth()->user()->name }} 👋</h2>

    <div style="display:flex;gap:30px;margin-top:30px;">

        <div style="padding:20px;border:1px solid #ccc;">
            <h3>Total Film</h3>
            <h1>{{ $totalFilms }}</h1>
        </div>

        <div style="padding:20px;border:1px solid #ccc;">
            <h3>Total Showtime</h3>
            <h1>{{ $totalShowtimes }}</h1>
        </div>

        <div style="padding:20px;border:1px solid #ccc;">
            <h3>Pesanan Saya</h3>
            <h1>{{ $myOrders }}</h1>
        </div>

    </div>

    <div style="margin-top:40px;">
        <a href="/" style="padding:10px 20px;background:black;color:white;">
            Lihat Film
        </a>
    </div>

</div>
@endsection