<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('guest')->name('api.')->group(function() {
    Route::post('register', [RegisterController::class, 'store'])->name('register');
});
