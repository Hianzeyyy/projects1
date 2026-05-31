<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class InventoryController extends Controller
{
    public function index(): JsonResponse
    {
        $this->syncMissingInventoryFromMedicines();

        $inventory = Inventory::with(['medicine', 'supplier'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($inventory);
    }

    public function store(Request $request): JsonResponse
    {
        $hasBatchNumber = Schema::hasColumn('inventory', 'batch_number');
        $hasExpiryDate = Schema::hasColumn('inventory', 'expiry_date');
        $hasMedicineName = Schema::hasColumn('inventory', 'medicine_name');
        $hasSupplierName = Schema::hasColumn('inventory', 'supplier_name');

        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'batch_number' => $hasBatchNumber ? 'nullable|string|max:100' : 'nullable',
            'expiry_date' => $hasExpiryDate ? 'nullable|date' : 'nullable',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);
        $supplier = Supplier::findOrFail($validated['supplier_id']);

        if ($hasMedicineName) {
            $validated['medicine_name'] = $medicine->name;
        }
        if ($hasSupplierName) {
            $validated['supplier_name'] = $supplier->name;
        }
        if ($hasBatchNumber) {
            $validated['batch_number'] = $validated['batch_number'] ?? 'AUTO-' . str_pad((string) $medicine->id, 5, '0', STR_PAD_LEFT);
        } else {
            unset($validated['batch_number']);
        }
        if ($hasExpiryDate) {
            $validated['expiry_date'] = $validated['expiry_date'] ?? now()->toDateString();
        } else {
            unset($validated['expiry_date']);
        }

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
        $hasBatchNumber = Schema::hasColumn('inventory', 'batch_number');
        $hasExpiryDate = Schema::hasColumn('inventory', 'expiry_date');
        $hasMedicineName = Schema::hasColumn('inventory', 'medicine_name');
        $hasSupplierName = Schema::hasColumn('inventory', 'supplier_name');

        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'batch_number' => $hasBatchNumber ? 'nullable|string|max:100' : 'nullable',
            'expiry_date' => $hasExpiryDate ? 'nullable|date' : 'nullable',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);
        $supplier = Supplier::findOrFail($validated['supplier_id']);

        if ($hasMedicineName) {
            $validated['medicine_name'] = $medicine->name;
        }
        if ($hasSupplierName) {
            $validated['supplier_name'] = $supplier->name;
        }
        if ($hasBatchNumber) {
            $validated['batch_number'] = $validated['batch_number'] ?? $inventory->batch_number ?? ('AUTO-' . str_pad((string) $medicine->id, 5, '0', STR_PAD_LEFT));
        } else {
            unset($validated['batch_number']);
        }
        if ($hasExpiryDate) {
            $validated['expiry_date'] = $validated['expiry_date'] ?? $inventory->expiry_date ?? now()->toDateString();
        } else {
            unset($validated['expiry_date']);
        }

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

    private function syncMissingInventoryFromMedicines(): void
    {
        $defaultSupplier = Supplier::query()->orderBy('id')->first();
        if (!$defaultSupplier) {
            return;
        }

        $hasInventoryBatchNumber = Schema::hasColumn('inventory', 'batch_number');
        $hasInventoryExpiryDate = Schema::hasColumn('inventory', 'expiry_date');
        $hasInventoryMedicineName = Schema::hasColumn('inventory', 'medicine_name');
        $hasInventorySupplierName = Schema::hasColumn('inventory', 'supplier_name');
        $hasMedicineExpiry = Schema::hasColumn('medicines', 'expiry_date');
        $hasMedicineSupplierId = Schema::hasColumn('medicines', 'supplier_id');

        $existingMedicineIds = Inventory::query()->pluck('medicine_id')->all();

        $medicineColumns = ['id', 'name'];
        if ($hasMedicineExpiry) {
            $medicineColumns[] = 'expiry_date';
        }
        if ($hasMedicineSupplierId) {
            $medicineColumns[] = 'supplier_id';
        }

        $missingMedicines = Medicine::query()
            ->when(!empty($existingMedicineIds), function ($query) use ($existingMedicineIds) {
                $query->whereNotIn('id', $existingMedicineIds);
            })
            ->get($medicineColumns);

        foreach ($missingMedicines as $medicine) {
            $chosenSupplier = $defaultSupplier;
            if ($hasMedicineSupplierId && !empty($medicine->supplier_id)) {
                $supplierFromMedicine = Supplier::query()->find($medicine->supplier_id);
                if ($supplierFromMedicine) {
                    $chosenSupplier = $supplierFromMedicine;
                }
            }

            $payload = [
                'medicine_id' => $medicine->id,
                'quantity' => 0,
                'supplier_id' => $chosenSupplier->id,
                'reorder_level' => 10,
            ];
            if ($hasInventoryMedicineName) {
                $payload['medicine_name'] = $medicine->name;
            }
            if ($hasInventorySupplierName) {
                $payload['supplier_name'] = $chosenSupplier->name;
            }
            if ($hasInventoryBatchNumber) {
                $payload['batch_number'] = 'AUTO-' . str_pad((string) $medicine->id, 5, '0', STR_PAD_LEFT);
            }
            if ($hasInventoryExpiryDate) {
                $payload['expiry_date'] = ($hasMedicineExpiry && !empty($medicine->expiry_date)) ? $medicine->expiry_date : now()->toDateString();
            }

            Inventory::create($payload);
        }
    }
}
