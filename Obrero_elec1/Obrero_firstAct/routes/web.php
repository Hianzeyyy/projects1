<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectiveControllers;

// Main Portal Route
Route::get('/', [ElectiveControllers::class, 'index']);
