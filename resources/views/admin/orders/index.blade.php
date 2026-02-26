@extends('layouts.admin')

@section('title','Orders')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">🎟 Daftar Orders</h1>
    
    {{-- Filter Stats --}}
    <div class="flex gap-3 text-sm">
        <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-full font-medium">
            Pending: {{ \App\Models\Order::where('status', 'pending')->count() }}
        </span>
        <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-full font-medium">
            Paid: {{ \App\Models\Order::where('status', 'paid')->count() }}
        </span>
        <span class="px-3 py-1.5 bg-red-100 text-red-700 rounded-full font-medium">
            Canceled: {{ \App\Models\Order::where('status', 'canceled')->count() }}
        </span>
    </div>
</div>

@if($orders->count() == 0)
<div class="bg-white shadow rounded-lg p-10 text-center">
    <p class="text-gray-500 text-lg">Belum ada order</p>
</div>
@else

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-4 text-left font-semibold text-gray-700">Booking Code</th>
                <th class="p-4 text-left font-semibold text-gray-700">User</th>
                <th class="p-4 text-left font-semibold text-gray-700">Film</th>
                <th class="p-4 text-left font-semibold text-gray-700">Tgl & Jam</th>
                <th class="p-4 text-left font-semibold text-gray-700">Kursi</th>
                <th class="p-4 text-left font-semibold text-gray-700">Total</th>
                <th class="p-4 text-left font-semibold text-gray-700">Status</th>
                <th class="p-4 text-center font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-4">
                    <span class="font-mono font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">
                        {{ $order->booking_code }}
                    </span>
                </td>
                <td class="p-4">
                    <div>
                        <p class="font-medium">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                    </div>
                </td>
                <td class="p-4">
                    <p class="font-medium">{{ $order->showtime->film->title }}</p>
                    <p class="text-xs text-gray-500">{{ $order->showtime->studio->name }}</p>
                </td>
                <td class="p-4 text-sm">
                    <p>{{ \Carbon\Carbon::parse($order->showtime->show_date)->format('d M Y') }}</p>
                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('H:i') }}</p>
                </td>
                <td class="p-4">
                    <div class="flex flex-wrap gap-1">
                        @foreach($order->tickets->take(3) as $ticket)
                            <span class="bg-gray-200 px-2 py-0.5 rounded text-xs font-medium">
                                {{ $ticket->seat->seat_number }}
                            </span>
                        @endforeach
                        @if($order->tickets->count() > 3)
                            <span class="text-xs text-gray-500">+{{ $order->tickets->count() - 3 }}</span>
                        @endif
                    </div>
                </td>
                <td class="p-4">
                    <span class="font-semibold text-red-600">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </td>
                <td class="p-4">
                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold
                        @if($order->status === 'paid') bg-green-500 text-white
                        @elseif($order->status === 'pending') bg-yellow-500 text-white
                        @else bg-red-500 text-white @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    @if($order->status === 'pending' && $order->expires_at)
                        <p class="text-xs text-orange-600 mt-1">
                            ⏰ {{ $order->expires_at->diffForHumans() }}
                        </p>
                    @endif
                </td>
                <td class="p-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('orders.show', $order->id) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm transition">
                            Detail
                        </a>
                        @if($order->status === 'pending')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm transition"
                                onclick="return confirm('Batalkan order ini?')">
                                Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
