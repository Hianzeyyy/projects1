<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $stats = [
            'medicines' => Medicine::count(),
            'inventory' => Inventory::count(),
            'sales' => Sale::count(),
            'suppliers' => Supplier::count(),
            'lowStock' => Inventory::whereColumn('quantity', '<=', 'reorder_level')->count(),
            'totalSalesAmount' => (float) Sale::sum('total_amount'),
        ];

        return response()->json($stats);
    }
}
