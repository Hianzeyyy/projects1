<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Medicine;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(): JsonResponse
    {
        $sales = Sale::with('medicine')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sales);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $medicine = Medicine::findOrFail($validated['medicine_id']);

                $inventory = Inventory::where('medicine_id', $validated['medicine_id'])
                    ->first();

                if (!$inventory || $inventory->quantity < $validated['quantity']) {
                    return response()->json([
                        'message' => 'Insufficient stock for this sale'
                    ], 400);
                }

                $unitPrice = $medicine->price;
                $totalAmount = $unitPrice * $validated['quantity'];

                $sale = Sale::create([
                    'medicine_id' => $validated['medicine_id'],
                    'medicine_name' => $medicine->name,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $unitPrice,
                    'total_amount' => $totalAmount,
                    'customer_name' => $validated['customer_name'],
                ]);

                $inventory->decrement('quantity', $validated['quantity']);

                return response()->json([
                    'message' => 'Sale recorded successfully',
                    'data' => $sale
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error recording sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Sale $sale): JsonResponse
    {
        return response()->json($sale->load('medicine'));
    }
}
