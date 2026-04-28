<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LinkAssignment;
use App\Models\Assignment;
use App\Models\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\FormResponse;
use App\Models\FormResponseValue;

class LinkAssignmentController extends Controller
{
    public function index()
    {
        $links = LinkAssignment::with(['assignment','form'])->latest()->get();
        
        // Add count of students who filled the form for each assignment
        foreach ($links as $link) {
            $link->form_responses_count = FormResponse::where('form_id', $link->form_id)
                ->whereIn('mobilization_id', function ($query) use ($link) {
                    $query->select('mobilization_id')
                        ->from('assignment_students')
                        ->where('assignment_id', $link->assignment_id);
                })
                ->count();
        }
        
        return view('link_assignments.index', compact('links'));
    }

    public function show($id)
    {
        $link = LinkAssignment::with(['assignment', 'form'])->findOrFail($id);

        $mobilizationIds = DB::table('assignment_students')
            ->where('assignment_id', $link->assignment_id)
            ->pluck('mobilization_id')
            ->toArray();

        $responses = FormResponse::with('mobilization')
            ->where('form_id', $link->form_id)
            ->whereIn('mobilization_id', $mobilizationIds)
            ->orderBy('created_at', 'desc')
            ->get();

        $responseIds = $responses->pluck('id')->toArray();

        $fileValues = FormResponseValue::with(['field', 'response.mobilization'])
            ->whereIn('response_id', $responseIds)
            ->whereNotNull('file_url')
            ->get();

        $totalFileUploads = $fileValues->count();
        $totalFileSize = $fileValues->sum('file_size');

        return view('link_assignments.show', compact(
            'link',
            'responses',
            'fileValues',
            'totalFileUploads',
            'totalFileSize'
        ));
    }

    public function create()
    {
        $assignments = Assignment::all();
        $forms = Form::all();

        return view('link_assignments.create', compact('assignments','forms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'form_id' => 'required|exists:forms,id',
        ]);

        // prevent duplicate
        $exists = LinkAssignment::where('assignment_id', $request->assignment_id)
            ->where('form_id', $request->form_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Already linked!');
        }

        LinkAssignment::create($request->all());

        return redirect()->route('link-assignments.index')->with('success', 'Linked successfully!');
    }


    public function edit($id)
{
    $link = LinkAssignment::findOrFail($id);
    $assignments = Assignment::all();
    $forms = Form::all();

    return view('link_assignments.edit', compact('link','assignments','forms'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'assignment_id' => [
            'required',
            Rule::unique('link_assignments')
                ->where(function ($query) use ($request) {
                    return $query->where('form_id', $request->form_id);
                })
                ->ignore($id),
        ],
        'form_id' => 'required',
    ], [
        'assignment_id.unique' => 'This assignment is already linked with the selected form.'
    ]);

    $link = LinkAssignment::findOrFail($id);

    $link->update([
        'assignment_id' => $request->assignment_id,
        'form_id' => $request->form_id,
    ]);

    return redirect()->route('link-assignments.index')
        ->with('success', 'Updated successfully');
}
    public function destroy($id)
    {
        LinkAssignment::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully!');
    }
}