<?php

use App\Http\Controllers\ElectiveController;
use Illuminate\Support\Facades\Route;


// This maps the website root to your controller's landingPage function
Route::get('/', [ElectiveController::class, 'landingPage'])->name('landingPage');
// Existing routes
Route::get('/PsuStrategicGoals', [ElectiveController::class, 'PsuStrategicGoals'])->name('PsuStrategicGoals');
Route::get('/mission', [ElectiveController::class, 'mission'])->name('mission');
Route::get('/syllabus', [ElectiveController::class, 'syllabus'])->name('syllabus');
Route::get('/schedule', [ElectiveController::class, 'schedule'])->name('schedule');
Route::get('/calendar', [ElectiveController::class, 'calendar'])->name('calendar');
