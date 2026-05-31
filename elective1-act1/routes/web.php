<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectiveController; // Capital 'E'

Route::get('/', [ElectiveController::class, 'index']);
Route::get('/syllabus', [ElectiveController::class, 'syllabus']);