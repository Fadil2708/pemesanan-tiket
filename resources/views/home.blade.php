@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h2 class="text-3xl font-bold mb-8 text-center">
        🎬 Daftar Film
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

        @foreach($films as $film)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition p-6 border">

                <h3 class="text-xl font-semibold mb-2">
                    {{ $film->title }}
                </h3>

                <p class="text-gray-500 mb-4">
                    Durasi: {{ $film->duration }} menit
                </p>

                <div class="mt-4">
                    <a href="{{ route('film.detail', $film->id) }}"
                       class="inline-block bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                        🎥 Detail Film
                    </a>
                </div>

            </div>
        @endforeach

    </div>

</div>

@endsection
