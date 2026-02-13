@extends('layouts.app')

@section('content')
<h2>Daftar Film</h2>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
    @foreach($films as $film)
        <div style="border:1px solid #ccc;padding:15px;">
            <h3>{{ $film->title }}</h3>
            <p>Durasi: {{ $film->duration }} menit</p>
            <a href="/film/{{ $film->id }}">Lihat Detail</a>
        </div>
    @endforeach
</div>
@endsection
