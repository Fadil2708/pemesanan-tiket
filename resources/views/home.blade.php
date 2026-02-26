@extends('layouts.app')

@section('title', 'BioskopApp - Nonton Film Terbaru')

@section('content')

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
    class="relative h-[70vh] md:h-[80vh] overflow-hidden">

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
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/50 to-transparent"></div>
        </div>
    </template>

    {{-- Content --}}
    <div class="relative z-10 px-4 md:px-12 max-w-4xl h-full flex flex-col justify-end pb-20">
        <template x-for="(film, index) in films" :key="film.id">
            <div x-show="active === index"
                 x-transition:enter="transition duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute inset-0 px-4 md:px-12 flex flex-col justify-end pb-20">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-lg" x-text="film.title"></h1>
                <p class="text-gray-200 mb-6 text-sm md:text-base line-clamp-3"
                   x-text="film.description ? film.description.substring(0,200)+'...' : ''">
                </p>
                <div class="flex flex-wrap gap-4 mb-6 text-xs md:text-sm text-gray-300">
                    <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full" x-text="'⏱ ' + film.duration + ' menit'"></span>
                    <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full" x-text="'🎟 ' + (film.age_rating ?? 'Semua Umur')"></span>
                </div>
                <a :href="'/film/' + film.id"
                   class="btn-primary inline-block px-8 py-3 rounded-xl font-semibold transition shadow-lg text-center w-fit">
                    🎬 Pesan Tiket
                </a>
            </div>
        </template>
    </div>

    {{-- Indicator Dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3">
        <template x-for="(film, index) in films" :key="index">
            <div @click="active = index"
                 :class="active === index ? 'bg-red-600 w-8' : 'bg-white/50 w-3'"
                 class="h-3 rounded-full cursor-pointer transition-all duration-300">
            </div>
        </template>
    </div>
</section>
@endif

{{-- NOW SHOWING --}}
<section class="px-4 md:px-8 py-16">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
                    <span class="text-red-500">🎥</span> Now Showing
                </h2>
                <p class="text-gray-400 text-sm mt-1">{{ $films->count() }} Film Tersedia</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @foreach($films as $film)
            <div class="film-card group bg-gray-900 rounded-xl overflow-hidden shadow-lg hover:shadow-red-600/30 transition">
                {{-- Poster --}}
                <div class="relative overflow-hidden aspect-[2/3]">
                    @if($film->poster)
                        <img src="{{ asset('storage/'.$film->poster) }}"
                             class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    @else
                        <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                            <span class="text-gray-600">No Poster</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="text-base font-semibold mb-2 truncate" title="{{ $film->title }}">
                        {{ $film->title }}
                    </h3>
                    <div class="text-xs text-gray-400 mb-3 flex justify-between">
                        <span>⏱ {{ $film->duration }} min</span>
                        <span class="bg-gray-800 px-2 py-0.5 rounded">{{ $film->age_rating ?? 'All' }}</span>
                    </div>
                    <a href="{{ route('film.detail',$film->id) }}"
                       class="btn-primary block w-full text-center py-2 rounded-lg text-sm font-semibold">
                        Pesan Tiket
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
