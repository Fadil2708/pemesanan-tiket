@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 md:px-8 py-12">

    <div class="mb-10">
        <h1 class="text-3xl md:text-4xl font-bold mb-2 flex items-center gap-3">
            <span>🎟</span> Pesanan Saya
        </h1>
        <p class="text-gray-400">Riwayat pemesanan tiket bioskop Anda</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-yellow-500/10 border border-yellow-500/30 p-4 rounded-xl">
            <div class="text-2xl font-bold text-yellow-500">{{ $orders->where('status', 'pending')->count() }}</div>
            <div class="text-sm text-yellow-500/80">Pending</div>
        </div>
        <div class="bg-green-500/10 border border-green-500/30 p-4 rounded-xl">
            <div class="text-2xl font-bold text-green-500">{{ $orders->where('status', 'paid')->count() }}</div>
            <div class="text-sm text-green-500/80">Berhasil</div>
        </div>
        <div class="bg-red-500/10 border border-red-500/30 p-4 rounded-xl">
            <div class="text-2xl font-bold text-red-500">{{ $orders->where('status', 'canceled')->count() }}</div>
            <div class="text-sm text-red-500/80">Dibatalkan</div>
        </div>
    </div>

    @if($orders->count() == 0)
        <div class="text-center py-20 bg-gray-900 rounded-2xl border border-gray-800">
            <div class="text-7xl mb-6">🎬</div>
            <h3 class="text-xl font-semibold mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-400 mb-6">Mulai pesan tiket film favoritmu sekarang!</p>
            <a href="{{ route('home') }}" class="btn-primary inline-block px-8 py-3 rounded-xl font-semibold">
                Jelajahi Film
            </a>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($orders as $order)
            <div class="bg-gray-900/80 hover:bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-red-500/30 transition">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-semibold mb-2">
                            🎫 {{ $order->showtime->film->title }}
                        </h3>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                            <span>📅 {{ \Carbon\Carbon::parse($order->showtime->show_date)->format('d M Y') }}</span>
                            <span>🕐 {{ \Carbon\Carbon::parse($order->showtime->start_time)->format('H:i') }} WIB</span>
                            <span>🎪 {{ $order->showtime->studio->name }}</span>
                            <span>💺 {{ $order->tickets->count() }} Kursi</span>
                        </div>
                        <p class="mt-3 text-sm">
                            Booking Code:
                            <span class="text-red-500 font-mono font-semibold bg-red-500/10 px-3 py-1 rounded">
                                {{ $order->booking_code }}
                            </span>
                        </p>
                    </div>

                    <div class="text-right flex flex-col items-end gap-3">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($order->status === 'paid') bg-green-500/20 text-green-400 border border-green-500/30
                            @elseif($order->status === 'pending') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                            @else bg-red-500/20 text-red-400 border border-red-500/30 @endif">
                            @if($order->status === 'paid') ✅ Paid
                            @elseif($order->status === 'pending') ⏳ Pending
                            @else ❌ {{ ucfirst($order->status) }} @endif
                        </span>

                        @if($order->status === 'pending' && $order->expires_at)
                        <div class="text-sm">
                            <div id="countdown-{{ $order->id }}" class="text-red-400 font-semibold bg-red-500/10 px-3 py-1.5 rounded-lg">
                                Loading...
                            </div>
                        </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('my.orders.show',$order->id) }}"
                               class="btn-primary px-6 py-2 rounded-lg font-semibold inline-flex items-center gap-2 text-sm">
                                Detail <span>›</span>
                            </a>
                            @if($order->status === 'paid')
                            <a href="{{ route('my.orders.show',$order->id) }}"
                               class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg font-semibold inline-flex items-center gap-2 text-sm transition">
                                🎫 Tiket
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pendingOrders = [];
    const initialStatuses = {};

    @foreach($orders as $order)
        @if($order->status === 'pending' && $order->expires_at)
            startCountdown('{{ $order->id }}', {{ $order->expires_at->timestamp * 1000 }});
            pendingOrders.push('{{ $order->id }}');
            initialStatuses['{{ $order->id }}'] = '{{ $order->status }}';
        @endif
    @endforeach

    if (pendingOrders.length > 0) {
        startStatusPolling(pendingOrders, initialStatuses);
    }

    function startCountdown(orderId, expiresAt) {
        const element = document.getElementById('countdown-' + orderId);
        const expireTime = expiresAt;

        const interval = setInterval(function () {
            const now = new Date().getTime();
            const distance = expireTime - now;

            if (distance <= 0) {
                clearInterval(interval);
                element.innerText = "⏰ Expired";
                element.classList.add('line-through');
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            element.innerText = `⏱ ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }, 1000);
    }

    function startStatusPolling(orderIds, initialStatuses) {
        setInterval(function () {
            orderIds.forEach(orderId => {
                checkStatusAndUpdate(orderId, initialStatuses);
            });
        }, 10000);
    }

    function checkStatusAndUpdate(orderId, initialStatuses) {
        fetch(`/my-orders/${orderId}/status`)
            .then(response => response.json())
            .then(data => {
                if (data.status !== initialStatuses[orderId]) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>

@endsection
