@extends('layouts.admin')

@section('title', 'Manajemen Film')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <span>🎬</span> Daftar Film
        </h1>
        <p class="text-gray-500 text-sm mt-1">Total {{ $films->count() }} film</p>
    </div>

    <a href="{{ route('films.create') }}"
       class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-semibold transition flex items-center gap-2">
        <span>➕</span> Tambah Film
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

@if($films->count() == 0)
<div class="bg-white shadow rounded-lg p-10 text-center">
    <div class="text-6xl mb-4">🎬</div>
    <p class="text-gray-500 text-lg mb-4">Belum ada film</p>
    <a href="{{ route('films.create') }}" class="text-red-600 hover:underline font-semibold">
        Tambah film pertama Anda
    </a>
</div>
@else

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-4 text-left font-semibold text-gray-700">Poster</th>
                <th class="p-4 text-left font-semibold text-gray-700">Judul</th>
                <th class="p-4 text-left font-semibold text-gray-700">Durasi</th>
                <th class="p-4 text-left font-semibold text-gray-700">Rating</th>
                <th class="p-4 text-left font-semibold text-gray-700">Release Date</th>
                <th class="p-4 text-right font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($films as $film)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-4">
                    @if($film->poster)
                        <img src="{{ asset('storage/'.$film->poster) }}"
                            class="w-16 h-24 object-cover rounded-lg shadow-sm">
                    @else
                        <div class="w-16 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-400 text-xs">No Image</span>
                        </div>
                    @endif
                </td>
                <td class="p-4">
                    <p class="font-semibold text-gray-800">{{ $film->title }}</p>
                    <p class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($film->description, 50) }}</p>
                </td>
                <td class="p-4">
                    <span class="text-gray-700">{{ $film->duration }} menit</span>
                </td>
                <td class="p-4">
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-xs font-medium">
                        {{ $film->age_rating ?? 'All Ages' }}
                    </span>
                </td>
                <td class="p-4">
                    <span class="text-gray-700">
                        {{ $film->release_date ? \Carbon\Carbon::parse($film->release_date)->format('d M Y') : '-' }}
                    </span>
                </td>
                <td class="p-4">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('films.edit', $film->id) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('films.destroy', $film->id) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
