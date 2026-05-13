<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Mobilization;
use App\Models\AssignmentStudent;
use App\Models\Progress;

class AssignmentStudentController extends Controller
{
    /**
     * View assignment student data
     */
    public function view($assignment, $student)
    {
        try {
            $assignment = Assignment::findOrFail($assignment);
            $student = Mobilization::findOrFail($student);

            $data = AssignmentStudent::where('assignment_id', $assignment->id)
                    ->where('mobilization_id', $student->id)
                    ->first();

            return view('assignment-students.view', compact('assignment', 'student', 'data'));
        } catch (\Exception $e) {
            \Log::error('Error in AssignmentStudentController@view: ' . $e->getMessage());
            return redirect()->route('assignments.registrations', $assignment->id ?? null)
                    ->with('error', 'Student data not found');
        }
    }

    /**
     * Show form for assignment student data
     */
   
public function form($assignment, $student)
{
    try {
        $assignment = Assignment::findOrFail($assignment);
        $student = Mobilization::findOrFail($student);

        $data = AssignmentStudent::where('assignment_id', $assignment->id)
                ->where('mobilization_id', $student->id)
                ->first();

        $progressList = Progress::all(); // ✅ NEW

        return view('assignment-students.form', compact(
            'assignment',
            'student',
            'data',
            'progressList'
        ));

    } catch (\Exception $e) {
        \Log::error('Error in AssignmentStudentController@form: ' . $e->getMessage());
        return redirect()->route('assignments.registrations', $assignment->id ?? null)
                ->with('error', 'Cannot load form for this student');
    }
}


    /**
     * Store assignment student data
     */
public function store(Request $request)
{
    try {

        $request->validate([
            'assignment_id'    => 'required|exists:assignments,id',
            'mobilization_id'  => 'required|exists:mobilizations,id',
            'remark'           => 'nullable|string|max:50',
        ]);

        // Prevent duplicate entry
        $data = AssignmentStudent::firstOrNew([
            'assignment_id'   => $request->assignment_id,
            'mobilization_id' => $request->mobilization_id,
        ]);

        $data->samarth_done = $request->samarth_done;
        $data->samarth_id   = $request->samarth_id ?? null;

        $data->uan_done   = $request->boolean('uan_done');
        $data->uan_number = $request->uan_number ?? null;

        $data->documents_done    = $request->boolean('documents_done');
        $data->offer_letter_done = $request->boolean('offer_letter_done');

        $data->offer_letter_date = $request->offer_letter_date ?: null;
        $data->ec_date           = $request->ec_date ?: null;
        $data->progress_id       = $request->progress_id ?: null;

        $data->registration_id       = $request->registration_id ?? null;
        $data->registration_password = $request->registration_password ?? null;
        $data->registration_number   = $request->registration_number ?? null;

        $data->ec_number = $request->ec_number ?? null;

        $data->date_of_placement = $request->date_of_placement ?: null;

        $data->placement_company  = $request->placement_company ?? null;
        $data->placement_offering = $request->placement_offering ?? null;

        $data->remark = $request->remark ?? null;

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOADS
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('samarth_certificate')) {

            $file = $request->file('samarth_certificate');

            $filename = time().'_samarth_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/samarth'), $filename);

            $data->samarth_certificate = $filename;
        }

        if ($request->hasFile('uan_certificate')) {

            $file = $request->file('uan_certificate');

            $filename = time().'_uan_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/uan'), $filename);

            $data->uan_certificate = $filename;
        }

        if ($request->hasFile('offer_letter_file')) {

            $file = $request->file('offer_letter_file');

            $filename = time().'_offer_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/offer_letters'), $filename);

            $data->offer_letter_file = $filename;
        }

        $data->save();

        return redirect()->route(
            'assignment.students.view',
            [$data->assignment_id, $data->mobilization_id]
        )->with('success', 'Student data saved successfully');

    } catch (\Exception $e) {

        \Log::error('STORE ERROR: '.$e->getMessage());

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

public function fullView($assignmentId, $studentId)
{
    $assignment = Assignment::findOrFail($assignmentId);
    $student = Mobilization::findOrFail($studentId);

    $data = AssignmentStudent::where('assignment_id', $assignmentId)
        ->where('mobilization_id', $studentId)
        ->with('progress')
        ->first();

    $progressList = Progress::all();

    return view('assignment-students.full-view', compact(
        'assignment',
        'student',
        'data',
        'progressList'
    ));
}
    /**
     * Update assignment student data
     */
public function update(Request $request, $id)
{
    try {

        $request->validate([
            'remark' => 'nullable|string|max:50',
        ]);

        $data = AssignmentStudent::findOrFail($id);

        $data->samarth_done = $request->samarth_done;
        $data->samarth_id   = $request->samarth_id ?? null;

        $data->uan_done   = $request->boolean('uan_done');
        $data->uan_number = $request->uan_number ?? null;

        $data->documents_done    = $request->boolean('documents_done');
        $data->offer_letter_done = $request->boolean('offer_letter_done');

        $data->offer_letter_date = $request->offer_letter_date ?: null;

        $data->ec_date     = $request->ec_date ?: null;
        $data->progress_id = $request->progress_id ?: null;

        $data->registration_id       = $request->registration_id ?? null;
        $data->registration_password = $request->registration_password ?? null;
        $data->registration_number   = $request->registration_number ?? null;

        $data->ec_number = $request->ec_number ?? null;

        $data->date_of_placement = $request->date_of_placement ?: null;

        $data->placement_company  = $request->placement_company ?? null;
        $data->placement_offering = $request->placement_offering ?? null;

        $data->remark = $request->remark ?? null;

        /*
        |--------------------------------------------------------------------------
        | FILE UPDATE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('samarth_certificate')) {

            $file = $request->file('samarth_certificate');

            $filename = time().'_samarth_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/samarth'), $filename);

            $data->samarth_certificate = $filename;
        }

        if ($request->hasFile('uan_certificate')) {

            $file = $request->file('uan_certificate');

            $filename = time().'_uan_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/uan'), $filename);

            $data->uan_certificate = $filename;
        }

        if ($request->hasFile('offer_letter_file')) {

            $file = $request->file('offer_letter_file');

            $filename = time().'_offer_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/offer_letters'), $filename);

            $data->offer_letter_file = $filename;
        }

        $data->save();

        return redirect()->route(
            'assignment.students.view',
            [$data->assignment_id, $data->mobilization_id]
        )->with('success', 'Student data updated successfully');

    } catch (\Exception $e) {

        \Log::error('UPDATE ERROR: '.$e->getMessage());

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

public function candidates(Request $request, $assignment_id)
{
    $assignment = Assignment::findOrFail($assignment_id);

    $query = Mobilization::whereHas('assignments', function ($q) use ($assignment_id) {
        $q->where('assignment_id', $assignment_id);
    });

    // Search Mobile
    if ($request->mobile) {
        $query->where('mobile', 'like', '%' . $request->mobile . '%');
    }

    // Filter Assignment Student Data
    $query->whereHas('assignmentData', function ($q) use ($request, $assignment_id) {

        $q->where('assignment_id', $assignment_id);

        if ($request->samarth_done !== null && $request->samarth_done !== '') {
            $q->where('samarth_done', $request->samarth_done);
        }

        if ($request->uan_done !== null && $request->uan_done !== '') {
            $q->where('uan_done', $request->uan_done);
        }

        if ($request->documents_done !== null && $request->documents_done !== '') {
            $q->where('documents_done', $request->documents_done);
        }

        if ($request->offer_letter_done !== null && $request->offer_letter_done !== '') {
            $q->where('offer_letter_done', $request->offer_letter_done);
        }

        if ($request->placement_company) {
            $q->where('placement_company', 'like', '%' . $request->placement_company . '%');
        }

        if ($request->from_date && $request->to_date) {
            $q->whereBetween('date_of_placement', [
                $request->from_date,
                $request->to_date
            ]);
        }

    });

$candidates = $query->with([
    'assignmentData' => function ($q) use ($assignment_id) {
        $q->where('assignment_id', $assignment_id);
    },
    'assignmentData.progress', // ✅ NEW
    'batches'
])->get();

    return view('assignments.candidates', compact('assignment', 'candidates'));
}

}