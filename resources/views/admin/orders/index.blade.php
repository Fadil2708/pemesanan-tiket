@extends('layouts.admin')

@section('title','Orders')

@section('content')

<h1 class="text-2xl font-bold mb-6">Daftar Orders</h1>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3 text-left">Booking Code</th>
            <th class="p-3 text-left">User</th>
            <th class="p-3 text-left">Film</th>
            <th class="p-3 text-left">Total</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr class="border-t">
            <td class="p-3">{{ $order->booking_code }}</td>
            <td class="p-3">{{ $order->user->name }}</td>
            <td class="p-3">{{ $order->showtime->film->title }}</td>
            <td class="p-3">
                Rp {{ number_format($order->total_price,0,',','.') }}
            </td>
            <td class="p-3">
                <span class="px-2 py-1 rounded text-white
                    @if($order->status === 'paid') bg-green-500
                    @elseif($order->status === 'pending') bg-yellow-500
                    @else bg-red-500 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </td>
            <td class="p-3">
                <a href="{{ route('orders.show',$order->id) }}"
                   class="bg-blue-500 text-white px-3 py-1 rounded">
                    Detail
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
