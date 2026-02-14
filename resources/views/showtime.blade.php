@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <h2 class="text-2xl font-bold mb-6">
        Pilih Kursi
    </h2>

    <div class="mb-8 text-center font-semibold">
        🎬 LAYAR
        <hr class="mt-2">
    </div>

    <form method="POST" action="/checkout">
        @csrf

        <div class="grid grid-cols-5 gap-4 mb-8">

            @foreach($showtime->showtimeSeats as $seat)

                @if($seat->status == 'available' || $seat->status == 'locked')
                    <label class="cursor-pointer">
                        <input type="checkbox"
                               name="showtime_seat_ids[]"
                               value="{{ $seat->id }}"
                               class="hidden peer">

                        <div class="w-full py-3 rounded text-white font-semibold text-center
                            @if($seat->status == 'available')
                                bg-green-500 peer-checked:bg-green-700
                            @elseif($seat->status == 'locked')
                                bg-orange-500
                            @endif">
                            {{ $seat->seat->seat_number }}
                        </div>
                    </label>
                @else
                    <div class="w-full py-3 rounded text-white font-semibold text-center bg-red-500">
                        {{ $seat->seat->seat_number }}
                    </div>
                @endif

            @endforeach

        </div>

        <div class="text-center">
            <button type="submit"
                    class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800">
                🎟 Checkout
            </button>
        </div>

    </form>

</div>

@endsection
