<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
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
        $medicines = Medicine::latest()->get();
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
            'type' => 'medicine',
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
        return view('details', [
            'type' => 'medicine',
            'action' => 'edit',
            'medicine' => $medicine
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
            'category' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expiry_date' => 'required|date',
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
    public function indexInventory(): View
    {
        $inventory = Inventory::with(['medicine', 'supplier'])->latest()->get();
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
        $sales = Sale::latest()->get();
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
            'type' => 'sale',
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
        return view('details', [
            'type' => 'sale',
            'action' => 'edit',
            'sale' => $sale
        ]);
    }

    /**
     * Update the specified sale in storage.
     */
    public function updateSale(Request $request, string $id): RedirectResponse
    {
        $sale = Sale::findOrFail($id);

        $validated = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
        ]);

        $validated['total_amount'] = $validated['quantity'] * $validated['unit_price'];
        $sale->update($validated);

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
        $suppliers = Supplier::latest()->get();
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
            'type' => 'supplier',
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
            'type' => 'supplier',
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
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
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
