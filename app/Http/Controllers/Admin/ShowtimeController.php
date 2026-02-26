<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\ShowtimeSeat;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowtimeController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::with(['film', 'studio'])
            ->latest()
            ->get();

        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function create()
    {
        $films = Film::all();
        $studios = Studio::all();

        return view('admin.showtimes.create', compact('films', 'studios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'show_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'price' => 'required|numeric|min:0',
        ]);

        // Cek bentrok dengan showtime lain di studio yang sama
        $hasConflict = Showtime::where('studio_id', $request->studio_id)
            ->where('show_date', $request->show_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($hasConflict) {
            return back()->withErrors([
                'start_time' => 'Showtime bentrok dengan jadwal lain di studio yang sama.'
            ])->withInput();
        }

        DB::transaction(function () use ($request) {

            // 1️⃣ Buat showtime
            $showtime = Showtime::create($request->all());

            // 2️⃣ Ambil semua seat dari studio
            $seats = Seat::where('studio_id', $request->studio_id)->get();

            // 3️⃣ Generate showtime_seats dengan bulk insert
            $showtimeSeats = $seats->map(fn ($seat) => [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            ShowtimeSeat::insert($showtimeSeats->toArray());
        });

        return redirect()->route('showtimes.index')
            ->with('success', 'Showtime berhasil ditambahkan & kursi otomatis dibuat');
    }

    public function edit(Showtime $showtime)
    {
        $films = Film::all();
        $studios = Studio::all();

        return view('admin.showtimes.edit',
            compact('showtime', 'films', 'studios'));
    }

    public function update(Request $request, Showtime $showtime)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'show_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'price' => 'required|numeric|min:0',
        ]);

        // Cek bentrok dengan showtime lain (kecuali showtime ini sendiri)
        $hasConflict = Showtime::where('studio_id', $request->studio_id)
            ->where('show_date', $request->show_date)
            ->where('id', '!=', $showtime->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($hasConflict) {
            return back()->withErrors([
                'start_time' => 'Showtime bentrok dengan jadwal lain di studio yang sama.'
            ])->withInput();
        }

        $showtime->update($request->all());

        return redirect()->route('showtimes.index')
            ->with('success', 'Showtime berhasil diupdate');
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();

        return redirect()->route('showtimes.index')
            ->with('success', 'Showtime berhasil dihapus');
    }
}
