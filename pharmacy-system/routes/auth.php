<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [AuthenticationController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthenticationController::class, 'register']);

    // Login
    Route::get('login', [AuthenticationController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthenticationController::class, 'login']);

    // Password Reset
    Route::get('forgot-password', [AuthenticationController::class, 'showForgotPassword'])->name('password.request');
    Route::post('forgot-password', [AuthenticationController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [AuthenticationController::class, 'showResetPassword'])->name('password.reset');
    Route::post('reset-password', [AuthenticationController::class, 'resetPassword'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Email Verification
    Route::get('verify-email', [AuthenticationController::class, 'showVerifyEmail'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [AuthenticationController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [AuthenticationController::class, 'sendVerificationNotification'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password Confirmation
    Route::get('confirm-password', [AuthenticationController::class, 'showConfirmPassword'])->name('password.confirm');
    Route::post('confirm-password', [AuthenticationController::class, 'confirmPassword']);

    // Password Update
    Route::put('password', [AuthenticationController::class, 'updatePassword'])->name('password.update');

    // Logout
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
});

