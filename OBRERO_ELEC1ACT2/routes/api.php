<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\DashboardController;

// Dashboard stats
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

// Medicines
Route::apiResource('medicines', MedicineController::class);

// Suppliers
Route::apiResource('suppliers', SupplierController::class);

// Inventory
Route::apiResource('inventory', InventoryController::class);

// Sales (only index, store, show)
Route::get('sales', [SaleController::class, 'index']);
Route::post('sales', [SaleController::class, 'store']);
Route::get('sales/{sale}', [SaleController::class, 'show']);
