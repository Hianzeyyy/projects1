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


// Sales
Route::apiResource('sales', SaleController::class);

// Transactions
Route::apiResource('transactions', App\Http\Controllers\Api\TransactionController::class);

// Reports
Route::apiResource('reports', App\Http\Controllers\Api\ReportController::class);

// AI Processes
Route::apiResource('aiprocesses', App\Http\Controllers\Api\AIProcessController::class);
