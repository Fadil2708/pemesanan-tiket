@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-12">

    {{-- HERO SECTION --}}
    <div class="stat-card bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 p-10 rounded-2xl border border-gray-800 shadow-xl transition opacity-0 translate-y-10">
        <h1 class="text-4xl font-bold mb-3">
            Welcome back, {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-gray-400">
            Siap untuk pengalaman nonton berikutnya?
        </p>

        <div class="mt-6">
            <a href="{{ route('home') }}"
               class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg font-semibold transition shadow-lg">
                🎬 Jelajahi Film
            </a>
        </div>
    </div>


    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Total Orders --}}
        <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-red-500 transition opacity-0 translate-y-10">
            <p class="text-gray-400 text-sm mb-2">Total Pesanan</p>
            <h2 class="text-4xl font-bold text-red-500 counter" 
                data-target="{{ $myOrders }}">
                0
            </h2>

        </div>

        {{-- Dummy Loyalty Points (Future Upgrade) --}}
        <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-red-500 transition opacity-0 translate-y-10">
            <p class="text-gray-400 text-sm mb-2">Loyalty Points</p>
            <h2 class="text-4xl font-bold text-green-400 counter" 
                data-target="{{ $myOrders * 10 }}">
                0
            </h2>

        </div>

        {{-- Membership Level --}}
        <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-red-500 transition opacity-0 translate-y-10">
            <p class="text-gray-400 text-sm mb-2">Membership</p>
            <h2 class="text-2xl font-bold text-yellow-400">
                Silver Member
            </h2>
        </div>

    </div>


    {{-- RECENT ORDERS --}}
    <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-red-500 transition opacity-0 translate-y-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">
                🎟 Pesanan Terakhir
            </h2>

            <a href="{{ route('my.orders') }}"
               class="text-sm text-red-500 hover:text-red-400 transition">
                Lihat Semua →
            </a>
        </div>

        @php
            $recentOrders = \App\Models\Order::where('user_id', auth()->id())
                                ->latest()
                                ->take(3)
                                ->get();
        @endphp

        @if($recentOrders->count())

            <div class="space-y-4">

                @foreach($recentOrders as $order)
                    <div class="flex justify-between items-center bg-gray-800 p-4 rounded-lg border border-gray-700">

                        <div>
                            <p class="font-semibold">
                                Booking Code: {{ $order->booking_code }}
                            </p>
                            <p class="text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-red-500 font-bold">
                                Rp {{ number_format($order->total_price,0,',','.') }}
                            </p>
                            <p class="text-xs text-gray-400 capitalize">
                                {{ $order->status }}
                            </p>
                        </div>

                    </div>
                @endforeach

            </div>

        @else

            <div class="text-gray-400">
                Belum ada pesanan.
            </div>

        @endif

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const counters = document.querySelectorAll('.counter');

    const animateCounter = (counter) => {

        const target = +counter.getAttribute('data-target');
        let count = 0;
        const duration = 1500; // 1.5 detik
        const startTime = performance.now();

        function update(currentTime) {
            const progress = Math.min((currentTime - startTime) / duration, 1);

            // Ease-out effect
            const easeOut = 1 - Math.pow(1 - progress, 3);

            counter.innerText = Math.floor(easeOut * target);

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                counter.innerText = target;
            }
        }

        requestAnimationFrame(update);
    };

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {

            if (entry.isIntersecting) {

                const counter = entry.target;

                if (!counter.classList.contains('animated')) {
                    counter.classList.add('animated');
                    animateCounter(counter);
                }

                obs.unobserve(counter); // hanya jalan sekali
            }

        });
    }, {
        threshold: 0.5
    });

    counters.forEach(counter => {
        observer.observe(counter);
    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const cards = document.querySelectorAll('.stat-card');

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {

                entry.target.classList.remove('opacity-0', 'translate-y-10');
                entry.target.classList.add('opacity-100', 'translate-y-0', 'transition-all', 'duration-700');

                observer.unobserve(entry.target);
            }

        });

    }, {
        threshold: 0.2
    });

    cards.forEach(card => {
        observer.observe(card);
    });

});
</script>

@endsection
