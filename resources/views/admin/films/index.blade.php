@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Film</h1>

    <a href="{{ route('films.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded">
        + Tambah Film
    </a>
</div>

@if(session('success'))
    <div class="bg-green-500 text-white p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3 text-left">Poster</th>
            <th class="p-3 text-left">Judul</th>
            <th class="p-3 text-left">Durasi</th>
            <th class="p-3 text-left">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($films as $film)
        <tr class="border-t">
            <td class="p-3">
                @if($film->poster)
                    <img src="{{ asset('storage/'.$film->poster) }}"
                        class="w-16 rounded">
                @endif
            </td>
            <td class="p-3">{{ $film->title }}</td>
            <td class="p-3">{{ $film->duration }} menit</td>
            <td class="p-3 flex gap-2">

                <a href="{{ route('films.edit', $film->id) }}"
                   class="bg-blue-500 text-white px-3 py-1 rounded">
                    Edit
                </a>

                <form action="{{ route('films.destroy', $film->id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="bg-red-500 text-white px-3 py-1 rounded">
                        Hapus
                    </button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
