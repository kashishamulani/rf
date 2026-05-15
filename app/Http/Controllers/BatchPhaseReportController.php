<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Phase;
use App\Models\BatchPhaseReport;

class BatchPhaseReportController extends Controller
{
    public function create()
    {
        $batches = Batch::orderBy('batch_code')->get();

        $phases = Phase::orderBy('phase_order')
            ->orderBy('sequence')
            ->get();

        return view('batch_phase_report.create', compact(
            'batches',
            'phases'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'phase_data' => 'required|array'
        ]);

        foreach ($request->phase_data as $phaseId => $data) {

            BatchPhaseReport::updateOrCreate(
                [
                    'batch_id' => $request->batch_id,
                    'phase_id' => $phaseId
                ],
                [
                    'status' => $data['status'] ?? null,
                    'start_date' => $data['start_date'] ?? null,
                    'expected_end_date' => $data['expected_end_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('batch-phase-report.index')
            ->with('success', 'Report saved successfully');
    }

    public function index()
    {
        $batches = Batch::with([
            'phaseReports.phase'
        ])->get();

        $phases = Phase::orderBy('phase_order')
            ->orderBy('sequence')
            ->get();

        return view('batch_phase_report.index', compact(
            'batches',
            'phases'
        ));
    }
}