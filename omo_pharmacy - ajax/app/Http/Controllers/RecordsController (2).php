<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RecordsController extends Controller
{
    // ============ MEDICINE RECORDS ============

    /**
     * Display a listing of medicines.
     */
    public function indexMedicines(): View
    {
        $medicines = Medicine::latest('created_at')->get();
        return view('records.index', [
            'type' => 'medicines',
            'medicines' => $medicines
        ]);
    }

    /**
     * Display the specified medicine.
     */
    public function showMedicine(string $id): View
    {
        $medicine = Medicine::findOrFail($id);
        return view('details', [
            'type' => 'medicines',
            'action' => 'show',
            'medicine' => $medicine
        ]);
    }

    /**
     * Show the form for editing the specified medicine.
     */
    public function editMedicine(string $id): View
    {
        $medicine = Medicine::findOrFail($id);
        $suppliers = Supplier::query()->orderBy('name')->get();

        return view('details', [
            'type' => 'medicines',
            'action' => 'edit',
            'medicine' => $medicine,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Update the specified medicine in storage.
     */
    public function updateMedicine(Request $request, string $id): RedirectResponse
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
        ]);

        $medicine->update($validated);

        return redirect()->route('records.medicines')
            ->with('success', 'Medicine updated successfully.');
    }

    /**
     * Remove the specified medicine from storage.
     */
    public function destroyMedicine(string $id): RedirectResponse
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->route('records.medicines')
            ->with('success', 'Medicine deleted successfully.');
    }

    // ============ INVENTORY RECORDS ============

    /**
     * Display a listing of inventory.
     */
    public function indexInventory(Request $request): View|JsonResponse
    {
        $inventory = Medicine::query()
            ->leftJoin('suppliers', 'medicines.supplier_id', '=', 'suppliers.id')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = (string) $request->string('q');
                $query->where(function ($inner) use ($term) {
                    $inner->where('medicines.name', 'like', "%{$term}%")
                        ->orWhere('suppliers.name', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('medicines.created_at')
            ->get([
                'medicines.id as medicine_id',
                'medicines.name as medicine_name',
                'medicines.stock as quantity',
                'suppliers.name as supplier_name',
            ])
            ->map(function ($row) {
                return (object) [
                    'medicine_id' => $row->medicine_id,
                    'medicine_name' => $row->medicine_name,
                    'batch_number' => 'N/A',
                    'quantity' => (int) ($row->quantity ?? 0),
                    'reorder_level' => 10,
                    'supplier_name' => $row->supplier_name ?? 'N/A',
                    'expiry_date' => now()->toDateString(),
                ];
            });

        if ($request->expectsJson() || $request->ajax()) {
            $tableHtml = view('records.partials.table-inventory', [
                'inventory' => $inventory,
            ])->render();

            $lowStockCount = $inventory->filter(function ($item) {
                return $item->quantity <= $item->reorder_level;
            })->count();

            return response()->json([
                'ok' => true,
                'risk_level' => $lowStockCount > 0 ? 'warning' : 'safe',
                'counts' => [
                    'total' => $inventory->count(),
                    'low_stock' => $lowStockCount,
                ],
                'fragments' => [
                    'inventory_table' => $tableHtml,
                ],
            ]);
        }

        return view('records.index', [
            'type' => 'inventory',
            'inventory' => $inventory
        ]);
    }

    // ============ SALE RECORDS ============

    /**
     * Display a listing of sales.
     */
    public function indexSales(): View
    {
        $sales = Sale::latest('created_at')->get();
        return view('records.index', [
            'type' => 'sales',
            'sales' => $sales
        ]);
    }

    /**
     * Display the specified sale.
     */
    public function showSale(string $id): View
    {
        $sale = Sale::findOrFail($id);
        return view('details', [
            'type' => 'sales',
            'action' => 'show',
            'sale' => $sale
        ]);
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function editSale(string $id): View
    {
        $sale = Sale::findOrFail($id);
        $medicines = Medicine::query()->orderBy('name')->get();

        return view('details', [
            'type' => 'sales',
            'action' => 'edit',
            'sale' => $sale,
            'medicines' => $medicines,
        ]);
    }

    /**
     * Update the specified sale in storage.
     */
    public function updateSale(Request $request, string $id): RedirectResponse
    {
        $sale = Sale::findOrFail($id);

        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
            'customer_name' => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::findOrFail((string) $validated['medicine_id']);
        $unitPrice = (float) $medicine->price;
        $totalAmount = (int) $validated['quantity'] * $unitPrice;

        $sale->update([
            'medicine_id' => $medicine->id,
            'medicine_name' => $medicine->name,
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'total_price' => $totalAmount,
            'sale_date' => $validated['sale_date'],
            'customer_name' => $validated['customer_name'] ?? null,
        ]);

        return redirect()->route('records.sales')
            ->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroySale(string $id): RedirectResponse
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('records.sales')
            ->with('success', 'Sale deleted successfully.');
    }

    // ============ SUPPLIER RECORDS ============

    /**
     * Display a listing of suppliers.
     */
    public function indexSuppliers(): View
    {
        $suppliers = Supplier::latest('created_at')->get();
        return view('records.index', [
            'type' => 'suppliers',
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Display the specified supplier.
     */
    public function showSupplier(string $id): View
    {
        $supplier = Supplier::findOrFail($id);
        return view('details', [
            'type' => 'suppliers',
            'action' => 'show',
            'supplier' => $supplier
        ]);
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function editSupplier(string $id): View
    {
        $supplier = Supplier::findOrFail($id);
        return view('details', [
            'type' => 'suppliers',
            'action' => 'edit',
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified supplier in storage.
     */
    public function updateSupplier(Request $request, string $id): RedirectResponse
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('records.suppliers')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroySupplier(string $id): RedirectResponse
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('records.suppliers')
            ->with('success', 'Supplier deleted successfully.');
    }
}
