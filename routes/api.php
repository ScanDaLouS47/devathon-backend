<?php

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


