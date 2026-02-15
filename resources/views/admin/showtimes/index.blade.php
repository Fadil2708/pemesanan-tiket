@extends('layouts.admin')

@section('title', 'Showtime')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Showtime</h1>

    <a href="{{ route('showtimes.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded">
        + Tambah Showtime
    </a>
</div>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3 text-left">Film</th>
            <th class="p-3 text-left">Studio</th>
            <th class="p-3 text-left">Tanggal</th>
            <th class="p-3 text-left">Jam</th>
            <th class="p-3 text-left">Harga</th>
            <th class="p-3">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($showtimes as $showtime)
        <tr class="border-t">
            <td class="p-3">{{ $showtime->film->title }}</td>
            <td class="p-3">{{ $showtime->studio->name }}</td>
            <td class="p-3">{{ $showtime->show_date }}</td>
            <td class="p-3">
                {{ $showtime->start_time }} - {{ $showtime->end_time }}
            </td>
            <td class="p-3">
                Rp {{ number_format($showtime->price,0,',','.') }}
            </td>
            <td class="p-3 flex gap-2">

                <a href="{{ route('showtimes.edit',$showtime->id) }}"
                   class="bg-blue-500 text-white px-3 py-1 rounded">
                    Edit
                </a>

                <form action="{{ route('showtimes.destroy',$showtime->id) }}"
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
