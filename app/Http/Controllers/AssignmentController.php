<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Format;
use App\Models\Hr;
use App\Models\AssignmentStatus;
use App\Models\Batch;
use App\Models\AssignmentForm;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\ActivityAssignment;
use App\Models\Mobilization;

class AssignmentController extends Controller
{ 

public function index(Request $request)
{
    $query = Assignment::with(['forms','mobilizations','batches'])
        ->withCount('mobilizations')
        ->withSum('batches as total_build', 'assignment_batch.build')
        ->addSelect([
            'billed_qty' => DB::table('invoice_assignment_items')
                ->join('invoices','invoices.id','=','invoice_assignment_items.invoice_id')
                ->whereColumn('invoice_assignment_items.assignment_id','assignments.id')
                ->selectRaw('COALESCE(SUM(invoice_assignment_items.quantity),0)')
        ]);

    // Date filters
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('date', [
            $request->from_date,
            $request->to_date
        ]);
    } else {
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }
    }

    if ($request->filled('deadline_from')) {
        $query->whereDate('deadline_date', '>=', $request->deadline_from);
    }

   if ($request->filled('status')) {
    $status = is_array($request->status) ? $request->status : [$request->status];
    $query->whereIn('status', $status);
}
    if ($request->filled('state')) {
        $query->where(function ($q) use ($request) {
            $q->where('state', $request->state)
              ->orWhere('state', 'LIKE', '%' . $request->state . '%');
        });
    }

    if ($request->filled('district')) {
        $query->where('district', $request->district);
    }

    // Billing filter
    if ($request->billing_status === 'not_billed') {
        $query->havingRaw('COALESCE(billed_qty,0) = 0');
    }

    // In Batch Filter
    if ($request->in_batch === '0') {
        $query->havingRaw('COALESCE(total_build,0) = 0');
    }

    $assignments = $query
        ->orderBy('date','desc')
        ->get();

    return view('assignments.index', compact('assignments'));
}

   
public function create()
{
    $hrs = Hr::latest()->get();
    $batches = Batch::latest()->get();
    $formats = Format::latest()->get();

    return view('assignments.create', compact('hrs', 'batches', 'formats'));
}

   
public function store(Request $request)
{
    $request->validate([
        'assignment_name' => 'required|string|max:255|unique:assignments,assignment_name',
        'date'            => 'required|date',
        'deadline'        => 'required|date|after_or_equal:date',
        'format_id'       => 'required|exists:formats,id',
        'requirement'     => 'required|integer|min:1',
        'state'           => 'required|string|max:255',
        'district'        => 'required|string|max:255',
        'location'        => 'required|string|max:255',
        'hr_id'           => 'nullable|exists:hrs,id',

        // ✅ Store Manager Validation
        'sm_name'   => 'nullable|string|max:255',
        'sm_mobile' => 'nullable|string|max:15',
        'sm_email'  => 'nullable|email|max:255',
        'batch_type' => 'nullable|string|max:255',
        'store_code' => 'nullable|string|max:255',
        'sourcing_machine' => 'nullable|string|max:255',
        'business' => 'nullable|string|max:255',
        'region' => 'nullable|string|max:255',
        'position_name' => 'nullable|string|max:255',
        'monthly_ctc' => 'nullable|numeric|min:0',
        'level' => 'nullable|string|max:255',
        'ft_pt' => 'nullable|in:FT,PT',
        'minimum_education_qualification' => 'nullable|string|max:255',
        'work_experience' => 'nullable|string|max:255',

        'batches'         => 'nullable|array',
        'batches.*'       => 'exists:batches,id',
    ]);

    DB::transaction(function () use ($request) {

        $assignment = Assignment::create([
            'assignment_name' => $request->assignment_name,
            'date'            => $request->date,
            'deadline_date'   => $request->deadline,
            'format_id'       => $request->format_id,
            'requirement'     => $request->requirement,
            'state'           => $request->state,
            'district'        => $request->district,
            'location'        => $request->location,
            'hr_id'           => $request->hr_id,

            // ✅ Store Manager Save
            'sm_name'   => $request->sm_name,
            'sm_mobile' => $request->sm_mobile,
            'sm_email'  => $request->sm_email,

            'batch_type' => $request->batch_type,
            'store_code' => $request->store_code,
            'sourcing_machine' => $request->sourcing_machine,
            'business' => $request->business,
            'region' => $request->region,
            'position_name' => $request->position_name,
            'monthly_ctc' => $request->monthly_ctc,
            'level' => $request->level,
            'ft_pt' => $request->ft_pt,
            'minimum_education_qualification' => $request->minimum_education_qualification,
            'work_experience' => $request->work_experience,
        ]);

        if ($request->filled('batches')) {
            $assignment->batches()->sync($request->batches);
        }
    });

    return redirect()->route('assignments.index')
        ->with('success', 'Assignment added successfully!');
}


   
public function edit(Assignment $assignment)
{
    $assignment->load(['batches', 'hr']);

    $hrs     = Hr::latest()->get();
    $batches = Batch::latest()->get();
    $formats = Format::latest()->get();

    return view('assignments.edit', compact(
        'assignment',
        'hrs',
        'batches',
        'formats'
    ));
}


public function update(Request $request, Assignment $assignment)
{
    $request->validate([
        'assignment_name' => 'required|string|max:255|unique:assignments,assignment_name,' . $assignment->id,
        'date'            => 'required|date',
        'deadline'        => 'required|date|after_or_equal:date',
        'format_id'       => 'required|exists:formats,id',
        'requirement'     => 'required|integer|min:1',
        'state'           => 'required|string',
        'district'        => 'required|string',
        'location'        => 'required|string',
        'hr_id'           => 'nullable|exists:hrs,id',

       
        'sm_name'   => 'nullable|string|max:255',
        'sm_mobile' => 'nullable|string|max:15',
        'sm_email'  => 'nullable|email|max:255',

        'batches'         => 'nullable|array',
        'batches.*'       => 'exists:batches,id',

        'batch_type' => 'nullable|string|max:255',
        'store_code' => 'nullable|string|max:255',
        'sourcing_machine' => 'nullable|string|max:255',
        'business' => 'nullable|string|max:255',
        'region' => 'nullable|string|max:255',
        'position_name' => 'nullable|string|max:255',
        'monthly_ctc' => 'nullable|numeric|min:0',
        'level' => 'nullable|string|max:255',
        'ft_pt' => 'nullable|in:FT,PT',
        'minimum_education_qualification' => 'nullable|string|max:255',
        'work_experience' => 'nullable|string|max:255',
    ]);

    DB::transaction(function () use ($request, $assignment) {

        $assignment->update([
            'assignment_name' => $request->assignment_name,
            'date'            => $request->date,
            'deadline_date'   => $request->deadline,
            'format_id'       => $request->format_id,
            'requirement'     => $request->requirement,
            'state'           => $request->state,
            'district'        => $request->district,
            'location'        => $request->location,
            'hr_id'           => $request->hr_id,
            'sm_name'   => $request->sm_name,
            'sm_mobile' => $request->sm_mobile,
            'sm_email'  => $request->sm_email,
            'batch_type' => $request->batch_type,
            'store_code' => $request->store_code,
            'sourcing_machine' => $request->sourcing_machine,
            'business' => $request->business,
            'region' => $request->region,
            'position_name' => $request->position_name,
            'monthly_ctc' => $request->monthly_ctc,
            'level' => $request->level,
            'ft_pt' => $request->ft_pt,
            'minimum_education_qualification' => $request->minimum_education_qualification,
            'work_experience' => $request->work_experience,
        ]);

        if ($request->has('batches')) {
    $assignment->batches()->sync($request->batches);
}
    });

    return redirect()->route('assignments.index')
        ->with('success', 'Assignment updated successfully!');
}


   
public function show($id)
{
    $assignment = Assignment::with([
        'format',
        'statusHistory',
        'forms',
        'hr',
        'batches' => function ($q) {

            
            $q->withPivot('build')      
              ->withCount([
                  'students as students_count'
              ])
              ->with([
                  'invoice.assignmentItems'
              ]);
        }
    ])->findOrFail($id);

    $allBatches = Batch::latest()->get();

    return view('assignments.show', compact('assignment', 'allBatches'));
}

  
    public function attachBatch(Request $request, Assignment $assignment)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id'
        ]);

        $assignment->batches()->syncWithoutDetaching([$request->batch_id]);

        return back()->with('success', 'Batch attached successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'status_date' => 'nullable|date',
            'remark' => 'nullable|string',
        ]);

        $assignment = Assignment::findOrFail($id);

        $assignment->update([
            'status' => $request->status,
            'status_date' => $request->status_date,
            'remark' => $request->remark,
        ]);

        AssignmentStatus::create([
            'assignment_id' => $assignment->id,
            'status' => $request->status,
            'status_date' => $request->status_date,
            'remark' => $request->remark,
        ]);

        return back()->with('success', 'Status updated successfully');
    }

    public function storeForms(Request $request, Assignment $assignment)
{
    foreach ($request->forms as $form) {

        $from = !empty($form['valid_from'])
            ? Carbon::parse($form['valid_from'])->format('Y-m-d')
            : null;

        $to = !empty($form['valid_to'])
            ? Carbon::parse($form['valid_to'])->format('Y-m-d')
            : null;

        AssignmentForm::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'form_id' => $form['form_id'],
            ],
            [
                'form_name'  => $form['form_name'] ?? null,
                'location'   => $form['location'] ?? null,
                'status'     => ($form['status'] == 1) ? 'Active' : 'Inactive',
                'valid_from' => $from,
                'valid_to'   => $to,
                'link'       => $form['link'] ?? null,
            ]
        );
    }

    return response()->json([
        'success' => true
    ]);
}

public function destroy($id)
{
    $assignment = Assignment::findOrFail($id);

    // ❗ Prevent delete if batches exist
    if ($assignment->batches()->exists()) {
        return redirect()->route('assignments.index')
            ->with('error', 'Assignment cannot be deleted because batches are attached.');
    }

    $assignment->delete();

    return redirect()->route('assignments.index')
        ->with('success', 'Assignment deleted successfully.');
}

public function progress(Request $request)
{
    $assignments = Assignment::orderBy('assignment_name')->get();

    $selectedAssignment = null;
    $activityAssignments = collect();

    if ($request->assignment_id) {
        $selectedAssignment = Assignment::find($request->assignment_id);

        $activityAssignments = ActivityAssignment::with([
            'phase',
            'activity',
            'teamMember',
            'assignment'
        ])
        ->where('assignment_id', $request->assignment_id)
        ->orderBy('assigned_at')
        ->get();
    }

    return view('assignments.progress', compact(
        'assignments',
        'selectedAssignment',
        'activityAssignments'
    ));
}
public function getBatches(Assignment $assignment)
{
    return response()->json([
        'batches' => Batch::all(),
        'assigned' => $assignment->batches()->pluck('batches.id')
    ]);
}

public function saveBatches(Request $request, Assignment $assignment)
{
    $assignment->batches()->sync($request->batch_ids ?? []);

    return response()->json([
        'success' => true
    ]);
}

public function assignBatches(Request $request, $id)
{
    $assignment = Assignment::findOrFail($id);

    $assignment->batches()->sync($request->batch_ids ?? []);

    return response()->json([
        'success' => true,
        'message' => 'Batches updated successfully'
    ]);
}
public function getHrs()
{
    return Hr::select('id', 'name', 'mobile', 'email', 'state')
        ->latest()
        ->get();
}
public function getMobilizations($id)
{
    $assignment = Assignment::with('mobilizations')->findOrFail($id);

    return response()->json($assignment->mobilizations);
}


public function assignBulkCandidates(Request $request, $id)
{
    $request->validate([
        'candidate_ids' => 'required|array',
        'batch_id'      => 'required|exists:batches,id'
    ]);

    DB::table('batch_assignment_students')->insert(
        collect($request->candidate_ids)->map(function ($cid) use ($id, $request) {
            return [
                'assignment_id' => $id,
                'batch_id'      => $request->batch_id, // ✅
                'student_id'    => $cid,               // ✅
                'created_at'    => now(),
                'updated_at'    => now()
            ];
        })->toArray()
    );

    return response()->json([
        'success' => true
    ]);
}
public function registrations(Request $request, $assignmentId)
{
    $assignment = Assignment::with('batches')->findOrFail($assignmentId);

    // candidates (mobilizations) with filters
    $query = Mobilization::join('assignment_students', 'mobilizations.id', '=', 'assignment_students.mobilization_id')
        ->where('assignment_students.assignment_id', $assignmentId)
        ->select(
            'mobilizations.*',
            'assignment_students.samarth_done',
            'assignment_students.uan_done',
            'assignment_students.documents_done',
            'assignment_students.offer_letter_done',
            'assignment_students.placement_company',
            'assignment_students.date_of_placement'
        );

    // Apply filters
    if ($request->filled('mobile')) {
        $query->where('mobilizations.mobile', 'like', '%' . $request->mobile . '%');
    }

    if ($request->filled('samarth_done')) {
        $query->where('assignment_students.samarth_done', $request->samarth_done);
    }

    if ($request->filled('uan_done')) {
        $query->where('assignment_students.uan_done', $request->uan_done);
    }

    if ($request->filled('documents_done')) {
        $query->where('assignment_students.documents_done', $request->documents_done);
    }

    if ($request->filled('offer_letter_done')) {
        $query->where('assignment_students.offer_letter_done', $request->offer_letter_done);
    }

    if ($request->filled('placement_company')) {
        $query->where('assignment_students.placement_company', 'like', '%' . $request->placement_company . '%');
    }

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('assignment_students.date_of_placement', [
            $request->from_date,
            $request->to_date
        ]);
    } elseif ($request->filled('from_date')) {
        $query->whereDate('assignment_students.date_of_placement', '>=', $request->from_date);
    } elseif ($request->filled('to_date')) {
        $query->whereDate('assignment_students.date_of_placement', '<=', $request->to_date);
    }

    $candidates = $query->with('assignmentBatches.batch')->get();

    return view('assignments.registrations', compact(
        'assignment',
        'candidates'
    ));
}
public function assignCandidates(Request $request, $assignmentId)
{
    $request->validate([
        'candidate_ids' => 'required|array',
        'batch_id' => 'required'
    ]);

    foreach ($request->candidate_ids as $candidateId) {
        DB::table('batch_candidates')->insert([
            'batch_id' => $request->batch_id,
            'candidate_id' => $candidateId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    return back()->with('success', 'Candidates assigned successfully');
}
public function addMobilizations($id)
{
    $assignment = Assignment::findOrFail($id);

    // get mobilization IDs already assigned
    $assignedIds = $assignment->mobilizations()->pluck('mobilizations.id');

    // fetch only those not assigned yet
    $mobilizations = Mobilization::whereNotIn('id', $assignedIds)
                        ->latest()
                        ->get();

    return view('assignments.add-mobilizations', compact(
        'assignment',
        'mobilizations'
    ));
}
public function storeMobilizations(Request $request, $id)
{
    $request->validate([
        'mobilization_ids' => ['required', 'array'],
        'mobilization_ids.*' => ['exists:mobilizations,id']
    ]);

    $assignment = Assignment::findOrFail($id);

    // remove duplicates & null values (safety)
    $mobilizationIds = collect($request->mobilization_ids)
                            ->filter()
                            ->unique()
                            ->values()
                            ->toArray();

    // attach without removing existing ones
    $assignment->mobilizations()
        ->syncWithoutDetaching($mobilizationIds);

    return redirect()
        ->route('assignments.registrations', $id)
        ->with('success', 'Candidates added successfully.');
}
public function removeMobilization($assignmentId, $mobilizationId)
{
    $assignment = Assignment::findOrFail($assignmentId);

    // Remove candidate from assignment (pivot table)
    $assignment->mobilizations()->detach($mobilizationId);

    return redirect()
        ->back()
        ->with('success', 'Candidate removed from assignment successfully');
}

public function remaining(Request $request, $id)
{
    $assignment = Assignment::findOrFail($id);

    $requirement = $assignment->requirement ?? 0;

    // ✅ Get total built manually
    $built = DB::table('assignment_batch')
        ->where('assignment_id', $id)
        ->sum('build');

    $currentBuild = 0;

    if ($request->batch_id) {
        $currentBuild = DB::table('assignment_batch')
            ->where('assignment_id', $id)
            ->where('batch_id', $request->batch_id)
            ->value('build') ?? 0;
    }

    // ✅ Correct remaining calculation
    $remaining = $requirement - ($built - $currentBuild);

    return response()->json([
        'requirement' => $requirement,
        'built' => $built,
        'current_build' => $currentBuild,
        'remaining' => max($remaining, 0),
    ]);
}
}