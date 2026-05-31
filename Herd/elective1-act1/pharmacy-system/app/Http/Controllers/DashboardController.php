<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and records overview.
     */
    public function index()
    {
        $todaySalesCount = Sale::whereDate('created_at', now()->toDateString())->count();
        $todaySalesTotal = Sale::whereDate('created_at', now()->toDateString())->sum('total_amount');
        $outOfStockCount = \App\Models\Inventory::where('quantity', '<=', 0)->count();
        $lowStockCount = \App\Models\Inventory::whereRaw('quantity <= reorder_level')->count();
        $lowStockMedicines = \App\Models\Inventory::whereRaw('quantity <= reorder_level')->orderBy('quantity')->limit(5)->get();
        $recentSales = Sale::orderByDesc('created_at')->limit(5)->get();
        $topMedicines = Sale::selectRaw('medicine_name, SUM(quantity) as total_qty, SUM(total_amount) as total_amount')
            ->groupBy('medicine_name')->orderByDesc('total_qty')->limit(5)->get();
        $latestSuppliers = Supplier::latest('id')->limit(5)->get();
        $healthyStockCount = \App\Models\Inventory::where('quantity', '>', 10)->count();
        $suppliersWithContact = Supplier::whereNotNull('phone')->count();
        $salesCount = Sale::count();
        $totalSales = Sale::sum('total_amount');
        $averageSale = $salesCount > 0 ? $totalSales / $salesCount : 0;
        $inventoryCount = \App\Models\Inventory::count();
        $stockHealth = $inventoryCount > 0 ? round(($healthyStockCount / $inventoryCount) * 100) : 0;
        $medicinesCount = Medicine::count();
        $suppliersCount = Supplier::count();
        $activeAlerts = $outOfStockCount + $lowStockCount;

        return view('dashboard.index', compact(
            'todaySalesCount',
            'todaySalesTotal',
            'outOfStockCount',
            'lowStockCount',
            'lowStockMedicines',
            'recentSales',
            'topMedicines',
            'latestSuppliers',
            'healthyStockCount',
            'suppliersWithContact',
            'salesCount',
            'totalSales',
            'averageSale',
            'inventoryCount',
            'stockHealth',
            'medicinesCount',
            'suppliersCount',
            'activeAlerts'
        ));
    }
}
