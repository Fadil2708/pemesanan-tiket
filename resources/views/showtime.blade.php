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

    <div class="grid grid-cols-5 gap-4">

        @foreach($showtime->showtimeSeats as $seat)

            <form method="POST" action="/lock-seat/{{ $seat->id }}">
                @csrf

                <button
                    type="submit"
                    class="w-full py-3 rounded text-white font-semibold
                    @if($seat->status == 'available')
                        bg-green-500 hover:bg-green-600
                    @elseif($seat->status == 'locked')
                        bg-orange-500
                    @else
                        bg-red-500 cursor-not-allowed
                    @endif"
                    @if($seat->status == 'booked') disabled @endif
                >
                    {{ $seat->seat->seat_number }}
                </button>

            </form>

        @endforeach

    </div>

</div>

@endsection
