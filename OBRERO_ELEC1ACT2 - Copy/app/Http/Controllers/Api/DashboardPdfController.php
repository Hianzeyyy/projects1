<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardPdfController extends Controller
{
    public function summary(Request $request)
    {
        $period = strtolower((string) $request->query('period', 'monthly'));
        if (!in_array($period, ['daily', 'weekly', 'monthly', 'yearly'], true)) {
            $period = 'monthly';
        }

        $now = Carbon::now();
        [$start, $end] = match ($period) {
            'daily' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'weekly' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'yearly' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };

        $salesInRange = DB::table('sales')
            ->whereBetween(DB::raw('COALESCE(sale_date, created_at)'), [$start, $end]);

        $stats = [
            'total_medicines' => DB::table('medicines')->count(),
            'total_inventory' => DB::table('inventory')->count(),
            'total_sales' => (clone $salesInRange)->count(),
            'total_suppliers' => DB::table('suppliers')->count(),
            'low_stock' => DB::table('inventory')->whereColumn('quantity', '<=', 'reorder_level')->count(),
            'total_revenue' => (float) (clone $salesInRange)->sum('total_amount'),
            'total_units_sold' => (int) ((clone $salesInRange)->sum('quantity') ?? 0),
        ];

        $averageSale = $stats['total_sales'] > 0
            ? (float) ($stats['total_revenue'] / $stats['total_sales'])
            : 0.0;

        $topMedicines = DB::table('sales')
            ->select(
                'medicine_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereBetween(DB::raw('COALESCE(sale_date, created_at)'), [$start, $end])
            ->groupBy('medicine_name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $detailedSales = DB::table('sales')
            ->select('medicine_name', 'customer_name', 'quantity', 'unit_price', 'total_amount', 'sale_date', 'created_at')
            ->whereBetween(DB::raw('COALESCE(sale_date, created_at)'), [$start, $end])
            ->orderByDesc(DB::raw('COALESCE(sale_date, created_at)'))
            ->limit(100)
            ->get();

        $pdf = Pdf::loadView('pdf.dashboard_summary', [
            'stats' => $stats,
            'period' => $period,
            'periodLabel' => ucfirst($period),
            'start' => $start,
            'end' => $end,
            'averageSale' => $averageSale,
            'topMedicines' => $topMedicines,
            'detailedSales' => $detailedSales,
            'generatedAt' => $now,
        ]);

        return $pdf->download('dashboard_summary_' . $period . '_' . $now->format('Ymd_His') . '.pdf');
    }
}
