@extends('layouts.admin')

@section('title','Detail Order')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Detail Order - {{ $order->booking_code }}
</h1>

<div class="bg-white shadow rounded p-6 space-y-4">

    <div>
        <strong>User:</strong>
        {{ $order->user->name }} ({{ $order->user->email }})
    </div>

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
            <span class="inline-block bg-gray-200 px-2 py-1 rounded">
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

    @if($order->status !== 'canceled')
    <form method="POST"
          action="{{ route('orders.cancel',$order->id) }}">
        @csrf
        @method('PATCH')

        <button class="bg-red-600 text-white px-4 py-2 rounded">
            Cancel Order
        </button>
    </form>
    @endif

</div>

@endsection
