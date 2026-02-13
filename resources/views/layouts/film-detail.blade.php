@extends('layouts.app')

@section('content')
<h2>{{ $film->title }}</h2>
<p>{{ $film->description }}</p>

<h3>Jadwal Tayang</h3>

@foreach($film->showtimes as $showtime)
    <div style="margin-bottom:10px;">
        {{ $showtime->show_date }} - {{ $showtime->start_time }}
        <a href="/showtime/{{ $showtime->id }}"
   class="bg-black text-white px-4 py-2 rounded">
    Pilih Kursi
</a>

    </div>
@endforeach

@endsection
