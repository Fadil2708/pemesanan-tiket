@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold mb-2">
            🎟 Pilih Kursi
        </h1>
        <p class="text-gray-400">
            {{ $showtime->film->title }} • 
            {{ \Carbon\Carbon::parse($showtime->show_date)->format('d M Y') }} • 
            {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
        </p>
    </div>

    {{-- SCREEN --}}
    <div class="mb-12 text-center">
        <div class="bg-gradient-to-r from-gray-700 via-gray-500 to-gray-700 h-3 rounded-full mb-2"></div>
        <p class="text-gray-400 text-sm tracking-widest">L A Y A R</p>
    </div>

    <form method="POST" action="{{ route('checkout') }}">
        @csrf

        {{-- SEAT GRID --}}
        <div class="grid grid-cols-8 gap-4 justify-center mb-12">

            @foreach($showtime->showtimeSeats as $seat)

                @if($seat->status == 'available')
                    <label class="cursor-pointer">
                        <input type="checkbox"
                               name="showtime_seat_ids[]"
                               value="{{ $seat->id }}"
                               data-price="{{ $showtime->price }}"
                               class="hidden peer seat-checkbox">

                        <div class="py-3 rounded-lg text-sm font-semibold text-center
                                    bg-green-500 hover:bg-green-600
                                    peer-checked:bg-green-700
                                    transition duration-200">
                            {{ $seat->seat->seat_number }}
                        </div>
                    </label>

                @elseif($seat->status == 'locked')

                    <div class="py-3 rounded-lg text-sm font-semibold text-center bg-orange-500 opacity-70 cursor-not-allowed">
                        {{ $seat->seat->seat_number }}
                    </div>

                @else

                    <div class="py-3 rounded-lg text-sm font-semibold text-center bg-red-600 opacity-80 cursor-not-allowed">
                        {{ $seat->seat->seat_number }}
                    </div>

                @endif

            @endforeach

        </div>

        {{-- LEGEND --}}
        <div class="flex justify-center gap-8 mb-10 text-sm text-gray-400">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-500 rounded"></div>
                Available
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-orange-500 rounded"></div>
                Locked
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-600 rounded"></div>
                Booked
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 max-w-xl mx-auto">

            <div class="flex justify-between mb-4">
                <span>Kursi Dipilih:</span>
                <span id="selectedSeats" class="font-semibold text-white">-</span>
            </div>

            <div class="flex justify-between mb-6">
                <span>Total Harga:</span>
                <span id="totalPrice" class="font-bold text-red-500">
                    Rp 0
                </span>
            </div>

            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg font-semibold transition">
                🎬 Checkout
            </button>

        </div>

    </form>

</div>


{{-- JAVASCRIPT TOTAL CALC --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.seat-checkbox');
    const totalPriceEl = document.getElementById('totalPrice');
    const selectedSeatsEl = document.getElementById('selectedSeats');

    function updateTotal() {
        let total = 0;
        let seats = [];

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseFloat(cb.dataset.price);
                seats.push(cb.closest('label').innerText.trim());
            }
        });

        totalPriceEl.innerText = "Rp " + total.toLocaleString('id-ID');
        selectedSeatsEl.innerText = seats.length ? seats.join(', ') : '-';
    }

    checkboxes.forEach(cb => {

        cb.addEventListener('change', function () {

            if (this.checked) {

                fetch("/lock-seat/" + this.value, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {

                    if (data.message !== "Seat locked successfully") {
                        alert(data.message);
                        this.checked = false;
                    } else {
                        updateTotal();
                    }

                })
                .catch(() => {
                    alert("Gagal lock seat");
                    this.checked = false;
                });

            } else {
                updateTotal();
            }

        });

    });

});
</script>

@endsection
