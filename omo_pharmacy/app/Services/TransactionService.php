<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Process a sale transaction with payment, discount, and tax logic.
     */
    public function processSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            // 1. Calculate subtotal
            $subtotal = collect($data['items'])->sum(function ($item) {
                return $item['unit_price'] * $item['quantity'];
            });

            // 2. Apply discount
            $discount = 0;
            if (!empty($data['discount_type'])) {
                if ($data['discount_type'] === 'senior') {
                    $discount = $subtotal * 0.20;
                } elseif ($data['discount_type'] === 'pwd') {
                    $discount = $subtotal * 0.20;
                }
            }

            // 3. Calculate tax (e.g., 12% VAT)
            $taxable = $subtotal - $discount;
            $tax = $taxable * 0.12;

            // 4. Calculate total
            $total = $taxable + $tax;

            // 5. Create Sale
            $sale = Sale::create([
                'pharmacist_id' => $data['pharmacist_id'],
                'customer_name' => $data['customer_name'] ?? null,
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $total,
                'status' => 'completed',
            ]);

            // 6. Attach items and update inventory
            foreach ($data['items'] as $item) {
                $sale->items()->create([
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['unit_price'] * $item['quantity'],
                    'dosage' => $item['dosage'] ?? null,
                ]);
                // Update inventory
                $medicine = Medicine::find($item['medicine_id']);
                $medicine->decrement('stock', $item['quantity']);
            }

            return $sale;
        });
    }
}
