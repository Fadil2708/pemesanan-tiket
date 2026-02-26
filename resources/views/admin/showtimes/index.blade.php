@extends('layouts.admin')

@section('title', 'Manajemen Showtime')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <span>🕒</span> Daftar Showtime
        </h1>
        <p class="text-gray-500 text-sm mt-1">Total {{ $showtimes->count() }} jadwal</p>
    </div>

    <a href="{{ route('showtimes.create') }}"
       class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-semibold transition flex items-center gap-2">
        <span>➕</span> Tambah Showtime
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-3">
        <span>⚠️</span> {{ session('error') }}
    </div>
@endif

@if($showtimes->count() == 0)
<div class="bg-white shadow rounded-lg p-10 text-center">
    <div class="text-6xl mb-4">🕒</div>
    <p class="text-gray-500 text-lg mb-4">Belum ada showtime</p>
    <a href="{{ route('showtimes.create') }}" class="text-red-600 hover:underline font-semibold">
        Tambah showtime pertama Anda
    </a>
</div>
@else

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-4 text-left font-semibold text-gray-700">Film</th>
                <th class="p-4 text-left font-semibold text-gray-700">Studio</th>
                <th class="p-4 text-left font-semibold text-gray-700">Tanggal</th>
                <th class="p-4 text-left font-semibold text-gray-700">Waktu</th>
                <th class="p-4 text-left font-semibold text-gray-700">Harga</th>
                <th class="p-4 text-right font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($showtimes as $showtime)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-4">
                    <p class="font-semibold text-gray-800">{{ $showtime->film->title }}</p>
                    @if($showtime->film->poster)
                        <img src="{{ asset('storage/'.$showtime->film->poster) }}" 
                             class="w-12 h-16 object-cover rounded mt-2">
                    @endif
                </td>
                <td class="p-4">
                    <p class="font-medium">{{ $showtime->studio->name }}</p>
                    <span class="text-xs px-2 py-0.5 bg-gray-200 rounded">{{ $showtime->studio->type }}</span>
                </td>
                <td class="p-4">
                    <p class="text-gray-700">{{ \Carbon\Carbon::parse($showtime->show_date)->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($showtime->show_date)->diffForHumans() }}</p>
                </td>
                <td class="p-4">
                    <p class="font-medium">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</p>
                    <p class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}</p>
                </td>
                <td class="p-4">
                    <span class="font-semibold text-red-600">
                        Rp {{ number_format($showtime->price, 0, ',', '.') }}
                    </span>
                </td>
                <td class="p-4">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('showtimes.edit', $showtime->id) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('showtimes.destroy', $showtime->id) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus showtime ini?')">
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
