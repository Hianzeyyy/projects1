<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Medicine;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        $salesColumns = Schema::getColumnListing('sales');
        $hasMedicineName = in_array('medicine_name', $salesColumns, true);
        $hasUnitPrice = in_array('unit_price', $salesColumns, true);
        $hasTotalAmount = in_array('total_amount', $salesColumns, true);
        $hasTotalPrice = in_array('total_price', $salesColumns, true);
        $hasSaleDate = in_array('sale_date', $salesColumns, true);
        $hasCustomerName = in_array('customer_name', $salesColumns, true);

        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => $hasCustomerName ? 'required|string|max:255' : 'nullable|string|max:255',
        ]);

        try {
            return DB::transaction(function () use ($validated, $hasMedicineName, $hasUnitPrice, $hasTotalAmount, $hasTotalPrice, $hasSaleDate, $hasCustomerName) {
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

                $salePayload = [
                    'medicine_id' => $validated['medicine_id'],
                    'quantity' => $validated['quantity'],
                ];
                if ($hasMedicineName) {
                    $salePayload['medicine_name'] = $medicine->name;
                }
                if ($hasUnitPrice) {
                    $salePayload['unit_price'] = $unitPrice;
                }
                if ($hasTotalAmount) {
                    $salePayload['total_amount'] = $totalAmount;
                }
                if ($hasTotalPrice) {
                    $salePayload['total_price'] = $totalAmount;
                }
                if ($hasSaleDate) {
                    $salePayload['sale_date'] = now()->toDateString();
                }
                if ($hasCustomerName) {
                    $salePayload['customer_name'] = $validated['customer_name'] ?? 'Walk-in';
                }

                $sale = Sale::create($salePayload);

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

    public function update(Request $request, Sale $sale): JsonResponse
    {
        $salesColumns = Schema::getColumnListing('sales');
        $hasMedicineName = in_array('medicine_name', $salesColumns, true);
        $hasUnitPrice = in_array('unit_price', $salesColumns, true);
        $hasTotalAmount = in_array('total_amount', $salesColumns, true);
        $hasTotalPrice = in_array('total_price', $salesColumns, true);
        $hasSaleDate = in_array('sale_date', $salesColumns, true);
        $hasCustomerName = in_array('customer_name', $salesColumns, true);

        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => $hasCustomerName ? 'required|string|max:255' : 'nullable|string|max:255',
        ]);

        try {
            return DB::transaction(function () use ($validated, $sale, $hasMedicineName, $hasUnitPrice, $hasTotalAmount, $hasTotalPrice, $hasSaleDate, $hasCustomerName) {
                $oldInventory = Inventory::where('medicine_id', $sale->medicine_id)->first();
                if ($oldInventory) {
                    $oldInventory->increment('quantity', $sale->quantity);
                }

                $medicine = Medicine::findOrFail($validated['medicine_id']);
                $newInventory = Inventory::where('medicine_id', $validated['medicine_id'])->first();

                if (!$newInventory || $newInventory->quantity < $validated['quantity']) {
                    throw new \RuntimeException('Insufficient stock for this sale');
                }

                $unitPrice = $medicine->price;
                $totalAmount = $unitPrice * $validated['quantity'];

                $salePayload = [
                    'medicine_id' => $validated['medicine_id'],
                    'quantity' => $validated['quantity'],
                ];
                if ($hasMedicineName) {
                    $salePayload['medicine_name'] = $medicine->name;
                }
                if ($hasUnitPrice) {
                    $salePayload['unit_price'] = $unitPrice;
                }
                if ($hasTotalAmount) {
                    $salePayload['total_amount'] = $totalAmount;
                }
                if ($hasTotalPrice) {
                    $salePayload['total_price'] = $totalAmount;
                }
                if ($hasSaleDate) {
                    $salePayload['sale_date'] = now()->toDateString();
                }
                if ($hasCustomerName) {
                    $salePayload['customer_name'] = $validated['customer_name'] ?? 'Walk-in';
                }

                $sale->update($salePayload);

                $newInventory->decrement('quantity', $validated['quantity']);

                return response()->json([
                    'message' => 'Sale updated successfully',
                    'data' => $sale->fresh()->load('medicine')
                ]);
            });
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Sale $sale): JsonResponse
    {
        try {
            DB::transaction(function () use ($sale) {
                $inventory = Inventory::where('medicine_id', $sale->medicine_id)->first();
                if ($inventory) {
                    $inventory->increment('quantity', $sale->quantity);
                }
                $sale->delete();
            });

            return response()->json(['message' => 'Sale deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting sale: ' . $e->getMessage()
            ], 500);
        }
    }
}
