<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Models\ReportEvidence;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create()
    {
        return view('report.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'file' => 'nullable|file|max:10240|mimes:jpg,png,pdf,mp3,mp4,webm',
        ]);

        // Create the report (anonymous if not logged in)
        $userId = Auth::check() ? Auth::id() : null;
        $report = \App\Models\Report::create([
            'user_id' => $userId,
            'type' => 'abuse',
            'description' => $request->description,
            'status' => 'unread',
        ]);

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('report_evidence', ['disk' => 'local', 'visibility' => 'private']);
            ReportEvidence::create([
                'report_id' => $report->id,
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'uploaded_by' => $userId,
            ]);
        }

        return back()->with('success', 'Report submitted successfully.');
    }
}
