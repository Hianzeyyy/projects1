<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ── Guest-only: auth pages ───────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
    Route::get('/signup',          [AuthController::class, 'showSignup'])->name('signup');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');

    Route::post('/login',           [AuthController::class, 'login']);
    Route::post('/signup',          [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

// ── Logout (auth required) ───────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Protected app pages ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return response(require resource_path('views/dashboard.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    })->name('dashboard');

    Route::get('/medicines', function () {
        return response(require resource_path('views/medicines.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    })->name('medicines');

    Route::get('/inventory', function () {
        return response(require resource_path('views/inventory.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    })->name('inventory');

    Route::get('/sales', function () {
        return response(require resource_path('views/sales.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    })->name('sales');

    Route::get('/suppliers', function () {
        return response(require resource_path('views/suppliers.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    })->name('suppliers');
});
