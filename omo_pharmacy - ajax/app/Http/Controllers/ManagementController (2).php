<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Supplier;
use App\Services\RiskAssessmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $profiles = DB::table('patient_medical_profiles')
            ->select('id', 'age')
            ->orderBy('id')
            ->get();

        return view('management.index', [
            'type' => 'sales',
            'medicines' => $medicines,
            'sampleSale' => $sampleSale,
            'profiles' => $profiles,
        ]);
    }

    /**
     * Assess sale risk instantly for checkout gating.
     */
    public function assessSaleRisk(Request $request, RiskAssessmentService $riskAssessmentService): JsonResponse
    {
        $validated = $request->validate([
            'patient_profile_id' => ['required', 'integer', 'exists:patient_medical_profiles,id'],
            'medicine_id' => ['required', 'integer', 'exists:medicines,id'],
            'current_medication_ingredients' => ['nullable', 'array'],
            'current_medication_ingredients.*' => ['nullable', 'string', 'max:100'],
            'requested_dosage_mg' => ['nullable', 'numeric', 'min:0'],
        ]);

        $medicine = Medicine::findOrFail((int) $validated['medicine_id']);
        $ingredients = collect(explode(',', (string) ($medicine->description ?? '')))
            ->map(fn (string $part): string => trim($part))
            ->filter(fn (string $part): bool => $part !== '')
            ->values()
            ->all();

        if (empty($ingredients)) {
            $ingredients = [(string) $medicine->name];
        }

        $assessment = $riskAssessmentService->assess(
            patientProfileId: (int) $validated['patient_profile_id'],
            newDrugName: (string) $medicine->name,
            newDrugIngredients: $ingredients,
            currentMedicationIngredients: (array) ($validated['current_medication_ingredients'] ?? []),
            requestedDosageMg: isset($validated['requested_dosage_mg']) ? (float) $validated['requested_dosage_mg'] : null,
        );

        $renderedAlerts = collect($assessment['checks'] ?? [])->mapWithKeys(function (array $check, string $key): array {
            $titleMap = [
                'allergy_match' => 'Allergy Match',
                'drug_interaction' => 'Drug-Drug Interaction',
                'dosage_safety' => 'Dosage Safety',
            ];

            return [
                $key => view('components.risk-alert', [
                    'status' => (string) ($check['status'] ?? 'safe'),
                    'title' => $titleMap[$key] ?? 'Risk Check',
                    'message' => (string) ($check['message'] ?? 'No details provided.'),
                ])->render(),
            ];
        })->all();

        return response()->json([
            'ok' => true,
            'risk_level' => (string) ($assessment['overall_status'] ?? 'warning'),
            'checkout_allowed' => (string) ($assessment['overall_status'] ?? 'warning') !== 'critical',
            'summary' => (string) ($assessment['summary'] ?? ''),
            'fragments' => [
                'alert_box' => view('management.partials.sale-risk-alerts', [
                    'summary' => (string) ($assessment['summary'] ?? ''),
                    'alerts' => $renderedAlerts,
                ])->render(),
            ],
            'data' => $assessment,
        ]);
    }

    /**
     * Store a newly created sale.
     */
    public function storeSale(Request $request): \Illuminate\Http\RedirectResponse|JsonResponse
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);

        $total_amount = $medicine->price * $request->quantity;

        $sale = Sale::create([
            'medicine_id' => $request->medicine_id,
            'medicine_name' => $medicine->name,
            'quantity' => $request->quantity,
            'unit_price' => $medicine->price,
            'total_amount' => $total_amount,
            'total_price' => $total_amount,
            'customer_name' => $request->customer_name,
            'sale_date' => now()->toDateString(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Sale recorded successfully.',
                'sale' => [
                    'id' => $sale->id,
                    'medicine_name' => $sale->medicine_name,
                    'quantity' => $sale->quantity,
                    'total_amount' => $sale->total_amount,
                ],
                'redirect_to' => route('records.sales'),
            ]);
        }

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
