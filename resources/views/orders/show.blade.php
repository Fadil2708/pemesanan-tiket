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
            <strong>Booking Code:</strong>
            <span class="text-red-500 font-semibold">{{ $order->booking_code }}</span>
        </div>

        <div>
            <strong>Status:</strong>
            <span class="px-3 py-1 rounded text-sm
                @if($order->status === 'paid') bg-green-600
                @elseif($order->status === 'pending') bg-yellow-600
                @else bg-red-600 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        @if($order->status === 'pending' && $order->expires_at)
        <div class="bg-yellow-900/20 border border-yellow-500 p-4 rounded">
            <strong>Waktu Tersisa:</strong>
            <div id="countdown-timer" class="text-yellow-400 font-semibold text-lg mt-2">
                Loading...
            </div>
        </div>
        @elseif($order->status === 'canceled')
        <div class="bg-red-900/20 border border-red-500 p-4 rounded">
            <p class="text-red-400">Pesanan ini telah dibatalkan karena melewati batas waktu pembayaran.</p>
        </div>
        @endif

        @if($order->status === 'pending')
        <div class="pt-4">
            <button id="pay-button" class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg font-semibold transition">
                Bayar Sekarang
            </button>
        </div>
        @endif

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($order->status === 'pending' && $order->expires_at)
        startCountdown({{ $order->expires_at->timestamp * 1000 }});
        startStatusPolling();
    @endif

    // Handle Bayar Sekarang button
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            // Kirim request ke endpoint pembayaran
            fetch('{{ route("my.orders.pay", $order->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect_url) {
                    // Redirect ke halaman pembayaran Midtrans
                    window.location.href = data.redirect_url;
                } else if (data.message) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memproses pembayaran. Silakan coba lagi.');
            });
        });
    }

    function startCountdown(expiresAt) {
        const element = document.getElementById('countdown-timer');
        const payButton = document.getElementById('pay-button');
        const expireTime = expiresAt;

        const interval = setInterval(function () {
            const now = new Date().getTime();
            const distance = expireTime - now;

            if (distance <= 0) {
                clearInterval(interval);
                element.innerText = "Expired";
                if (payButton) {
                    payButton.disabled = true;
                    payButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
                checkStatusAndUpdate(); // Check status immediately when expired
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            element.innerText = String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
        }, 1000);
    }

    function startStatusPolling() {
        setInterval(function () {
            checkStatusAndUpdate();
        }, 10000); // Poll every 10 seconds
    }

    function checkStatusAndUpdate() {
        fetch('{{ route("my.orders.status", $order->id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.status !== '{{ $order->status }}') {
                    // Status changed, reload page to update UI
                    location.reload();
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }
});
</script>

@endsection
