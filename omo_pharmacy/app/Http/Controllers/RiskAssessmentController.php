<?php

namespace App\Http\Controllers;

use App\Services\RiskAssessmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RiskAssessmentController extends Controller
{
    private RiskAssessmentService $riskAssessmentService;

    public function __construct(RiskAssessmentService $riskAssessmentService)
    {
        $this->riskAssessmentService = $riskAssessmentService;
    }

    /**
     * Simple demo page for risk checks.
     */
    public function index(): View
    {
        $profiles = DB::table('patient_medical_profiles')
            ->select('id', 'age')
            ->orderBy('id')
            ->get();

        return view('risk-alerts.index', [
            'profiles' => $profiles,
        ]);
    }

    /**
     * Return risk assessment as JSON for frontend fetch.
     */
    public function assess(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'patient_profile_id' => ['required', 'integer', 'exists:patient_medical_profiles,id'],
            'new_drug_name' => ['required', 'string', 'max:255'],
            'new_drug_ingredients' => ['required', 'array', 'min:1'],
            'new_drug_ingredients.*' => ['required', 'string', 'max:100'],
            'current_medication_ingredients' => ['nullable', 'array'],
            'current_medication_ingredients.*' => ['nullable', 'string', 'max:100'],
            'requested_dosage_mg' => ['nullable', 'numeric', 'min:0'],
        ]);

        $assessment = $this->riskAssessmentService->assess(
            patientProfileId: (int) $validated['patient_profile_id'],
            newDrugName: (string) $validated['new_drug_name'],
            newDrugIngredients: (array) $validated['new_drug_ingredients'],
            currentMedicationIngredients: (array) ($validated['current_medication_ingredients'] ?? []),
            requestedDosageMg: isset($validated['requested_dosage_mg']) ? (float) $validated['requested_dosage_mg'] : null,
        );

        $titleMap = [
            'allergy_match' => 'Allergy Match',
            'drug_interaction' => 'Drug-Drug Interaction',
            'dosage_safety' => 'Dosage Safety',
        ];

        $renderedAlerts = collect($assessment['checks'] ?? [])->mapWithKeys(function (array $check, string $key) use ($titleMap): array {
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
            'data' => $assessment,
            'rendered_alerts' => $renderedAlerts,
        ]);
    }
}
