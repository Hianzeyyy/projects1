<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function index(): JsonResponse
    {
        $inventory = Inventory::with(['medicine', 'supplier'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($inventory);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);
        $supplier = Supplier::findOrFail($validated['supplier_id']);

        $validated['medicine_name'] = $medicine->name;
        $validated['supplier_name'] = $supplier->name;

        $inventory = Inventory::create($validated);

        return response()->json([
            'message' => 'Inventory item created successfully',
            'data' => $inventory
        ], 201);
    }

    public function show(Inventory $inventory): JsonResponse
    {
        return response()->json($inventory->load(['medicine', 'supplier']));
    }

    public function update(Request $request, Inventory $inventory): JsonResponse
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);
        $supplier = Supplier::findOrFail($validated['supplier_id']);

        $validated['medicine_name'] = $medicine->name;
        $validated['supplier_name'] = $supplier->name;

        $inventory->update($validated);

        return response()->json([
            'message' => 'Inventory updated successfully',
            'data' => $inventory
        ]);
    }

    public function destroy(Inventory $inventory): JsonResponse
    {
        $inventory->delete();

        return response()->json([
            'message' => 'Inventory item deleted successfully'
        ]);
    }
}
