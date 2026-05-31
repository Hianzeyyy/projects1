<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MedicineController extends Controller
{
    public function index(): JsonResponse
    {
        $medicines = Medicine::orderBy('created_at', 'desc')->get();
        return response()->json($medicines);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expiry_date' => 'required|date',
        ]);

        $medicine = Medicine::create($validated);

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expiry_date' => 'required|date',
        ]);

        $medicine->update($validated);

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
