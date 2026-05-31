<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    /**
     * Display the Dashboard and Records.
     */
    public function index()
    {
        // Prototype: Dashboard (Status/Number of Records)
        $totalItems = DB::table('pharmacy_inventory')->count();

        // Prototype: Information and Records (View)
        $items = DB::table('pharmacy_inventory')->get();

        // Calling the view file: resources/views/pharmacy_main.php
        return view('pharmacy_main', [
            'total' => $totalItems,
            'records' => $items
        ]);
    }

    /**
     * Prototype: Management Form (Adding Records)
     */
    public function store(Request $request)
    {
        DB::table('pharmacy_inventory')->insert([
            'med_name'   => $request->name,
            'category'   => $request->cat,
            'stock_qty'  => $request->qty,
            'price'      => $request->price,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/');
    }

    /**
     * Prototype: Information and Records (Delete)
     */
    public function destroy($id)
    {
        DB::table('pharmacy_inventory')->where('id', $id)->delete();
        return redirect('/');
    }
}
