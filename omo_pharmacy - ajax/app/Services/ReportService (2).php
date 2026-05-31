<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Sale;
use App\Models\Medicine;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportService
{
    /**
     * Build report PDF content and optionally persist a storage copy.
     *
     * @return array{filename:string,content:string,path:?string}
     */
    public function generateReportPdf(array $params, $userId): array
    {
        $type = $params['type'];
        $data = $this->getReportData($type, $params);

        $mpdf = new \Mpdf\Mpdf();
        $html = view('reports.summary', [
            'data' => $data,
            'type' => $type,
            'timestamp' => now(),
        ])->render();
        $mpdf->WriteHTML($html);

        $filename = "report_{$type}_" . now()->format('Ymd_His') . ".pdf";
        $path = "reports/{$filename}";
        $content = $mpdf->Output('', 'S');

        $storedPath = null;
        if (Storage::put($path, $content)) {
            $storedPath = $path;
        }

        if (Schema::hasTable('reports')) {
            Report::create([
                'generated_by' => $userId,
                'type' => $type,
                'parameters' => json_encode($params),
                'file_path' => $storedPath,
                'is_read_only' => true,
                'timestamp' => now(),
            ]);
        }

        return [
            'filename' => $filename,
            'content' => $content,
            'path' => $storedPath,
        ];
    }

    /**
     * Generate and store a PDF report.
     */
    public function generateReport(array $params, $userId): string
    {
        $pdf = $this->generateReportPdf($params, $userId);

        if ($pdf['path'] === null) {
            throw new \RuntimeException('Unable to save PDF report copy to storage.');
        }

        return $pdf['path'];
    }

    public function getReportData(string $type, array $params = []): array
    {
        [$start, $end] = $this->resolveDateRange($type, $params);

        $sales = Sale::query()
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->get();

        $totalRevenue = (float) $sales->sum('total_amount');
        $estimatedCost = $totalRevenue * 0.8;
        $estimatedProfit = $totalRevenue - $estimatedCost;
        $profitMargin = $totalRevenue > 0
            ? round(($estimatedProfit / $totalRevenue) * 100, 2)
            : 0.0;

        $salesRows = $sales->map(static function (Sale $sale): array {
            return [
                'id' => $sale->id,
                'medicine_name' => $sale->medicine_name,
                'customer_name' => $sale->customer_name ?? 'Walk-in',
                'quantity' => (int) $sale->quantity,
                'unit_price' => (float) $sale->unit_price,
                'total' => (float) $sale->total_amount,
                'date' => optional($sale->created_at)->format('Y-m-d H:i'),
            ];
        })->values()->all();

        $lowStockRows = Medicine::query()
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->limit(20)
            ->get(['name', 'stock'])
            ->map(static function (Medicine $medicine): array {
                return [
                    'medicine_name' => $medicine->name,
                    'quantity' => (int) $medicine->stock,
                ];
            })->values()->all();

        return [
            'range' => [
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
            ],
            'totals' => [
                'transactions' => count($salesRows),
                'revenue' => $totalRevenue,
                'estimated_cost' => $estimatedCost,
                'estimated_profit' => $estimatedProfit,
                'profit_margin' => $profitMargin,
            ],
            'sales' => $salesRows,
            'low_stock' => $lowStockRows,
        ];
    }

    private function resolveDateRange(string $type, array $params): array
    {
        if (!empty($params['date'])) {
            $base = Carbon::parse((string) $params['date']);
        } else {
            $base = now();
        }

        if ($type === 'weekly') {
            return [$base->copy()->startOfWeek(), $base->copy()->endOfWeek()];
        }

        if ($type === 'monthly') {
            return [$base->copy()->startOfMonth(), $base->copy()->endOfMonth()];
        }

        return [$base->copy()->startOfDay(), $base->copy()->endOfDay()];
    }
}
