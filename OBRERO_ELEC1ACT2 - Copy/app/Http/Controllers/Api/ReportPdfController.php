<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportPdfController extends Controller
{
    public function download($id)
    {
        $report = Report::findOrFail($id);
        $pdf = Pdf::loadView('pdf.report', ['report' => $report]);
        return $pdf->download('report_' . $report->id . '.pdf');
    }
}
