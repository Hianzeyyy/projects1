<?php

namespace App\Services;


class ReceiptService
{
    /**
     * Generate a PDF receipt for a sale.
     */
    public function generateReceipt($sale)
    {
        $mpdf = new \Mpdf\Mpdf();
        $html = view('receipts.sale', [
            'sale' => $sale,
            'timestamp' => now(),
        ])->render();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="receipt_' . $sale->id . '.pdf"');
    }
}
