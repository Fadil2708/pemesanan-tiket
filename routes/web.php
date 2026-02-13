<?php

use Illuminate\Support\Facades\Route;
use App\Models\Film;
use App\Models\Showtime;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home (WAJIB punya name('home'))
Route::get('/', function () {
    $films = Film::all();
    return view('home', compact('films'));
})->name('home');

// Detail Film
Route::get('/film/{id}', function ($id) {
    $film = Film::with('showtimes')->findOrFail($id);
    return view('film-detail', compact('film'));
})->name('film.detail');

// Detail Showtime
Route::get('/showtime/{id}', function ($id) {
    $showtime = Showtime::with('showtimeSeats.seat')->findOrFail($id);
    return view('showtime', compact('showtime'));
})->middleware('auth')->name('showtime.detail');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {

        $totalFilms = Film::count();
        $totalShowtimes = Showtime::count();
        $myOrders = Order::where('user_id', auth()->id())->count();

        return view('dashboard', compact(
            'totalFilms',
            'totalShowtimes',
            'myOrders'
        ));
    })->name('dashboard');

});


require __DIR__.'/settings.php';
