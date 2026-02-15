@extends('layouts.admin')

@section('title', 'Tambah Showtime')

@section('content')

<h1 class="text-2xl font-bold mb-6">Tambah Showtime</h1>

<form method="POST" action="{{ route('showtimes.store') }}"
      class="space-y-4">
    @csrf

    <select name="film_id"
        class="w-full border p-3 rounded">
        <option value="">Pilih Film</option>
        @foreach($films as $film)
            <option value="{{ $film->id }}">
                {{ $film->title }}
            </option>
        @endforeach
    </select>

    <select name="studio_id"
        class="w-full border p-3 rounded">
        <option value="">Pilih Studio</option>
        @foreach($studios as $studio)
            <option value="{{ $studio->id }}">
                {{ $studio->name }}
            </option>
        @endforeach
    </select>

    <input type="date" name="show_date"
        class="w-full border p-3 rounded">

    <input type="time" name="start_time"
        class="w-full border p-3 rounded">

    <input type="time" name="end_time"
        class="w-full border p-3 rounded">

    <input type="number" name="price"
        placeholder="Harga"
        class="w-full border p-3 rounded">

    <button class="bg-red-600 text-white px-6 py-2 rounded">
        Simpan
    </button>

</form>

@endsection
