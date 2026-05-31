<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;

class AiPharmacyService
{
    public function parsePrescription(?string $rawText = null, ?string $imageDataUrl = null, ?string $imagePath = null): array
    {
        $rawText = trim((string) $rawText);
        $apiUrl = config('services.ai_pharmacy.api_url');
        $apiKey = config('services.ai_pharmacy.api_key');
        $model = config('services.ai_pharmacy.model', 'gpt-4o-mini');
        $timeout = (int) config('services.ai_pharmacy.timeout', 30);

        if (!$apiUrl || !$apiKey) {
            if ($imageDataUrl && $rawText === '') {
                $ocrText = $this->extractTextFromImage($imagePath);
                if ($ocrText !== '') {
                    return $this->heuristicFallback($ocrText);
                }

                return [
                    'items' => [],
                    'warning' => 'Image parsing needs AI API keys or local OCR (Tesseract). Install Tesseract and optionally set TESSERACT_BINARY in .env. You can still paste prescription text for local parsing.',
                ];
            }
            return $this->heuristicFallback($rawText);
        }

        $systemPrompt = 'You are a pharmacy NLP extractor. Return JSON only in this shape: {"items":[{"drug_name":"Amoxicillin","dosage":"500mg","duration":"7 days","quantity":1}]}. Extract drug_name, dosage, and duration. If quantity is missing, set quantity to 1.';

        $userContent = [];
        if ($rawText !== '') {
            $userContent[] = [
                'type' => 'text',
                'text' => 'Prescription text: ' . $rawText,
            ];
        }

        if ($imageDataUrl) {
            $userContent[] = [
                'type' => 'image_url',
                'image_url' => ['url' => $imageDataUrl],
            ];
        }

        if (!$userContent) {
            return ['items' => []];
        }

        $response = Http::timeout($timeout)
            ->withToken($apiKey)
            ->acceptJson()
            ->post($apiUrl, [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userContent],
                ],
                'temperature' => 0.1,
            ]);

        if (!$response->ok()) {
            return $this->heuristicFallback($rawText);
        }

        $content = data_get($response->json(), 'choices.0.message.content');
        if (!is_string($content) || trim($content) === '') {
            return $this->heuristicFallback($rawText);
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            // Handle cases where model wraps JSON with markdown fences or extra text.
            if (preg_match('/```(?:json)?\s*(\{[\s\S]*\})\s*```/i', $content, $m)) {
                $decoded = json_decode($m[1], true);
            }
        }
        if (!is_array($decoded)) {
            if (preg_match('/\{[\s\S]*\}/', $content, $m)) {
                $decoded = json_decode($m[0], true);
            }
        }
        if (!is_array($decoded)) {
            return $this->heuristicFallback($rawText);
        }

        $items = $decoded['items'] ?? [];
        if (!is_array($items)) {
            return $this->heuristicFallback($rawText);
        }

        $normalized = [];
        foreach ($items as $item) {
            $name = trim((string) ($item['drug_name'] ?? $item['medicine_name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $normalized[] = [
                'drug_name' => $name,
                'medicine_name' => $name,
                'dosage' => trim((string) ($item['dosage'] ?? '')),
                'duration' => trim((string) ($item['duration'] ?? '')),
                'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
            ];
        }

        return ['items' => $normalized];
    }

    private function extractTextFromImage(?string $imagePath): string
    {
        if (!$imagePath || !is_file($imagePath)) {
            return '';
        }

        $binary = $this->resolveTesseractBinary();
        if ($binary === '') {
            return '';
        }

        try {
            $process = new Process([$binary, $imagePath, 'stdout', '-l', 'eng']);
            $process->setTimeout(20);
            $process->run();

            if (!$process->isSuccessful()) {
                return '';
            }

            return trim((string) $process->getOutput());
        } catch (\Throwable $_e) {
            return '';
        }
    }

    private function resolveTesseractBinary(): string
    {
        $configured = trim((string) config('services.ai_pharmacy.tesseract_binary', ''));
        if ($configured !== '' && is_file($configured)) {
            return $configured;
        }

        $windowsDefaults = [
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
        ];

        foreach ($windowsDefaults as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        // If available in PATH (Linux/macOS/Windows PATH)
        return 'tesseract';
    }

    private function heuristicFallback(string $rawText): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $rawText) ?: [];
        $items = [];

        foreach ($lines as $line) {
            $clean = trim($line);
            if ($clean === '') {
                continue;
            }

            preg_match('/(\d+)\s*(tab|capsule|cap|ml|pcs|piece|tablet|bottle)?/i', $clean, $qtyMatch);
            $quantity = isset($qtyMatch[1]) ? max(1, (int) $qtyMatch[1]) : 1;

            preg_match('/\b(\d+\s?(mg|g|mcg|ml))\b/i', $clean, $dosageMatch);
            $dosage = isset($dosageMatch[1]) ? trim($dosageMatch[1]) : '';

            preg_match('/\b(\d+\s?(day|days|week|weeks|month|months))\b/i', $clean, $durationMatch);
            $duration = isset($durationMatch[1]) ? trim($durationMatch[1]) : '';

            $name = preg_replace('/\b\d+\s?(mg|g|mcg|ml)\b/i', '', $clean);
            $name = preg_replace('/\b(OD|BID|TDS|QID)\b/i', '', (string) $name);
            $name = preg_replace('/\bfor\s+\d+\s?(day|days|week|weeks|month|months)\b/i', '', (string) $name);
            $name = trim((string) $name, " -,:\t");

            $items[] = [
                'drug_name' => $name !== '' ? $name : $clean,
                'medicine_name' => $name !== '' ? $name : $clean,
                'dosage' => $dosage,
                'duration' => $duration,
                'quantity' => $quantity,
            ];
        }

        return ['items' => $items];
    }
}
