<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Api\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Api\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisteredUserController::class, 'store']);

Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware(['verified']);

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->middleware(['verified']);

Route::post('reset-password', [NewPasswordController::class, 'store']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class);

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1']);

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show']);

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
});
