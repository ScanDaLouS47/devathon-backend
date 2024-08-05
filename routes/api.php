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

Route::post('login', [AuthController::class, 'login']);
Route::post('create', [AuthController::class, 'store']);

Route::group([
    'middleware' => 'auth:sanctum'
], function () {
    Route::get('/user/profile', [UserController::class, 'userProfile']);
    Route::get('/user', [UserController::class, 'index']);
    Route::delete('/user', [UserController::class, 'destroy']);
    Route::put('/user', [UserController::class, 'update']);
    Route::get('/user/{user}', [UserController::class, 'show']);
    Route::get('logout', [AuthController::class, 'logout']);
});


Route::resource('/table', TableController::class);
Route::get('/table_available', [TableController::class,'available']);
Route::get('/mybookings/{id}', [BookingController::class, 'mybookings']);
Route::resource('/booking', BookingController::class);


Route::fallback(function () {
    return response()->json(['message' => 'Page Not Found'], 404);
});
