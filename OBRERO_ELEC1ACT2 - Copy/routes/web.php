<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiPharmacyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// ── Splash screen for everyone ───────────────────────────────────────────────
Route::get('/', function () {
    return response()->view('splash');
})->name('splash');

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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard/summary/download', [\App\Http\Controllers\Api\DashboardPdfController::class, 'summary'])
        ->name('dashboard.summary.download');

    Route::get('/medicines', function () {
        return view('medicines');
    })->name('medicines');

    Route::get('/inventory', function () {
        return view('inventory');
    })->name('inventory');

    Route::get('/sales', function () {
        return view('sales');
    })->name('sales');

    Route::get('/suppliers', function () {
        return view('suppliers');
    })->name('suppliers');

    Route::get('/transactions', function () {
        return view('transactions');
    })->name('transactions');


    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    // Download report as PDF
    Route::get('/reports/{id}/download', [\App\Http\Controllers\Api\ReportPdfController::class, 'download'])->middleware('auth');

    Route::get('/ai', function () {
        return redirect()->route('ai.parser');
    })->name('ai');

    // Pharmacy transaction and AI parser module
    Route::get('/ai/prescription-parser', [AiPharmacyController::class, 'index'])->name('ai.parser');
    Route::post('/ai/prescription-parser', [AiPharmacyController::class, 'parse'])->name('ai.parser.parse');

    Route::post('/transactions/pharmacy', [TransactionController::class, 'store'])->name('transactions.pharmacy.store');
    Route::get('/transactions/{transaction}/receipt', [ReportController::class, 'downloadReceipt'])->name('transactions.receipt.download');

    Route::get('/reports/sales/{period}', [ReportController::class, 'salesByPeriod'])
        ->whereIn('period', ['daily', 'weekly', 'monthly'])
        ->name('reports.sales.period');
});
