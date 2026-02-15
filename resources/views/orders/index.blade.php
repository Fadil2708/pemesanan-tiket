@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-10">🎟 Pesanan Saya</h1>

    @if($orders->count() == 0)
        <div class="text-gray-400">
            Belum ada pesanan.
        </div>
    @else

    <div class="grid gap-6">

        @foreach($orders as $order)
        <div class="bg-gray-900 p-6 rounded-xl border border-gray-800 flex justify-between items-center">

            <div>
                <h3 class="text-xl font-semibold">
                    {{ $order->showtime->film->title }}
                </h3>

                <p class="text-gray-400 text-sm">
                    {{ $order->showtime->show_date }}
                </p>

                <p class="mt-2 text-sm">
                    Booking Code:
                    <span class="text-red-500 font-semibold">
                        {{ $order->booking_code }}
                    </span>
                </p>
            </div>

            <div class="text-right">

                <div class="mb-3">
                    <span class="px-3 py-1 rounded text-sm
                        @if($order->status === 'paid') bg-green-600
                        @elseif($order->status === 'pending') bg-yellow-600
                        @else bg-red-600 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <a href="{{ route('my.orders.show',$order->id) }}"
                   class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition">
                    Detail
                </a>

            </div>

        </div>
        @endforeach

    </div>

    @endif

</div>

@endsection
