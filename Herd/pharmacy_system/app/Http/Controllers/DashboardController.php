<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Medicine;
use App\Models\Sale;

class DashboardController extends Controller
{
    private const LOW_STOCK_THRESHOLD = 10;

    /**
     * Display the dashboard with statistics and records overview.
     */
    public function index()
    {
        $suppliersCount = Supplier::query()->count('*');
        $medicinesCount = Medicine::query()->count('*');
        $salesCount = Sale::query()->count('*');
        $totalSales = Sale::sum('total_amount');

        $todaySalesCount = Sale::query()
            ->whereDate('created_at', '=', now()->toDateString(), 'and')
            ->count('*');
        $todaySalesTotal = Sale::query()
            ->whereDate('created_at', '=', now()->toDateString(), 'and')
            ->sum('total_amount');
        $outOfStockCount = Medicine::query()
            ->where('stock', '<=', 0, 'and')
            ->count('*');
        $lowStockCount = Medicine::query()
            ->where('stock', '>', 0, 'and')
            ->where('stock', '<=', self::LOW_STOCK_THRESHOLD, 'and')
            ->count('*');
        $lowStockMedicines = Medicine::query()
            ->where('stock', '<=', self::LOW_STOCK_THRESHOLD, 'and')
            ->orderBy('stock')
            ->limit(5)
            ->get(['name', 'stock'])
            ->map(fn (Medicine $medicine): array => [
                'medicine_name' => $medicine->name,
                'reorder_level' => self::LOW_STOCK_THRESHOLD,
                'quantity' => $medicine->stock,
            ]);
        $recentSales = Sale::query()->orderByDesc('created_at')->limit(5)->get();
        $topMedicines = Sale::query()->selectRaw(
            'medicine_name, SUM(quantity) as total_qty, SUM(total_amount) as total_amount',
            []
        )
            ->groupBy('medicine_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
        $latestSuppliers = Supplier::query()->latest('id')->limit(5)->get();
        $healthyStockCount = Medicine::query()
            ->where('stock', '>', self::LOW_STOCK_THRESHOLD, 'and')
            ->count('*');
        $suppliersWithContact = Supplier::query()
            ->whereNotNull('contact', 'and')
            ->where('contact', '<>', '', 'and')
            ->count('*');
        $averageSale = $salesCount > 0 ? $totalSales / $salesCount : 0;
        $inventoryCount = Medicine::query()->count('*');
        $stockHealth = $inventoryCount > 0 ? round(($healthyStockCount / $inventoryCount) * 100) : 0;
        $activeAlerts = $outOfStockCount + $lowStockCount;

        return view('dashboard.index', compact(
            'suppliersCount',
            'medicinesCount',
            'salesCount',
            'totalSales',
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
            'averageSale',
            'inventoryCount',
            'stockHealth',
            'activeAlerts'
        ));
    }
}
