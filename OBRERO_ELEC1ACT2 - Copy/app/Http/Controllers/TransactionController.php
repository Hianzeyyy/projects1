<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type' => ['required', 'string', 'max:50'],
            'payment_method' => ['required', 'in:Cash,GCash'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.inventory_id' => ['required', 'exists:inventory,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $taxRate = (float) ($validated['tax_rate'] ?? 0);

        $transaction = DB::transaction(function () use ($validated, $taxRate) {
            $lineItems = [];
            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $inventory = Inventory::query()
                    ->with('medicine')
                    ->lockForUpdate()
                    ->findOrFail($item['inventory_id']);

                $medicineName = $inventory->medicine?->name
                    ?? (string) ($inventory->getAttribute('medicine_name') ?? ('Medicine #' . $inventory->id));

                $quantity = (int) $item['quantity'];

                if ($inventory->quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => ["Insufficient stock for {$medicineName}."],
                    ]);
                }

                $inventory->decrement('quantity', $quantity);

                $unitPrice = (float) ($item['unit_price'] ?? ($inventory->medicine->price ?? 0));
                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'inventory_id' => $inventory->id,
                    'medicine_name' => $medicineName,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $taxAmount = $subtotal * ($taxRate / 100);
            $grandTotal = $subtotal + $taxAmount;

            $transaction = Transaction::create([
                'user_id' => auth()->id() ?? 1,
                'inventory_id' => $lineItems[0]['inventory_id'] ?? null,
                'transaction_type' => $validated['transaction_type'],
                'payment_method' => $validated['payment_method'],
                'total_price' => $grandTotal,
                'tax_amount' => $taxAmount,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($lineItems as $line) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'inventory_id' => $line['inventory_id'],
                    'medicine_name' => $line['medicine_name'],
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'line_total' => $line['line_total'],
                ]);
            }

            return $transaction->load(['user', 'items']);
        });

        return response()->json([
            'message' => 'Transaction saved successfully.',
            'transaction' => $transaction,
        ], 201);
    }
}
