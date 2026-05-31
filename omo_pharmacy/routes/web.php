<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\RiskAssessmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/download', [ReportController::class, 'download'])->name('reports.download');
    Route::post('/reports/archive', [ReportController::class, 'archive'])->name('reports.archive');
    Route::get('/sales/{sale}/receipt', [ReportController::class, 'receipt'])->name('sales.receipt');

    Route::get('/ai/process', [AiController::class, 'index'])->name('ai.index');
    Route::post('/ai/process', [AiController::class, 'process'])->name('ai.process');

    Route::get('/risk-alerts', [RiskAssessmentController::class, 'index'])->name('risk-alerts.index');
    Route::post('/risk-alerts/assess', [RiskAssessmentController::class, 'assess'])->name('risk-alerts.assess');
});

// Management Routes (Create/Add Records)
Route::middleware('auth')->group(function () {
    // Medicine Management
    Route::get('/medicines/create', [ManagementController::class, 'createMedicine'])->name('medicines.create');
    Route::post('/medicines', [ManagementController::class, 'storeMedicine'])->name('medicines.store');

    // Sale Management
    Route::get('/sales/create', [ManagementController::class, 'createSale'])->name('sales.create');
    Route::post('/sales', [ManagementController::class, 'storeSale'])->name('sales.store');

    // Supplier Management
    Route::get('/suppliers/create', [ManagementController::class, 'createSupplier'])->name('suppliers.create');
    Route::post('/suppliers', [ManagementController::class, 'storeSupplier'])->name('suppliers.store');
});

// Records Routes (View, Edit, Delete)
Route::middleware('auth')->group(function () {
    // Medicine Records
    Route::get('/medicines', [RecordsController::class, 'indexMedicines'])->name('records.medicines');
    Route::get('/medicines/{id}', [RecordsController::class, 'showMedicine'])->name('medicines.show');
    Route::get('/medicines/{id}/edit', [RecordsController::class, 'editMedicine'])->name('medicines.edit');
    Route::put('/medicines/{id}', [RecordsController::class, 'updateMedicine'])->name('medicines.update');
    Route::delete('/medicines/{id}', [RecordsController::class, 'destroyMedicine'])->name('medicines.destroy');

    // Inventory Records
    Route::get('/inventory', [RecordsController::class, 'indexInventory'])->name('records.inventory');

    // Sale Records
    Route::get('/sales', [RecordsController::class, 'indexSales'])->name('records.sales');
    Route::get('/sales/{id}', [RecordsController::class, 'showSale'])->name('sales.show');
    Route::get('/sales/{id}/edit', [RecordsController::class, 'editSale'])->name('sales.edit');
    Route::put('/sales/{id}', [RecordsController::class, 'updateSale'])->name('sales.update');
    Route::delete('/sales/{id}', [RecordsController::class, 'destroySale'])->name('sales.destroy');

    // Supplier Records
    Route::get('/suppliers', [RecordsController::class, 'indexSuppliers'])->name('records.suppliers');
    Route::get('/suppliers/{id}', [RecordsController::class, 'showSupplier'])->name('suppliers.show');
    Route::get('/suppliers/{id}/edit', [RecordsController::class, 'editSupplier'])->name('suppliers.edit');
    Route::put('/suppliers/{id}', [RecordsController::class, 'updateSupplier'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [RecordsController::class, 'destroySupplier'])->name('suppliers.destroy');
});

require __DIR__.'/auth.php';
