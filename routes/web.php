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
| Default Login Redirect (WAJIB)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return redirect()->route('customer.login');
})->name('login');


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    $heroFilms = Film::latest()->take(5)->get(); // ambil 5 film terbaru
    $films = Film::latest()->get();

    return view('home', compact('films', 'heroFilms'));

})->name('home');

/*
|--------------------------------------------------------------------------
| Customer Routes
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

    Route::get('/film/{id}', function ($id) {
        $film = Film::with('showtimes')->findOrFail($id);
        return view('film-detail', compact('film'));
    })->name('film.detail');

    Route::get('/showtime/{id}', function ($id) {
        $showtime = Showtime::with('showtimeSeats.seat')->findOrFail($id);
        return view('showtime', compact('showtime'));
    })->name('showtime.detail');

    Route::post('/lock-seat/{id}', [BookingController::class, 'lockSeat'])
    ->middleware('auth')
    ->name('seat.lock');

    Route::post('/checkout', [BookingController::class, 'checkout'])
        ->name('checkout');

    Route::get('/my-orders', [OrderHistoryController::class, 'index'])
        ->name('my.orders');

    Route::get('/my-orders/{order}', [OrderHistoryController::class, 'show'])
        ->name('my.orders.show');
        
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

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


/*
|--------------------------------------------------------------------------
| Customer Auth
|--------------------------------------------------------------------------
*/

Route::get('/login/customer', [AuthController::class, 'showCustomerLogin'])
    ->name('customer.login');

Route::post('/login/customer', [AuthController::class, 'loginCustomer'])
    ->name('customer.login.process');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');


/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'loginAdmin'])
    ->name('admin.login.process');


Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
