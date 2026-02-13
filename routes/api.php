<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::post('/lock-seat/{id}', [BookingController::class, 'lockSeat']);
Route::post('/checkout', [BookingController::class, 'checkout'])
    ->middleware('api');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
