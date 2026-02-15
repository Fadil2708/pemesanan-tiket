<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Film;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Seat;
use App\Models\ShowtimeSeat;

class ShowtimeController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::with(['film','studio'])
            ->latest()
            ->get();

        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function create()
    {
        $films = Film::all();
        $studios = Studio::all();

        return view('admin.showtimes.create', compact('films','studios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required',
            'studio_id' => 'required',
            'show_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'price' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ Buat showtime
            $showtime = Showtime::create($request->all());

            // 2️⃣ Ambil semua seat dari studio
            $seats = Seat::where('studio_id', $request->studio_id)->get();

            // 3️⃣ Generate showtime_seats
           foreach ($seats as $seat) {
                ShowtimeSeat::firstOrCreate([
                    'showtime_id' => $showtime->id,
                    'seat_id' => $seat->id,
                ], [
                    'status' => 'available'
                ]);
            }
        });

        return redirect()->route('showtimes.index')
            ->with('success','Showtime berhasil ditambahkan & kursi otomatis dibuat');
    }

    public function edit(Showtime $showtime)
    {
        $films = Film::all();
        $studios = Studio::all();

        return view('admin.showtimes.edit',
            compact('showtime','films','studios'));
    }

    public function update(Request $request, Showtime $showtime)
    {
        $request->validate([
            'film_id' => 'required',
            'studio_id' => 'required',
            'show_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'price' => 'required|numeric'
        ]);

        $showtime->update($request->all());

        return redirect()->route('showtimes.index')
            ->with('success','Showtime berhasil diupdate');
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();

        return redirect()->route('showtimes.index')
            ->with('success','Showtime berhasil dihapus');
    }
}
