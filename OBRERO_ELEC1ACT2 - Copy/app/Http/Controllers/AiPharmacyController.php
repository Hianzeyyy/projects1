<?php

namespace App\Http\Controllers;

use App\Models\AIProcess;
use App\Models\Inventory;
use App\Models\Medicine;
use App\Services\AiPharmacyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiPharmacyController extends Controller
{
    public function index()
    {
        $inventory = Inventory::query()
            ->with('medicine:id,name')
            ->select(['id', 'medicine_id', 'quantity'])
            ->orderBy('id')
            ->get()
            ->map(function (Inventory $item) {
                return [
                    'id' => $item->id,
                    'medicine_id' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'medicine_name' => $item->medicine?->name
                        ?? (string) ($item->getAttribute('medicine_name') ?? ('Medicine #' . $item->id)),
                ];
            })
            ->values();

        $medicines = Medicine::query()
            ->select(['id', 'name', 'price'])
            ->orderBy('name')
            ->get()
            ->values();

        return view('ai.prescription-parser', [
            'inventory' => $inventory,
            'medicines' => $medicines,
            'pharmacistName' => optional(auth()->user())->name,
        ]);
    }

    public function parse(Request $request, AiPharmacyService $service)
    {
        $validated = $request->validate([
            'raw_text' => ['nullable', 'string', 'max:5000', 'required_without:prescription_image'],
            'prescription_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'required_without:raw_text'],
        ]);

        $rawText = (string) ($validated['raw_text'] ?? '');
        $imageDataUrl = null;
        $imagePath = null;

        if ($request->hasFile('prescription_image')) {
            $file = $request->file('prescription_image');
            $mime = $file->getMimeType() ?: 'image/jpeg';
            $imagePath = $file->getRealPath();
            $imageDataUrl = 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($file->getRealPath()));
        }

        $result = $service->parsePrescription($rawText, $imageDataUrl, $imagePath);

        try {
            AIProcess::create([
                'user_id' => auth()->id() ?? 1,
                'form_type' => 'prescription_parser',
                'input_data' => $rawText !== '' ? $rawText : '[IMAGE_UPLOAD]',
                'result' => json_encode($result),
                'status' => 'completed',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to persist AI parser process log.', ['error' => $e->getMessage()]);
        }

        return response()->json($result);
    }
}
