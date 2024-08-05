<?php


use Illuminate\Support\Facades\Route;


Route::fallback(function () {
    return response()->json(['message' => 'Page Not Found'], 404);
});
