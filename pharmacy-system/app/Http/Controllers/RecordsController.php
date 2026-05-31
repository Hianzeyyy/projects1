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
        return view('records', [
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
        return view('details', [
            'type' => 'medicines',
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
        return view('records', [
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
        return view('records', [
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
        $medicines = Medicine::orderBy('name')->get();

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
            'customer_name' => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);

        $sale->update([
            'medicine_id' => $medicine->id,
            'medicine_name' => $medicine->name,
            'quantity' => $validated['quantity'],
            'unit_price' => $medicine->price,
            'total_amount' => $validated['quantity'] * $medicine->price,
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
        $suppliers = Supplier::latest()->get();
        return view('records', [
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
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
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
