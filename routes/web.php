<?php

use Illuminate\Support\Facades\Route;
use App\Models\Film;
use App\Models\Showtime;
use App\Models\Order;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\FilmController;
use App\Http\Controllers\admin\ShowtimeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\OrderHistoryController;

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
    
    Route::get('/film/{id}', function ($id) {
        $film = Film::with('showtimes')->findOrFail($id);
        return view('film-detail', compact('film'));
    })->middleware('auth')
    ->name('film.detail');


    Route::get('/my-orders', [OrderHistoryController::class, 'index'])
        ->name('my.orders');

    Route::get('/my-orders/{order}', [OrderHistoryController::class, 'show'])
        ->name('my.orders.show');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

    Route::get('/dashboard', function () {

        $totalFilms = Film::count();
        $totalShowtimes = Showtime::count();
        $totalOrders = Order::count();

        return view('admin.dashboard', compact(
            'totalFilms',
            'totalShowtimes',
            'totalOrders'
        ));
    })->name('admin.dashboard');
    Route::resource('films', FilmController::class);
    Route::resource('showtimes', ShowtimeController::class);
    Route::get('orders', [OrderController::class, 'index'])
    ->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');
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
