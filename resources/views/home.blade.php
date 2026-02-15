@extends('layouts.auth')

@section('content')

<div class="bg-black text-white min-h-screen">

    {{-- HERO --}}
    <section class="relative h-[80vh] flex items-center justify-center text-center bg-gradient-to-br from-black via-gray-900 to-black">

        <div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                🎬 BioskopApp
            </h1>

            <p class="text-gray-400 mb-8 max-w-xl mx-auto">
                Rasakan pengalaman nonton modern.
                Pilih film favoritmu dan pesan kursi sekarang.
            </p>

            <a href="{{ route('register.role','customer') }}"
               class="bg-red-600 hover:bg-red-700 px-8 py-3 rounded-lg font-semibold transition">
                Mulai Sekarang
            </a>
        </div>

    </section>

    {{-- NOW SHOWING --}}
    <section class="px-8 py-16">

        <h2 class="text-3xl font-bold mb-10">
            🎥 Now Showing
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">

            @foreach($films as $film)

                <a href="{{ route('film.detail',$film->id) }}"
                   class="group relative overflow-hidden rounded-lg shadow-lg">

                    @if($film->poster)
                        <img src="{{ asset('storage/'.$film->poster) }}"
                             class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-500">
                    @else
                        <div class="w-full h-80 bg-gray-800 flex items-center justify-center">
                            No Poster
                        </div>
                    @endif

                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-80"></div>

                    {{-- Title --}}
                    <div class="absolute bottom-4 left-4 right-4">
                        <h3 class="text-lg font-semibold">
                            {{ $film->title }}
                        </h3>
                    </div>

                </a>

            @endforeach

        </div>

    </section>

</div>

@endsection
