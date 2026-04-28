<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Phase;
use Illuminate\Http\Request;
use App\Models\ActivityAssignment;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities grouped by phases.
     */
    public function index()
    {
        // Fetch phases with activities
        // Activities are ordered by creation date
        // Phases are ordered by phase_order and sequence
        $phases = Phase::with(['activities' => function ($query) {
            $query->withCount('assignments')
                  ->orderBy('created_at', 'asc');
        }])
        ->orderBy('phase_order', 'asc')
        ->orderBy('sequence', 'asc') // Ensure 'sequence' exists in DB
        ->get();

        return view('activities.index', compact('phases'));
    }

    /**
     * Show form to create a new activity.
     */
    public function create()
    {
        $phases = Phase::orderBy('phase_order', 'asc')
                       ->orderBy('sequence', 'asc')
                       ->get();

        return view('activities.create', compact('phases'));
    }

    /**
     * Store a new activity.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:activities,name',
            'phase_id'    => 'required|exists:phases,id',
            'description' => 'nullable|string|max:1000',
        ]);

        Activity::create([
            'name'        => $request->name,
            'phase_id'    => $request->phase_id,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity created successfully');
    }

    /**
     * Show form to edit an activity.
     */
    public function edit(Activity $activity)
    {
        $phases = Phase::orderBy('phase_order', 'asc')
                       ->orderBy('sequence', 'asc')
                       ->get();

        return view('activities.edit', compact('activity', 'phases'));
    }

    /**
     * Update an activity.
     */
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:activities,name,' . $activity->id,
            'phase_id'    => 'required|exists:phases,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $activity->update([
            'name'        => $request->name,
            'phase_id'    => $request->phase_id,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity updated successfully');
    }

    /**
     * Delete an activity if not assigned.
     */


public function destroy(Activity $activity)
{
    // CHECK IF USED IN ASSIGNMENT
    if ($activity->activityAssignments()->exists()) {

        return redirect()
            ->route('activities.index')
            ->with('error', 'This activity is already assigned and cannot be deleted.');
    }

    $activity->delete();

    return redirect()
        ->route('activities.index')
        ->with('success', 'Activity deleted successfully.');
}


    /**
     * Show a single activity.
     */
    public function show(Activity $activity)
    {
        $activity->load('phase');

        return view('activities.show', compact('activity'));
    }
}
