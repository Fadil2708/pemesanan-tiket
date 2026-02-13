@extends('layouts.app')

@section('content')

<h2 class="text-3xl font-bold mb-4">{{ $film->title }}</h2>

<p class="text-gray-600 mb-6">
    Durasi: {{ $film->duration }} menit
</p>

<h3 class="text-xl font-semibold mb-4">Jadwal Tayang</h3>

@if($film->showtimes->count())

    <div class="space-y-3">
        @foreach($film->showtimes as $showtime)
            <div class="p-4 border rounded flex justify-between items-center">

                <div>
                    <div>{{ $showtime->show_date }}</div>
                    <div>{{ $showtime->start_time }} - {{ $showtime->end_time }}</div>
                </div>

                <a href="/showtime/{{ $showtime->id }}"
                   class="bg-black text-white px-4 py-2 rounded">
                    Pilih Kursi
                </a>

            </div>
        @endforeach
    </div>

@else
    <p>Belum ada jadwal tayang.</p>
@endif

@endsection
