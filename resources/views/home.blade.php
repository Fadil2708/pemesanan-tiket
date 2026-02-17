@extends('layouts.auth')

@section('content')

<div class="bg-black text-white min-h-screen">

    {{-- HERO AUTO SLIDER --}}
@if($heroFilms->count())

<section 
    x-data="{
        active: 0,
        films: {{ $heroFilms->toJson() }},
        init() {
            setInterval(() => {
                this.active = (this.active + 1) % this.films.length
            }, 5000)
        }
    }"
    class="relative h-[90vh] overflow-hidden">

    {{-- Background Images --}}
    <template x-for="(film, index) in films" :key="film.id">
        <div x-show="active === index"
             x-transition:enter="transition-opacity duration-1000"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-1000"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0">

            <img :src="'/storage/' + film.poster"
                 class="w-full h-full object-cover">
        </div>
    </template>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-black/40"></div>

    {{-- Content --}}
    <div class="relative z-10 px-12 max-w-3xl h-full flex flex-col justify-center">

        <template x-for="(film, index) in films" :key="film.id">
            <div x-show="active === index"
                 x-transition:enter="transition duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute">

                <h1 class="text-5xl md:text-6xl font-bold mb-6" x-text="film.title"></h1>

                <p class="text-gray-300 mb-6"
                   x-text="film.description ? film.description.substring(0,200)+'...' : ''">
                </p>

                <div class="flex gap-6 mb-6 text-sm text-gray-400">
                    <span x-text="'⏱ ' + film.duration + ' menit'"></span>
                    <span x-text="'🎟 ' + (film.age_rating ?? 'Semua Umur')"></span>
                </div>

                <a :href="'/film/' + film.id"
                   class="bg-red-600 hover:bg-red-700 px-8 py-3 rounded-lg font-semibold transition shadow-lg">
                    🎬 Pesan Sekarang
                </a>

            </div>
        </template>

    </div>

    {{-- Indicator Dots --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3">
        <template x-for="(film, index) in films" :key="index">
            <div @click="active = index"
                 :class="active === index ? 'bg-red-600' : 'bg-gray-500'"
                 class="w-3 h-3 rounded-full cursor-pointer transition">
            </div>
        </template>
    </div>

</section>

@endif


    {{-- NOW SHOWING --}}
    <section id="now-showing" class="px-8 py-20">

        <div class="flex justify-between items-center mb-12">
            <h2 class="text-3xl font-bold">
                🎥 Now Showing
            </h2>

            <span class="text-gray-500 text-sm">
                {{ $films->count() }} Film Tersedia
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">

            @foreach($films as $film)

            <div class="group bg-gray-900 rounded-xl overflow-hidden shadow-lg hover:shadow-red-600/30 transition duration-500">

                {{-- Poster --}}
                <div class="relative overflow-hidden">
                    @if($film->poster)
                        <img src="{{ asset('storage/'.$film->poster) }}"
                             class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-500">
                    @else
                        <div class="w-full h-80 bg-gray-800 flex items-center justify-center">
                            No Poster
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                </div>

                {{-- Info --}}
                <div class="p-4">

                    <h3 class="text-lg font-semibold mb-2 truncate">
                        {{ $film->title }}
                    </h3>

                    <div class="text-sm text-gray-400 mb-3 flex justify-between">
                        <span>⏱ {{ $film->duration }} min</span>
                        <span>🎟 {{ $film->age_rating ?? 'All' }}</span>
                    </div>

                    <a href="{{ route('film.detail',$film->id) }}"
                       class="block w-full text-center bg-red-600 hover:bg-red-700 py-2 rounded-lg text-sm font-semibold transition">
                        Pesan Tiket
                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </section>

</div>

@endsection
