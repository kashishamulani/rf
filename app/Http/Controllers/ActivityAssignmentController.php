<?php

namespace App\Http\Controllers;

use App\Models\ActivityAssignment;
use App\Models\TeamMember;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityAssignmentController extends Controller
{
    /**
     * MAIN INDEX PAGE - Shows list of all assignments
     */
   /**
 * MAIN INDEX PAGE - Shows list of only those assignments that have assignments
 */
public function index()
{
    $assignments = Assignment::withCount([
            'activityAssignments as total_assignments',
            'activityAssignments as phase_count' => function($query) {
                $query->where('type', 'phase');
            },
            'activityAssignments as activity_count' => function($query) {
                $query->where('type', 'activity');
            },
            'activityAssignments as member_count' => function($query) {
                $query->select(DB::raw('COUNT(DISTINCT team_member_id)'));
            }
        ])
        ->whereHas('activityAssignments') // Only assignments with at least one activityAssignment
        ->latest()
        ->get();

    return view('activity-assignments.index', compact('assignments'));
}
public function create()
{
    // Get IDs already assigned
    $assignedIds = ActivityAssignment::pluck('assignment_id')->unique();

    // Only show assignments not yet assigned
    $assignments = Assignment::whereNotIn('id', $assignedIds)->get();

    $phases = Phase::with('activities')->get();
    $members = TeamMember::where('status', 1)->get();

    return view('activity-assignments.create', compact(
        'assignments',
        'phases',
        'members'
    ));
}

    /**
     * SHOW PAGE - Shows detailed view of a specific assignment
     * with all phases and activities and their assigned members
     */
   

public function show($id)
{
    $assignment = Assignment::with([
        'activityAssignments' => function($query) {
            $query->with(['phase', 'activity', 'teamMember'])
                  ->orderBy('phase_id')
                  ->orderBy('type', 'desc');
        }
    ])->findOrFail($id);

    // Group assignments by phase
    $groupedAssignments = [];
    
    foreach ($assignment->activityAssignments as $item) {
        $phaseId = $item->phase_id;
        
        if (!isset($groupedAssignments[$phaseId])) {
            $groupedAssignments[$phaseId] = [
                'phase' => $item->phase,
                'phase_assignment' => null, // Single row for whole phase
                'activities' => [] // Individual activities
            ];
        }
        
        if ($item->type == 'phase') {
            // If whole phase is assigned, store it (only one row for the phase)
            // If multiple members assigned to same phase, we'll handle in view
            if (!$groupedAssignments[$phaseId]['phase_assignment']) {
                $groupedAssignments[$phaseId]['phase_assignment'] = [];
            }
            $groupedAssignments[$phaseId]['phase_assignment'][] = $item;
        } else {
            // Individual activities
            $groupedAssignments[$phaseId]['activities'][] = $item;
        }
    }

    return view('activity-assignments.show', compact('assignment', 'groupedAssignments'));
}
    /**
     * STORE
     */
    public function store(Request $request)
    {

    $exists = ActivityAssignment::where('assignment_id', $request->assignment_id)->exists();

    if ($exists) {
        return redirect()
            ->route('activity-assignments.edit', $request->assignment_id)
            ->with('error', 'This assignment is already created. Please edit it instead.');
    }
        DB::beginTransaction();
        
        try {
            $assignmentId = $request->assignment_id;

            // ---------------- PHASE ----------------
            if ($request->has('phase_member')) {
                foreach ($request->phase_member as $phaseId => $memberId) {
                    if (empty($memberId)) continue;

                    ActivityAssignment::create([
                        'assignment_id' => $assignmentId,
                        'phase_id' => $phaseId,
                        'activity_id' => null,
                        'team_member_id' => $memberId,
                        'start_date' => $request->phase_start[$phaseId] ?? null,
                        'days' => $request->phase_days[$phaseId] ?? null,
                        'target_date' => $request->phase_target[$phaseId] ?? null,
                        'type' => 'phase'
                    ]);
                }
            }

            // ---------------- ACTIVITY ----------------
            if ($request->has('activity_member')) {
                foreach ($request->activity_member as $activityId => $memberId) {
                    if (empty($memberId)) continue;

                    $activity = Activity::find($activityId);
                    if (!$activity) continue;

                    ActivityAssignment::create([
                        'assignment_id' => $assignmentId,
                        'phase_id' => $activity->phase_id,
                        'activity_id' => $activityId,
                        'team_member_id' => $memberId,
                        'start_date' => $request->activity_start[$activityId] ?? null,
                        'days' => $request->activity_days[$activityId] ?? null,
                        'target_date' => $request->activity_target[$activityId] ?? null,
                        'type' => 'activity'
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('activity-assignments.index')
                ->with('success', 'Assignments saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error saving assignments: ' . $e->getMessage());
        }
    }

    /**
     * EDIT PAGE
     */
/**
 * EDIT PAGE
 */
public function edit($id)
{
    $assignment = Assignment::findOrFail($id);

    $phases = Phase::with('activities')->get();
    $members = TeamMember::where('status', 1)->get();

    // Fetch saved assignments - group by phase/activity to handle multiple members
    $phaseAssignments = ActivityAssignment::where('assignment_id', $id)
        ->where('type', 'phase')
        ->get()
        ->groupBy('phase_id');

    $activityAssignments = ActivityAssignment::where('assignment_id', $id)
        ->where('type', 'activity')
        ->get()
        ->groupBy('activity_id');

    return view('activity-assignments.edit', compact(
        'assignment',
        'phases',
        'members',
        'phaseAssignments',
        'activityAssignments'
    ));
}

/**
 * UPDATE
 */
public function update(Request $request, $id)
{
    $assignment = Assignment::findOrFail($id);

    DB::beginTransaction();

    try {
        // ----------------------------------------
        // DELETE OLD RECORDS (FOR THIS ASSIGNMENT)
        // ----------------------------------------
        ActivityAssignment::where('assignment_id', $assignment->id)->delete();

        $assignmentId = $assignment->id;

        // ===================================================
        // PHASE ASSIGNMENTS (Multiple members per phase)
        // ===================================================
        if ($request->has('phase_member') && is_array($request->phase_member)) {
            foreach ($request->phase_member as $phaseId => $memberValue) {
                
                // Handle both single member and multiple members
                $memberIds = [];
                
                if (is_array($memberValue)) {
                    // Multiple members selected (checkboxes)
                    $memberIds = array_filter($memberValue);
                } else {
                    // Single member selected (dropdown)
                    if (!empty($memberValue)) {
                        $memberIds = [$memberValue];
                    }
                }
                
                foreach ($memberIds as $memberId) {
                    if (empty($memberId)) continue;

                    ActivityAssignment::create([
                        'assignment_id' => $assignmentId,
                        'phase_id' => $phaseId,
                        'activity_id' => null,
                        'team_member_id' => $memberId,
                        'start_date' => $request->phase_start[$phaseId] ?? null,
                        'days' => $request->phase_days[$phaseId] ?? null,
                        'target_date' => $request->phase_target[$phaseId] ?? null,
                        'type' => 'phase'
                    ]);
                }
            }
        }

        // ===================================================
        // ACTIVITY ASSIGNMENTS (Multiple members per activity)
        // Only create activity if phase is NOT assigned
        // ===================================================
        if ($request->has('activity_member') && is_array($request->activity_member)) {
            foreach ($request->activity_member as $activityId => $memberValue) {
                
                // Skip if phase is assigned for this activity's phase
                $activity = Activity::find($activityId);
                if (!$activity) continue;
                
                // Check if phase is assigned (whole phase)
                $phaseAssigned = false;
                if ($request->has('phase_member') && is_array($request->phase_member)) {
                    foreach ($request->phase_member as $phaseId => $phaseMemberValue) {
                        if ($phaseId == $activity->phase_id) {
                            if (is_array($phaseMemberValue)) {
                                $phaseAssigned = !empty(array_filter($phaseMemberValue));
                            } else {
                                $phaseAssigned = !empty($phaseMemberValue);
                            }
                            break;
                        }
                    }
                }
                
                // Skip activity if its phase is assigned
                if ($phaseAssigned) continue;
                
                // Handle both single member and multiple members
                $memberIds = [];
                
                if (is_array($memberValue)) {
                    // Multiple members selected (checkboxes)
                    $memberIds = array_filter($memberValue);
                } else {
                    // Single member selected (dropdown)
                    if (!empty($memberValue)) {
                        $memberIds = [$memberValue];
                    }
                }
                
                foreach ($memberIds as $memberId) {
                    if (empty($memberId)) continue;

                    ActivityAssignment::create([
                        'assignment_id' => $assignmentId,
                        'phase_id' => $activity->phase_id,
                        'activity_id' => $activityId,
                        'team_member_id' => $memberId,
                        'start_date' => $request->activity_start[$activityId] ?? null,
                        'days' => $request->activity_days[$activityId] ?? null,
                        'target_date' => $request->activity_target[$activityId] ?? null,
                        'type' => 'activity'
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()
            ->route('activity-assignments.index')
            ->with('success', 'Assignments updated successfully!');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->with('error', 'Error updating assignments: ' . $e->getMessage());
    }
}

    /**
     * DELETE SINGLE ACTIVITY ASSIGNMENT
     */
public function destroy($id)
{
    try {
        DB::beginTransaction();

        // delete all activity assignments under this assignment
        ActivityAssignment::where('assignment_id', $id)->delete();

        DB::commit();

        return redirect()
            ->route('activity-assignments.index')
            ->with('success', 'Assignment deleted successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', 'Delete failed: ' . $e->getMessage());
    }
}
}