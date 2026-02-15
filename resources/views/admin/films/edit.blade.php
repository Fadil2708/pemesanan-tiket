@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-6">Edit Film</h1>

<form method="POST" action="{{ route('films.update', $film->id) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $film->title }}"
        class="w-full border p-3 rounded">

    <input type="number" name="duration" value="{{ $film->duration }}"
        class="w-full border p-3 rounded">

    <textarea name="description"
        class="w-full border p-3 rounded">{{ $film->description }}</textarea>

    <button class="bg-blue-600 text-white px-6 py-2 rounded">
        Update
    </button>
</form>

@endsection
