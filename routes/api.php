<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/lock-seat/{id}', [BookingController::class, 'lockSeat']);
Route::post('/checkout', [BookingController::class, 'checkout'])
    ->middleware('api');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
