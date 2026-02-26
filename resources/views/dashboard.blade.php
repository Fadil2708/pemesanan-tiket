@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 md:px-8 py-12 space-y-8">

    {{-- HERO SECTION --}}
    <div class="stat-card bg-gradient-to-r from-red-900/50 via-gray-900 to-red-900/50 p-8 md:p-10 rounded-2xl border border-red-500/30 shadow-xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">
                    Welcome back, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-gray-400">
                    Siap untuk pengalaman nonton berikutnya?
                </p>
            </div>
            <a href="{{ route('home') }}"
               class="btn-primary px-6 py-3 rounded-xl font-semibold transition shadow-lg whitespace-nowrap">
                🎬 Jelajahi Film
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Orders --}}
        <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-red-500/50 transition shadow-lg">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-500/20 rounded-xl flex items-center justify-center text-2xl">
                    🎟
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Total Pesanan</p>
                    <h2 class="text-3xl font-bold text-red-500 counter" data-target="{{ $myOrders }}">0</h2>
                </div>
            </div>
        </div>

        {{-- Loyalty Points --}}
        <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-green-500/50 transition shadow-lg">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-500/20 rounded-xl flex items-center justify-center text-2xl">
                    ⭐
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Loyalty Points</p>
                    <h2 class="text-3xl font-bold text-green-400 counter" data-target="{{ $myOrders * 10 }}">0</h2>
                </div>
            </div>
        </div>

        {{-- Membership Level --}}
        <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-yellow-500/50 transition shadow-lg">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-yellow-500/20 rounded-xl flex items-center justify-center text-2xl">
                    👑
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Membership</p>
                    <h2 class="text-xl font-bold text-yellow-400">
                        @if($myOrders >= 10) Gold Member
                        @elseif($myOrders >= 5) Silver Member
                        @else Bronze Member @endif
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ORDERS --}}
    <div class="stat-card bg-gray-900 p-6 rounded-xl border border-gray-800 hover:border-red-500/50 transition shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl md:text-2xl font-bold flex items-center gap-2">
                📋 Pesanan Terakhir
            </h2>
            <a href="{{ route('my.orders') }}"
               class="text-sm text-red-500 hover:text-red-400 transition font-semibold">
                Lihat Semua →
            </a>
        </div>

        @php
            $recentOrders = \App\Models\Order::where('user_id', auth()->id())
                                ->latest()
                                ->take(5)
                                ->get();
        @endphp

        @if($recentOrders->count())
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                    <a href="{{ route('my.orders.show', $order->id) }}"
                       class="flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-800/50 hover:bg-gray-800 p-4 rounded-xl border border-gray-700 hover:border-red-500/30 transition gap-4">
                        <div class="flex-1">
                            <p class="font-semibold text-white">
                                🎫 {{ $order->showtime->film->title }}
                            </p>
                            <p class="text-sm text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-red-500 font-bold">
                                    Rp {{ number_format($order->total_price,0,',','.') }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full capitalize
                                    @if($order->status === 'paid') bg-green-500/20 text-green-400
                                    @elseif($order->status === 'pending') bg-yellow-500/20 text-yellow-400
                                    @else bg-red-500/20 text-red-400 @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <span class="text-gray-500">›</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <div class="text-6xl mb-4">🎬</div>
                <p class="mb-4">Belum ada pesanan</p>
                <a href="{{ route('home') }}" class="btn-primary inline-block px-6 py-2 rounded-lg font-semibold">
                    Pesan Film Sekarang
                </a>
            </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Counter animation
    const counters = document.querySelectorAll('.counter');
    const animateCounter = (counter) => {
        const target = +counter.getAttribute('data-target');
        let count = 0;
        const duration = 1500;
        const startTime = performance.now();

        function update(currentTime) {
            const progress = Math.min((currentTime - startTime) / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            counter.innerText = Math.floor(easeOut * target);
            if (progress < 1) requestAnimationFrame(update);
            else counter.innerText = target;
        }
        requestAnimationFrame(update);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));

    // Card fade-in animation
    const cards = document.querySelectorAll('.stat-card');
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                }, index * 100);
            }
        });
    }, { threshold: 0.2 });

    cards.forEach(card => {
        card.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-700');
        cardObserver.observe(card);
    });
});
</script>

@endsection
