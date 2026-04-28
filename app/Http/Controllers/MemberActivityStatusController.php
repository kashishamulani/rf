<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberActivityStatus;
use App\Models\TeamMember;
use App\Models\ActivityAssignment;
use Carbon\Carbon;

class MemberActivityStatusController extends Controller
{
    /**
     * Store or Update Activity Status
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_assignment_id' => 'required|exists:activity_assignments,id',
            'status' => 'required|string',
            'remark' => 'nullable|string'
        ]);

        MemberActivityStatus::updateOrCreate(
            [
                'activity_assignment_id' => $request->activity_assignment_id
            ],
            [
                'status' => $request->status,
                'remark' => $request->remark,
                'updated_by' => auth()->id()
            ]
        );

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Reporting Log Page
     */
    public function reportingLog(Request $request)
    {
        $members = TeamMember::orderBy('name')->get();

        $assignments = ActivityAssignment::with([
                'teamMember',
                'assignment',
                'activity',
                'status'
            ])
            ->when($request->member_id, function ($q) use ($request) {
                $q->where('team_member_id', $request->member_id);
            })
            ->when($request->from_date, function ($q) use ($request) {
                $q->whereDate('assigned_at', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($q) use ($request) {
                $q->whereDate('assigned_at', '<=', $request->to_date);
            })
            ->latest()
            ->get();

        return view('reporting-log.index', compact('assignments', 'members'));
    }

    /**
     * Members List API (Optional)
     */
    public function membersList()
    {
        return TeamMember::select('id','name')->get();
    }
}