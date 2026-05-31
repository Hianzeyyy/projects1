<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesByPeriod(Request $request, string $period)
    {
        $now = Carbon::now();

        $range = match ($period) {
            'daily' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'weekly' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'monthly' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            default => null,
        };

        if (!$range) {
            abort(404, 'Invalid report period.');
        }

        [$start, $end] = $range;

        $transactions = Transaction::query()
            ->with(['user', 'items'])
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'period' => $period,
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString(),
            'transaction_count' => $transactions->count(),
            'gross_sales' => (float) $transactions->sum('total_price'),
            'tax_total' => (float) $transactions->sum('tax_amount'),
            'data' => $transactions,
        ]);
    }

    public function downloadReceipt(Request $request, Transaction $transaction)
    {
        $transaction->load(['user', 'items']);

        $subtotal = (float) $transaction->items->sum('line_total');
        $tax = (float) ($transaction->tax_amount ?? 0);
        $total = (float) ($subtotal + $tax);

        $pdf = Pdf::loadView('pdf.receipt', [
            'transaction' => $transaction,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'pharmacistName' => optional($transaction->user)->name ?? 'Assigned Pharmacist',
        ])->setPaper('a4');

        $filename = 'receipt_' . $transaction->id . '.pdf';

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
