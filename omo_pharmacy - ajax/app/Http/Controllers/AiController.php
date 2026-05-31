<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function index()
    {
        return view('ai.index', [
            'result' => null,
            'input' => [
                'patient_name' => '',
                'age' => '',
                'symptoms' => '',
                'allergies' => '',
                'notes' => '',
            ],
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:120',
            'symptoms' => 'required|string|min:3|max:1000',
            'allergies' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $symptomKeywords = $this->extractKeywords((string) $validated['symptoms']);
        $allergyKeywords = $this->extractKeywords((string) ($validated['allergies'] ?? ''));

        $notesKeywords = $this->extractKeywords((string) ($validated['notes'] ?? ''));

        $allMedicines = Medicine::query()->get(['id', 'name', 'description', 'stock', 'price']);

        $medicineCandidates = $allMedicines
            ->map(function (Medicine $medicine) use ($symptomKeywords) {
                $name = strtolower((string) ($medicine->name ?? ''));
                $description = strtolower((string) ($medicine->description ?? ''));
                $haystack = trim($name . ' ' . $description);
                $score = 0;
                $matchedTerms = [];

                foreach ($symptomKeywords as $keyword) {
                    $word = (string) $keyword;

                    if (str_contains($name, $word)) {
                        $score += 2;
                        $matchedTerms[] = $word;
                        continue;
                    }

                    if (str_contains($description, $word) || str_contains($haystack, $word)) {
                        $score++;
                        $matchedTerms[] = $word;
                    }
                }

                return [
                    'id' => $medicine->id,
                    'name' => $medicine->name,
                    'description' => $medicine->description,
                    'stock' => (int) $medicine->stock,
                    'price' => (float) $medicine->price,
                    'score' => $score,
                    'matched_terms' => array_values(array_unique($matchedTerms)),
                ];
            })
            ->sortByDesc('score')
            ->filter(static fn (array $row) => $row['score'] > 0 && $row['stock'] > 0)
            ->take(7)
            ->values();

        $age = (int) $validated['age'];

        $recommendations = $medicineCandidates->map(static function (array $row) use ($age): array {
            $confidence = 'Low';
            if ($row['score'] >= 4) {
                $confidence = 'High';
            } elseif ($row['score'] >= 2) {
                $confidence = 'Medium';
            }

            $dosageReminder = $age < 12 || $age > 65
                ? 'Review age-adjusted dosage before dispensing.'
                : 'Use standard dosing guidance and verify with pharmacist review.';

            return [
                'name' => $row['name'],
                'stock' => $row['stock'],
                'price' => number_format($row['price'], 2),
                'reason' => 'Matched symptom keywords and currently in stock.',
                'confidence' => $confidence,
                'match_terms' => implode(', ', $row['matched_terms']),
                'dosage_reminder' => $dosageReminder,
            ];
        })->all();

        $riskFlags = [];
        if ($allergyKeywords->isNotEmpty()) {
            $riskFlags[] = 'Allergy information provided: verify contraindications before dispensing.';
        }
        if ($age < 12 || $age > 65) {
            $riskFlags[] = 'Age-sensitive patient group detected. Review dosage carefully.';
        }

        $urgentKeywords = ['chest', 'bleeding', 'fainting', 'seizure', 'breathing'];
        $hasUrgentSymptom = $symptomKeywords->contains(static function ($term) use ($urgentKeywords) {
            foreach ($urgentKeywords as $urgentKeyword) {
                if (str_contains((string) $term, $urgentKeyword)) {
                    return true;
                }
            }

            return false;
        });

        if ($hasUrgentSymptom) {
            $riskFlags[] = 'Potential urgent symptom detected. Escalate for immediate clinical review.';
        }

        $careSteps = [
            'Confirm symptoms and allergy history with the patient before dispensing.',
            'Double-check medicine stock, expiry, and dosage instruction.',
            'Document counseling notes and monitor patient response.',
        ];

        if ($notesKeywords->isNotEmpty()) {
            $careSteps[] = 'Clinical notes were provided. Include them in pharmacist verification.';
        }

        $result = [
            'patient' => $validated['patient_name'],
            'age' => $age,
            'summary' => [
                'symptom_terms' => $symptomKeywords->values()->all(),
                'allergy_terms' => $allergyKeywords->values()->all(),
                'candidate_count' => $allMedicines->count(),
                'match_count' => count($recommendations),
            ],
            'recommendations' => $recommendations,
            'risk_flags' => $riskFlags,
            'care_steps' => $careSteps,
            'no_match_tips' => [
                'Try broader symptom terms (e.g., fever, cough, pain, allergy).',
                'Check medicine descriptions in inventory for relevant keywords.',
                'If symptoms are severe or unclear, escalate to pharmacist evaluation.',
            ],
            'disclaimer' => 'AI support only. Final dispensing decision must be approved by a licensed pharmacist.',
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'result' => $result,
                'input' => $validated,
            ]);
        }

        return view('ai.index', [
            'result' => $result,
            'input' => $validated,
        ]);
    }

    private function extractKeywords(string $value)
    {
        return collect(preg_split('/\s+/', strtolower(trim($value))))
            ->map(static function ($word) {
                return preg_replace('/[^a-z0-9\-]/', '', (string) $word);
            })
            ->filter(static fn ($word) => strlen((string) $word) >= 3)
            ->unique()
            ->values();
    }
}
