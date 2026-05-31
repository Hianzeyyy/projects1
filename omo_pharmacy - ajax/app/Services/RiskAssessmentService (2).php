<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RiskAssessmentService
{
    /**
     * Assess dispensing risk using allergy checks, interaction checks, and dosage safety.
     *
     * @param array<int, string> $newDrugIngredients
     * @param array<int, string> $currentMedicationIngredients
     * @return array<string, mixed>
     */
    public function assess(
        int $patientProfileId,
        string $newDrugName,
        array $newDrugIngredients,
        array $currentMedicationIngredients = [],
        ?float $requestedDosageMg = null
    ): array {
        $profile = DB::table('patient_medical_profiles')->where('id', $patientProfileId)->first();

        if (! $profile) {
            return [
                'overall_status' => 'critical',
                'summary' => 'Patient profile was not found.',
                'checks' => [
                    'allergy_match' => [
                        'status' => 'critical',
                        'message' => 'Cannot verify allergies because patient profile is missing.',
                        'matches' => [],
                    ],
                    'drug_interaction' => [
                        'status' => 'warning',
                        'message' => 'Interaction check was skipped because patient profile is missing.',
                        'conflicts' => [],
                    ],
                    'dosage_safety' => [
                        'status' => 'warning',
                        'message' => 'Dosage safety check was skipped because patient profile is missing.',
                        'age' => null,
                        'max_safe_dose_mg' => null,
                        'requested_dose_mg' => $requestedDosageMg,
                    ],
                ],
            ];
        }

        $allergies = $this->normalizeList(json_decode((string) ($profile->allergies ?? '[]'), true));
        $ingredients = $this->normalizeList($newDrugIngredients);
        $currentIngredients = $this->normalizeList($currentMedicationIngredients);
        $age = (int) ($profile->age ?? 0);

        $allergyMatches = $this->findPartialMatches($ingredients, $allergies);

        $allergyCheck = [
            'status' => empty($allergyMatches) ? 'safe' : 'critical',
            'message' => empty($allergyMatches)
                ? 'No allergy match found for the selected medicine.'
                : 'Allergy conflict detected. Dispensing should be blocked unless overridden by a manager.',
            'matches' => $allergyMatches,
        ];

        $interactionConflicts = $this->findDrugInteractions($ingredients, $currentIngredients);

        $hasCriticalInteraction = collect($interactionConflicts)->contains(
            fn (array $conflict): bool => ($conflict['severity'] ?? 'warning') === 'critical'
        );

        $interactionStatus = 'safe';
        if (! empty($interactionConflicts)) {
            $interactionStatus = $hasCriticalInteraction ? 'critical' : 'warning';
        }

        $interactionCheck = [
            'status' => $interactionStatus,
            'message' => empty($interactionConflicts)
                ? 'No known drug interaction was found.'
                : 'Potential interaction detected between the new drug and current medication ingredients.',
            'conflicts' => $interactionConflicts,
        ];

        $dosageCheck = $this->assessDosageByAge($age, $requestedDosageMg);

        $overallStatus = $this->resolveOverallStatus([
            $allergyCheck['status'],
            $interactionCheck['status'],
            $dosageCheck['status'],
        ]);

        return [
            'overall_status' => $overallStatus,
            'summary' => $this->buildSummary($overallStatus, $newDrugName),
            'patient' => [
                'profile_id' => $patientProfileId,
                'age' => $age,
                'allergies' => $allergies,
            ],
            'new_drug' => [
                'name' => $newDrugName,
                'ingredients' => $ingredients,
            ],
            'checks' => [
                'allergy_match' => $allergyCheck,
                'drug_interaction' => $interactionCheck,
                'dosage_safety' => $dosageCheck,
            ],
        ];
    }

    /**
     * @param mixed $values
     * @return array<int, string>
     */
    private function normalizeList(mixed $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        return collect($values)
            ->map(fn (mixed $value): string => strtolower(trim((string) $value)))
            ->filter(fn (string $value): bool => $value !== '')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param array<int, string> $needles
     * @param array<int, string> $haystack
     * @return array<int, string>
     */
    private function findPartialMatches(array $needles, array $haystack): array
    {
        $matches = [];

        foreach ($needles as $needle) {
            foreach ($haystack as $item) {
                if (str_contains($needle, $item) || str_contains($item, $needle)) {
                    $matches[] = $needle;
                }
            }
        }

        return collect($matches)->unique()->values()->all();
    }

    /**
     * @param array<int, string> $newIngredients
     * @param array<int, string> $currentIngredients
     * @return array<int, array<string, string>>
     */
    private function findDrugInteractions(array $newIngredients, array $currentIngredients): array
    {
        if (empty($newIngredients) || empty($currentIngredients)) {
            return [];
        }

        $allConflicts = [];

        foreach ($newIngredients as $newIngredient) {
            foreach ($currentIngredients as $currentIngredient) {
                $row = DB::table('drug_interactions')
                    ->where(function ($query) use ($newIngredient, $currentIngredient): void {
                        $query->where('ingredient', $newIngredient)
                            ->where('conflicting_ingredient', $currentIngredient);
                    })
                    ->orWhere(function ($query) use ($newIngredient, $currentIngredient): void {
                        $query->where('ingredient', $currentIngredient)
                            ->where('conflicting_ingredient', $newIngredient);
                    })
                    ->first();

                if ($row) {
                    $allConflicts[] = [
                        'new_ingredient' => $newIngredient,
                        'current_ingredient' => $currentIngredient,
                        'severity' => (string) $row->severity,
                        'note' => (string) ($row->note ?? ''),
                    ];
                }
            }
        }

        return $allConflicts;
    }

    /**
     * @return array<string, int|float|string|null>
     */
    private function assessDosageByAge(int $age, ?float $requestedDosageMg): array
    {
        $maxSafeDose = 1000;
        if ($age < 12) {
            $maxSafeDose = 250;
        } elseif ($age >= 65) {
            $maxSafeDose = 500;
        }

        if ($requestedDosageMg === null) {
            return [
                'status' => 'warning',
                'message' => 'Requested dosage is not provided. Please verify dosage before dispensing.',
                'age' => $age,
                'max_safe_dose_mg' => $maxSafeDose,
                'requested_dose_mg' => null,
            ];
        }

        if ($requestedDosageMg > $maxSafeDose) {
            return [
                'status' => 'critical',
                'message' => 'Requested dosage is above the age-safe maximum.',
                'age' => $age,
                'max_safe_dose_mg' => $maxSafeDose,
                'requested_dose_mg' => $requestedDosageMg,
            ];
        }

        return [
            'status' => 'safe',
            'message' => 'Requested dosage is within the age-safe limit.',
            'age' => $age,
            'max_safe_dose_mg' => $maxSafeDose,
            'requested_dose_mg' => $requestedDosageMg,
        ];
    }

    /**
     * @param array<int, string> $statuses
     */
    private function resolveOverallStatus(array $statuses): string
    {
        if (in_array('critical', $statuses, true)) {
            return 'critical';
        }

        if (in_array('warning', $statuses, true)) {
            return 'warning';
        }

        return 'safe';
    }

    private function buildSummary(string $overallStatus, string $newDrugName): string
    {
        if ($overallStatus === 'critical') {
            return 'Critical risks detected for ' . $newDrugName . '. Dispensing should be blocked unless overridden.';
        }

        if ($overallStatus === 'warning') {
            return 'Warnings detected for ' . $newDrugName . '. Pharmacist acknowledgment is required.';
        }

        return 'No immediate risks detected for ' . $newDrugName . '. Medicine is safe to dispense.';
    }
}
