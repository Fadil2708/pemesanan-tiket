@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <h1 class="text-3xl font-bold mb-8">
        Detail Pesanan
    </h1>

    <div class="bg-gray-900 p-8 rounded-xl border border-gray-800 space-y-6">

        <div>
            <strong>Film:</strong>
            {{ $order->showtime->film->title }}
        </div>

        <div>
            <strong>Studio:</strong>
            {{ $order->showtime->studio->name }}
        </div>

        <div>
            <strong>Tanggal:</strong>
            {{ $order->showtime->show_date }}
        </div>

        <div>
            <strong>Kursi:</strong>
            @foreach($order->tickets as $ticket)
                <span class="inline-block bg-gray-800 px-3 py-1 rounded mr-2">
                    {{ $ticket->seat->seat_number }}
                </span>
            @endforeach
        </div>

        <div>
            <strong>Total:</strong>
            Rp {{ number_format($order->total_price,0,',','.') }}
        </div>

        <div>
            <strong>Status:</strong>
            {{ ucfirst($order->status) }}
        </div>

    </div>

</div>

@endsection
