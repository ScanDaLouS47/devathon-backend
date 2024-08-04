<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'auth:sanctum'
], function () {
    Route::get('auth/logout', [AuthController::class, 'logout']);
    Route::get('/user/show', [UserController::class, 'show']);
});

Route::resource('/user', UserController::class)->middleware('auth:sanctum');
Route::post('auth/create', [AuthController::class, 'store']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::resource('/table', TableController::class);
Route::get('/table_available', [TableController::class,'available']);
Route::get('/mybookings/{id}', [BookingController::class, 'mybookings']);
Route::resource('/booking', BookingController::class);



