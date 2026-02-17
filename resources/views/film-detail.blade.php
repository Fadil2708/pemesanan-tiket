@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- FILM HEADER --}}
    <div class="grid md:grid-cols-3 gap-10 mb-16">

        {{-- Poster --}}
        <div>
            @if($film->poster)
                <img src="{{ asset('storage/'.$film->poster) }}"
                     class="rounded-xl shadow-2xl w-full">
            @else
                <div class="bg-gray-800 h-[500px] rounded-xl flex items-center justify-center">
                    No Poster
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="md:col-span-2 flex flex-col justify-center">

            <h1 class="text-4xl font-bold mb-6">
                {{ $film->title }}
            </h1>

            <div class="flex gap-6 text-gray-400 text-sm mb-6">
                <span>⏱ {{ $film->duration }} menit</span>
                <span>🎟 {{ $film->age_rating ?? 'Semua Umur' }}</span>
                <span>📅 {{ $film->release_date }}</span>
            </div>

            <p class="text-gray-300 leading-relaxed mb-8">
                {{ $film->description }}
            </p>

            <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
                <h3 class="text-lg font-semibold mb-3">
                    Informasi Film
                </h3>

                <ul class="text-gray-400 text-sm space-y-2">
                    <li>✔ Kualitas gambar terbaik</li>
                    <li>✔ Audio Dolby Surround</li>
                    <li>✔ Studio nyaman & modern</li>
                </ul>
            </div>

        </div>

    </div>


    {{-- JADWAL TAYANG --}}
    <div>

        <h2 class="text-3xl font-bold mb-10">
            🎟 Pilih Jadwal Tayang
        </h2>

        @if($film->showtimes->count() == 0)

            <div class="bg-gray-900 p-6 rounded-xl border border-gray-800 text-gray-400">
                Belum ada jadwal tayang tersedia.
            </div>

        @else

        <div class="grid md:grid-cols-3 gap-6">

            @foreach($film->showtimes as $showtime)

            <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl hover:border-red-500 transition">

                <div class="mb-4">
                    <p class="text-gray-400 text-sm">
                        {{ \Carbon\Carbon::parse($showtime->show_date)->format('d M Y') }}
                    </p>

                    <h3 class="text-xl font-bold">
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                    </h3>
                </div>

                <div class="mb-4 text-sm text-gray-400">
                    Studio: {{ $showtime->studio->name ?? '-' }}
                </div>

                <div class="mb-6 text-lg font-semibold text-red-500">
                    Rp {{ number_format($showtime->price,0,',','.') }}
                </div>

                <a href="{{ route('showtime.detail',$showtime->id) }}"
                   class="block w-full text-center bg-red-600 hover:bg-red-700 py-2 rounded-lg font-semibold transition">
                    Pilih Kursi
                </a>

            </div>

            @endforeach

        </div>

        @endif

    </div>

</div>

@endsection
