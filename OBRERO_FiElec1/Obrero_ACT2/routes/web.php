<?php

use Illuminate\Support\Facades\Route;
// THIS LINE FIXES THE "Undefined type" ERROR:
use App\Http\Controllers\PharmacyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Prototype: Dashboard & Records (View)
// This serves as your home page
Route::get('/', [PharmacyController::class, 'index']);

// 2. Prototype: Management Form (Add Action)
// This handles the POST request from your 'Add Medicine' form
Route::post('/add-medicine', [PharmacyController::class, 'store']);

// 3. Prototype: Information and Records (Delete Action)
// This allows you to delete a record by its ID
Route::get('/delete-medicine/{id}', [PharmacyController::class, 'destroy']);

Route::get('/pharmacy', function () {
    return view('pharmacy');
});
Route::get('/', [PharmacyController::class, 'index']);
Route::post('/add-medicine', [PharmacyController::class, 'store']);
Route::get('/delete-medicine/{id}', [PharmacyController::class, 'destroy']);

// FIX: Change 'get' to 'any' so Login (POST) works!
Route::any('/pharmacy', function () {
    // This allows pharmacy.php to handle its own POST logic
    return view('pharmacy');
});
