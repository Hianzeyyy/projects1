<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class MedicineController extends Controller
{
    public function index(): JsonResponse
    {
        $orderColumn = Schema::hasColumn('medicines', 'created_at') ? 'created_at' : 'id';
        $medicines = Medicine::orderBy($orderColumn, 'desc')->get();
        return response()->json($medicines);
    }

    public function store(Request $request): JsonResponse
    {
        $medicineColumns = Schema::getColumnListing('medicines');
        $hasMedicineCategory = in_array('category', $medicineColumns, true);
        $hasMedicineManufacturer = in_array('manufacturer', $medicineColumns, true);
        $hasMedicinePrice = in_array('price', $medicineColumns, true);
        $hasMedicineDescription = in_array('description', $medicineColumns, true);
        $hasMedicineExpiry = in_array('expiry_date', $medicineColumns, true);
        $hasMedicineStock = in_array('stock', $medicineColumns, true);
        $hasMedicineSupplierId = in_array('supplier_id', $medicineColumns, true);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => $hasMedicineCategory ? 'required|string|max:100' : 'nullable|string|max:100',
            'manufacturer' => $hasMedicineManufacturer ? 'required|string|max:255' : 'nullable|string|max:255',
            'price' => $hasMedicinePrice ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'description' => $hasMedicineDescription ? 'nullable|string' : 'nullable|string',
            'expiry_date' => $hasMedicineExpiry ? 'required|date' : 'nullable|date',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'stock' => $hasMedicineStock ? 'nullable|integer|min:0' : 'nullable|integer|min:0',
        ]);

        $medicinePayload = ['name' => $validated['name']];
        if ($hasMedicineCategory) {
            $medicinePayload['category'] = $validated['category'] ?? null;
        }
        if ($hasMedicineManufacturer) {
            $medicinePayload['manufacturer'] = $validated['manufacturer'] ?? null;
        }
        if ($hasMedicinePrice) {
            $medicinePayload['price'] = $validated['price'] ?? 0;
        }
        if ($hasMedicineDescription) {
            $medicinePayload['description'] = $validated['description'] ?? null;
        }
        if ($hasMedicineExpiry && !empty($validated['expiry_date'])) {
            $medicinePayload['expiry_date'] = $validated['expiry_date'];
        }
        if ($hasMedicineStock) {
            $medicinePayload['stock'] = $validated['stock'] ?? 0;
        }
        if ($hasMedicineSupplierId && !empty($validated['supplier_id'])) {
            $medicinePayload['supplier_id'] = $validated['supplier_id'];
        }

        $medicine = Medicine::create($medicinePayload);

        // Keep inventory aligned with medicines by creating a starter stock record.
        $hasInventoryMedicineName = Schema::hasColumn('inventory', 'medicine_name');
        $hasInventorySupplierName = Schema::hasColumn('inventory', 'supplier_name');
        $hasInventoryBatchNumber = Schema::hasColumn('inventory', 'batch_number');
        $hasInventoryExpiryDate = Schema::hasColumn('inventory', 'expiry_date');

        $defaultSupplier = null;
        if (!empty($validated['supplier_id'])) {
            $defaultSupplier = Supplier::query()->find($validated['supplier_id']);
        }
        if (!$defaultSupplier && $hasMedicineSupplierId && !empty($medicine->supplier_id)) {
            $defaultSupplier = Supplier::query()->find($medicine->supplier_id);
        }
        if (!$defaultSupplier) {
            $defaultSupplier = Supplier::query()->orderBy('id')->first();
        }

        if ($defaultSupplier) {
            $payload = [
                'medicine_id' => $medicine->id,
                'quantity' => 0,
                'supplier_id' => $defaultSupplier->id,
                'reorder_level' => 10,
            ];
            if ($hasInventoryMedicineName) {
                $payload['medicine_name'] = $medicine->name;
            }
            if ($hasInventorySupplierName) {
                $payload['supplier_name'] = $defaultSupplier->name;
            }
            if ($hasInventoryBatchNumber) {
                $payload['batch_number'] = 'AUTO-' . str_pad((string) $medicine->id, 5, '0', STR_PAD_LEFT);
            }
            if ($hasInventoryExpiryDate) {
                $payload['expiry_date'] = $validated['expiry_date'] ?? ($hasMedicineExpiry ? $medicine->expiry_date : now()->toDateString());
            }

            Inventory::create($payload);
        }

        return response()->json([
            'message' => 'Medicine created successfully',
            'data' => $medicine
        ], 201);
    }

    public function show(Medicine $medicine): JsonResponse
    {
        return response()->json($medicine);
    }

    public function update(Request $request, Medicine $medicine): JsonResponse
    {
        $medicineColumns = Schema::getColumnListing('medicines');
        $hasMedicineCategory = in_array('category', $medicineColumns, true);
        $hasMedicineManufacturer = in_array('manufacturer', $medicineColumns, true);
        $hasMedicinePrice = in_array('price', $medicineColumns, true);
        $hasMedicineDescription = in_array('description', $medicineColumns, true);
        $hasMedicineExpiry = in_array('expiry_date', $medicineColumns, true);
        $hasMedicineStock = in_array('stock', $medicineColumns, true);
        $hasMedicineSupplierId = in_array('supplier_id', $medicineColumns, true);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => $hasMedicineCategory ? 'required|string|max:100' : 'nullable|string|max:100',
            'manufacturer' => $hasMedicineManufacturer ? 'required|string|max:255' : 'nullable|string|max:255',
            'price' => $hasMedicinePrice ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'description' => $hasMedicineDescription ? 'nullable|string' : 'nullable|string',
            'expiry_date' => $hasMedicineExpiry ? 'required|date' : 'nullable|date',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'stock' => $hasMedicineStock ? 'nullable|integer|min:0' : 'nullable|integer|min:0',
        ]);

        $medicinePayload = ['name' => $validated['name']];
        if ($hasMedicineCategory) {
            $medicinePayload['category'] = $validated['category'] ?? null;
        }
        if ($hasMedicineManufacturer) {
            $medicinePayload['manufacturer'] = $validated['manufacturer'] ?? null;
        }
        if ($hasMedicinePrice) {
            $medicinePayload['price'] = $validated['price'] ?? 0;
        }
        if ($hasMedicineDescription) {
            $medicinePayload['description'] = $validated['description'] ?? null;
        }
        if ($hasMedicineExpiry && !empty($validated['expiry_date'])) {
            $medicinePayload['expiry_date'] = $validated['expiry_date'];
        }
        if ($hasMedicineStock && array_key_exists('stock', $validated) && $validated['stock'] !== null) {
            $medicinePayload['stock'] = $validated['stock'];
        }
        if ($hasMedicineSupplierId && array_key_exists('supplier_id', $validated) && $validated['supplier_id']) {
            $medicinePayload['supplier_id'] = $validated['supplier_id'];
        }

        $medicine->update($medicinePayload);

        // Keep denormalized inventory fields in sync when medicine details change.
        $inventoryUpdate = [];
        if (Schema::hasColumn('inventory', 'medicine_name')) {
            $inventoryUpdate['medicine_name'] = $medicine->name;
        }
        if (!empty($validated['supplier_id']) && Schema::hasColumn('inventory', 'supplier_id')) {
            $inventoryUpdate['supplier_id'] = $validated['supplier_id'];
            if (Schema::hasColumn('inventory', 'supplier_name')) {
                $selectedSupplier = Supplier::query()->find($validated['supplier_id']);
                if ($selectedSupplier) {
                    $inventoryUpdate['supplier_name'] = $selectedSupplier->name;
                }
            }
        }
        if (Schema::hasColumn('inventory', 'expiry_date')) {
            if ($hasMedicineExpiry && !empty($medicine->expiry_date)) {
                $inventoryUpdate['expiry_date'] = $medicine->expiry_date;
            } elseif (!empty($validated['expiry_date'])) {
                $inventoryUpdate['expiry_date'] = $validated['expiry_date'];
            }
        }

        if (!empty($inventoryUpdate)) {
            Inventory::query()
                ->where('medicine_id', $medicine->id)
                ->update($inventoryUpdate);
        }

        return response()->json([
            'message' => 'Medicine updated successfully',
            'data' => $medicine
        ]);
    }

    public function destroy(Medicine $medicine): JsonResponse
    {
        $medicine->delete();

        return response()->json([
            'message' => 'Medicine deleted successfully'
        ]);
    }
}
