@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-6">Tambah Film</h1>

<form method="POST" action="{{ route('films.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <input type="text" name="title" placeholder="Judul"
        class="w-full border p-3 rounded">
    
    <input type="file" name="poster"
       class="w-full border p-3 rounded">

    <input type="number" name="duration" placeholder="Durasi (menit)"
        class="w-full border p-3 rounded">

    <input type="text" name="age_rating" placeholder="Rating Usia"
        class="w-full border p-3 rounded">

    <input type="date" name="release_date"
        class="w-full border p-3 rounded">

    <textarea name="description" placeholder="Deskripsi"
        class="w-full border p-3 rounded"></textarea>

    <button class="bg-red-600 text-white px-6 py-2 rounded">
        Simpan
    </button>
</form>

@endsection
