<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailBookingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Cors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([Cors::class])->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

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

    Route::post('/booking', [BookingController::class, 'store']);
    Route::get('/mybookings2', [BookingController::class, 'mybookings2']);
});
Route::get('/mybookings/{id}', [BookingController::class, 'mybookings']);


Route::resource('/table', TableController::class);
Route::get('/table_available', [TableController::class, 'available']);
Route::resource('/booking', BookingController::class)->except(['store']);

Route::get('/todaybookings', [BookingController::class, 'todaybookings']);

Route::get('/detail_booking', [DetailBookingController::class, 'all']);

Route::get('/dashboard/barchart', [DashboardController::class, 'barchart']);
Route::get('/dashboard/donutchart', [DashboardController::class, 'donutchart']);


Route::fallback(function () {
    return response()->json(['message' => 'Page Not Found'], 404);
});
