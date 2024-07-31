<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::resource('/user', UserController::class);
Route::resource('/table', TableController::class);
Route::get('/table_available', [TableController::class,'available']);
Route::get('/mybookings/{id}', [BookingController::class, 'mybookings']);
Route::resource('/booking', BookingController::class);


