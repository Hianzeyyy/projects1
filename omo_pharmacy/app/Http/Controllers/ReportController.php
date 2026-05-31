<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Services\ReceiptService;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reportService)
    {
        $type = (string) $request->query('type', 'daily');
        if (!in_array($type, ['daily', 'weekly', 'monthly'], true)) {
            $type = 'daily';
        }

        $date = (string) $request->query('date', now()->toDateString());
        $params = [
            'type' => $type,
            'date' => $date,
        ];

        $data = $reportService->getReportData($type, $params);

        $receiptDateFrom = (string) $request->query('receipt_date_from', now()->startOfMonth()->toDateString());
        $receiptDateTo = (string) $request->query('receipt_date_to', now()->toDateString());
        $receiptSearch = trim((string) $request->query('receipt_search', ''));

        $receiptHistoryQuery = Sale::query()
            ->whereDate('created_at', '>=', $receiptDateFrom)
            ->whereDate('created_at', '<=', $receiptDateTo)
            ->orderByDesc('created_at');

        if ($receiptSearch !== '') {
            $receiptHistoryQuery->where(function ($query) use ($receiptSearch) {
                $query->where('medicine_name', 'like', '%' . $receiptSearch . '%')
                    ->orWhere('customer_name', 'like', '%' . $receiptSearch . '%')
                    ->orWhere('id', 'like', '%' . $receiptSearch . '%');
            });
        }

        $receiptHistory = $receiptHistoryQuery->limit(50)->get();

        return view('reports.index', [
            'type' => $type,
            'date' => $date,
            'reportData' => $data,
            'receiptHistory' => $receiptHistory,
            'receiptDateFrom' => $receiptDateFrom,
            'receiptDateTo' => $receiptDateTo,
            'receiptSearch' => $receiptSearch,
        ]);
    }

    public function download(Request $request, ReportService $reportService)
    {
        $request->validate([
            'type' => 'required|in:daily,weekly,monthly',
            'date' => 'nullable|date',
        ]);
        $params = [
            'type' => $request->input('type'),
            'date' => $request->input('date', now()->toDateString()),
        ];
        $userId = Auth::id();
        $pdf = $reportService->generateReportPdf($params, $userId);

        return response($pdf['content'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $pdf['filename'] . '"',
        ]);
    }

    public function receipt(Sale $sale, ReceiptService $receiptService)
    {
        return $receiptService->generateReceipt($sale);
    }

    public function archive(Request $request, ReportService $reportService)
    {
        $request->validate([
            'type' => 'required|in:daily,weekly,monthly',
            'date' => 'nullable|date',
        ]);

        $params = [
            'type' => $request->input('type'),
            'date' => $request->input('date', now()->toDateString()),
        ];

        try {
            $storedPath = $reportService->generateReport($params, Auth::id());

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Report archived successfully at storage/app/' . $storedPath,
                    'path' => $storedPath,
                ]);
            }

            return redirect()
                ->route('reports.index', [
                    'type' => $params['type'],
                    'date' => $params['date'],
                ])
                ->with('report_success', 'Report archived successfully at storage/app/' . $storedPath);
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Failed to archive report PDF. ' . $e->getMessage(),
                ], 422);
            }

            return redirect()
                ->route('reports.index', [
                    'type' => $params['type'],
                    'date' => $params['date'],
                ])
                ->with('report_error', 'Failed to archive report PDF. ' . $e->getMessage());
        }
    }
}
