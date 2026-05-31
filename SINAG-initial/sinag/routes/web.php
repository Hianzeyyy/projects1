<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::view('/report-abuse', 'pages.report-abuse')->name('report-abuse');
    Route::view('/evidence-vault', 'pages.evidence-vault')->name('evidence-vault');
    Route::view('/suggestions', 'pages.suggestions')->name('suggestions');
    Route::view('/safewalk', 'pages.safewalk')->name('safewalk');
});

Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/inbox', 'admin.inbox')->name('admin.inbox');
    Route::view('/suggestions', 'admin.suggestions')->name('admin.suggestions');
    Route::view('/users', 'admin.users')->name('admin.users');
});

require __DIR__.'/auth.php';
