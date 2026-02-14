<?php

use Illuminate\Support\Facades\Route;
use App\Models\Film;
use App\Models\Showtime;
use App\Models\Order;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    $films = Film::all();
    return view('home', compact('films'));
})->name('home');

// Detail Film
Route::get('/film/{id}', function ($id) {
    $film = Film::with('showtimes')->findOrFail($id);
    return view('film-detail', compact('film'));
})->name('film.detail');


/*
|--------------------------------------------------------------------------
| Customer Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
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

    // Detail Showtime
    Route::get('/showtime/{id}', function ($id) {
        $showtime = Showtime::with('showtimeSeats.seat')->findOrFail($id);
        return view('showtime', compact('showtime'));
    })->name('showtime.detail');

    // Lock Seat
    Route::post('/lock-seat/{id}', [BookingController::class, 'lockSeat'])
        ->name('seat.lock');

    // Checkout
    Route::post('/checkout', [BookingController::class, 'checkout'])
        ->name('checkout');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});

// Login
Route::get('/login/{role}', [AuthController::class, 'showLogin'])
    ->name('login.role');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

// Register
Route::get('/register/{role}', [AuthController::class, 'showRegister'])
    ->name('register.role');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


require __DIR__.'/settings.php';
