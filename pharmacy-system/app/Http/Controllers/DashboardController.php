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
        $suppliersCount = Supplier::count();
        $medicinesCount = Medicine::count();
        $salesCount = Sale::count();
        $totalSales = Sale::sum('total_amount');

        return view('dashboard', compact('suppliersCount', 'medicinesCount', 'salesCount', 'totalSales'));
    }
}
