<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        return Report::latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required',
            'data' => 'required',
            'generated_at' => 'nullable',
        ]);
        $report = Report::create($data);
        return response()->json($report, 201);
    }

    public function show(Report $report)
    {
        return $report;
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required',
            'data' => 'required',
            'generated_at' => 'nullable',
        ]);

        $report->update($data);

        return response()->json($report);
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
