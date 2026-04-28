<?php

namespace App\Http\Controllers;

use App\Models\ActivityAssignment;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamTaskReportController extends Controller
{
    public function index(Request $request)
    {
        $members = TeamMember::all();
        $reportData = collect();

        if ($request->member_id) {

            $reportData = ActivityAssignment::with([
                'assignment',
                'phase',
                'activity'
            ])
            ->where('team_member_id', $request->member_id) // ✅ FIX HERE
            ->get()
            ->groupBy('assignment_id')
            ->map(function ($rows) {

                return [
                    'assignment' => optional($rows->first()->assignment)->assignment_name,

                    'phases' => $rows->pluck('phase.phase_name')
                        ->filter()
                        ->unique()
                        ->implode(', '),

                    'activities' => $rows->pluck('activity.name')
                        ->filter()
                        ->unique()
                        ->implode(', '),

                    'status' => $rows->pluck('status')
                        ->filter()
                        ->unique()
                        ->implode(', '),

                    'start' => $rows->min('start_date'),

                    'target' => $rows->max('target_date'),

                    'remark' => optional(
    $rows->firstWhere('remark','!=',null)
)->remark,

                    'id' => $rows->first()->id
                ];
            });
        }

        return view('team-task-report.index', compact('members', 'reportData'));
    }

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string',
        'remark' => 'nullable|string|max:255'
    ]);

    $assignment = ActivityAssignment::findOrFail($id);

    $assignment->update([
        'status' => $request->status,
        'remark' => $request->remark
    ]);

    return redirect()->back()->with('success','Updated Successfully!');
}
}