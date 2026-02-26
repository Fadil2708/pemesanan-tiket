@extends('layouts.admin')

@section('title','Detail Order')

@section('content')

<div class="max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            Detail Order - {{ $order->booking_code }}
        </h1>
        <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-800">
            ← Kembali
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6 space-y-6">

        {{-- Customer Info --}}
        <div class="border-b pb-4">
            <h3 class="font-semibold text-lg mb-3">👤 Informasi Pelanggan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-500">Nama:</span>
                    <p class="font-medium">{{ $order->user->name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Email:</span>
                    <p class="font-medium">{{ $order->user->email }}</p>
                </div>
                @if($order->user->phone)
                <div>
                    <span class="text-gray-500">Telepon:</span>
                    <p class="font-medium">{{ $order->user->phone }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Showtime Info --}}
        <div class="border-b pb-4">
            <h3 class="font-semibold text-lg mb-3">🎬 Informasi Film & Jadwal</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-500">Film:</span>
                    <p class="font-medium">{{ $order->showtime->film->title }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Studio:</span>
                    <p class="font-medium">{{ $order->showtime->studio->name }} ({{ $order->showtime->studio->type }})</p>
                </div>
                <div>
                    <span class="text-gray-500">Tanggal:</span>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($order->showtime->show_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Waktu:</span>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->showtime->end_time)->format('H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Seat Info --}}
        <div class="border-b pb-4">
            <h3 class="font-semibold text-lg mb-3">💺 Kursi Dipilih</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($order->tickets as $ticket)
                    <span class="bg-gray-800 text-white px-3 py-1.5 rounded-lg font-medium">
                        {{ $ticket->seat->seat_number }}
                    </span>
                @endforeach
            </div>
            <p class="text-sm text-gray-500 mt-2">Total {{ $order->tickets->count() }} kursi</p>
        </div>

        {{-- Payment Info --}}
        <div class="border-b pb-4">
            <h3 class="font-semibold text-lg mb-3">💳 Informasi Pembayaran</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-500">Total Harga:</span>
                    <p class="font-medium text-lg text-red-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Metode Pembayaran:</span>
                    <p class="font-medium">{{ ucfirst($order->payment_method ?? '-') }}</p>
                </div>
                @if($order->payment)
                <div>
                    <span class="text-gray-500">Status Pembayaran:</span>
                    <p class="font-medium">
                        <span class="px-2 py-1 rounded text-xs text-white
                            @if($order->payment->payment_status === 'success') bg-green-500
                            @elseif($order->payment->payment_status === 'failed') bg-red-500
                            @else bg-yellow-500 @endif">
                            {{ ucfirst($order->payment->payment_status) }}
                        </span>
                    </p>
                </div>
                @if($order->payment->paid_at)
                <div>
                    <span class="text-gray-500">Dibayar Pada:</span>
                    <p class="font-medium">{{ $order->payment->paid_at->format('d M Y H:i') }}</p>
                </div>
                @endif
                @endif
            </div>
        </div>

        {{-- Order Status --}}
        <div>
            <h3 class="font-semibold text-lg mb-3">📊 Status Order</h3>
            <div class="flex items-center gap-4">
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($order->status === 'paid') bg-green-500 text-white
                    @elseif($order->status === 'pending') bg-yellow-500 text-white
                    @else bg-red-500 text-white @endif">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="text-gray-500 text-sm">
                    Dibuat: {{ $order->created_at->format('d M Y H:i') }}
                </span>
            </div>
            @if($order->expires_at && $order->status === 'pending')
            <p class="text-sm text-orange-600 mt-2">
                ⏰ Expired: {{ $order->expires_at->format('d M Y H:i') }}
            </p>
            @endif
        </div>

        {{-- Actions --}}
        @if($order->status === 'pending')
        <div class="pt-4 border-t">
            <form method="POST" action="{{ route('orders.cancel', $order->id) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-semibold transition"
                    onclick="return confirm('Yakin ingin membatalkan order ini?')">
                    ❌ Batalkan Order
                </button>
            </form>
        </div>
        @endif

    </div>

</div>

@endsection
