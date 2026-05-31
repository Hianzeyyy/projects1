<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    // ============ MEDICINE MANAGEMENT ============

    /**
     * Show the form for creating a new medicine.
     */
    public function createMedicine()
    {
        $suppliers = Supplier::query()->orderBy('name')->get();

        return view('management.index', [
            'type' => 'medicines',
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Store a newly created medicine.
     */
    public function storeMedicine(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        Medicine::create($request->only([
            'name',
            'description',
            'price',
            'stock',
            'supplier_id',
        ]));
        return redirect()->route('records.medicines')->with('success', 'Medicine created successfully.');
    }

    // ============ SALE MANAGEMENT ============

    /**
     * Show the form for creating a new sale.
     */
    public function createSale()
    {
        $medicines = Medicine::all();
        $sampleSale = Sale::with('medicine')->latest('id')->first();

        return view('management.index', [
            'type' => 'sales',
            'medicines' => $medicines,
            'sampleSale' => $sampleSale,
        ]);
    }

    /**
     * Store a newly created sale.
     */
    public function storeSale(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);

        $total_amount = $medicine->price * $request->quantity;

        Sale::create([
            'medicine_id' => $request->medicine_id,
            'medicine_name' => $medicine->name,
            'quantity' => $request->quantity,
            'unit_price' => $medicine->price,
            'total_amount' => $total_amount,
            'total_price' => $total_amount,
            'customer_name' => $request->customer_name,
            'sale_date' => now()->toDateString(),
        ]);

        return redirect()->route('records.sales')->with('success', 'Sale recorded successfully.');
    }

    // ============ SUPPLIER MANAGEMENT ============

    /**
     * Show the form for creating a new supplier.
     */
    public function createSupplier()
    {
        $sampleSupplier = Supplier::latest('id')->first();

        return view('management.index', [
            'type' => 'suppliers',
            'sampleSupplier' => $sampleSupplier,
        ]);
    }

    /**
     * Store a newly created supplier.
     */
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());
        return redirect()->route('records.suppliers')->with('success', 'Supplier created successfully.');
    }
}
