<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phase;

class PhaseController extends Controller
{
    public function index()
    {
       $phases = Phase::withCount('activities')
    ->orderBy('phase_order', 'asc')
    ->orderBy('sequence', 'asc')
    ->get();

        return view('phase.index', compact('phases'));
    }

    public function create()
    {
        return view('phase.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phase_name' => 'required|string|max:255',
            'phase_order' => 'required|integer|min:1|max:20',
        ]);

        // Get next sequence number inside selected order
        $maxSequence = Phase::where('phase_order', $request->phase_order)
                            ->max('sequence');

        $nextSequence = $maxSequence ? $maxSequence + 1 : 1;

        Phase::create([
            'phase_name' => $request->phase_name,
            'phase_order' => $request->phase_order,
            'sequence' => $nextSequence
        ]);

        return redirect()->route('phase.index')
                         ->with('success', 'Phase created successfully.');
    }

    public function edit($id)
    {
        $phase = Phase::findOrFail($id);
        return view('phase.edit', compact('phase'));
    }

    public function update(Request $request, $id)
    {
        $phase = Phase::findOrFail($id);

        $request->validate([
            'phase_name' => 'required|string|max:255',
            'phase_order' => 'required|integer|min:1|max:20',
        ]);

        // If order changed, recalculate sequence
        if ($phase->phase_order != $request->phase_order) {

            $maxSequence = Phase::where('phase_order', $request->phase_order)
                                ->max('sequence');

            $phase->sequence = $maxSequence ? $maxSequence + 1 : 1;
        }

        $phase->update([
            'phase_name' => $request->phase_name,
            'phase_order' => $request->phase_order,
            'sequence' => $phase->sequence
        ]);

        return redirect()->route('phase.index')
                         ->with('success', 'Phase updated successfully.');
    }

    public function destroy($id)
    {
        Phase::findOrFail($id)->delete();

        return redirect()->route('phase.index')
                         ->with('success', 'Phase deleted successfully.');
    }
}