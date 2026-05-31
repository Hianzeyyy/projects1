<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AIProcess;

class AIProcessController extends Controller
{
    public function index()
    {
        return AIProcess::latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'form_type' => 'required',
            'input_data' => 'required',
            'status' => 'nullable',
        ]);
        $ai = AIProcess::create($data);
        // Here you can trigger AI logic (sync/async)
        return response()->json($ai, 201);
    }

    public function show(AIProcess $aiprocess)
    {
        return $aiprocess;
    }

    public function update(Request $request, AIProcess $aiprocess)
    {
        $aiprocess->update($request->all());
        return response()->json($aiprocess);
    }

    public function destroy(AIProcess $aiprocess)
    {
        $aiprocess->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
