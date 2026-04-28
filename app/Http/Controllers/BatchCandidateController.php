<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\Batch;
use App\Models\Assignment;

class BatchCandidateController extends Controller
{
   public function create(Batch $batch)
{
    $assignments = Assignment::with('forms')->latest()->get();

    $existingCandidates = DB::table('batch_assignment_students')
        ->where('batch_id', $batch->id)
        ->get()
        ->groupBy('assignment_id')
        ->map(fn ($rows) => $rows->pluck('student_id')->toArray())
        ->toArray();

    return view('batches.candidates', compact(
        'batch',
        'assignments',
        'existingCandidates'
    ));
}


    public function forms(Assignment $assignment)
    {
        $forms = $assignment->forms()->get();
        
        // Return with both IDs
        return response()->json($forms->map(function($form) {
            return [
                'id' => $form->form_id, // Return the ACTUAL form_id (75) as the value
                'assignment_form_id' => $form->id, // This is from assignment_forms table
                'form_id' => $form->form_id, // This is the actual form ID (75)
                'form_name' => $form->form_name,
                'location' => $form->location,
                'status' => $form->status,
                'valid_from' => $form->valid_from,
                'valid_to' => $form->valid_to,
                'link' => $form->link
            ];
        }));
    }

public function students($formId)
{
    \Log::info('=== STUDENTS METHOD CALLED ===');
    \Log::info('Form ID received: ' . $formId);
    
    try {
        // The API expects 'id' parameter, not 'form_id'
        $response = Http::get(
            'http://localhost/reliance_core/api/get-requests.php',
            ['id' => $formId]  // Changed from 'form_id' to 'id'
        );
        
        \Log::info('API Response Status: ' . $response->status());
        \Log::info('API URL called: http://localhost/reliance_core/api/get-requests.php?id=' . $formId);
        
        if ($response->successful()) {
            $students = $response->json();
            
            \Log::info('Number of students found: ' . (is_array($students) ? count($students) : '0'));
            
            // Check if response is valid
            if (empty($students)) {
                \Log::warning('API returned empty response for form ID: ' . $formId);
                return response()->json([]);
            }
            
            // If it's a single object, wrap it in an array
            if (!is_array($students)) {
                $students = [$students];
            } else if (is_array($students) && !isset($students[0])) {
                // If it's an associative array, wrap it
                $students = [$students];
            }
            
            // Return ALL data from API without transforming
            return response()->json($students)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type');
        }
        
        \Log::warning('API call failed with status: ' . $response->status());
        return response()->json([], 200);
        
    } catch (\Exception $e) {
        \Log::error('Error fetching students: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([], 200);
    }
}

   public function store(Request $request, Batch $batch)
{
    $request->validate([
        'assignment_id' => 'required|exists:assignments,id',
        'students' => 'required|array|min:1',
        'students.*' => 'required|string' // JSON string
    ]);

    $assignmentId = $request->assignment_id;
    $successCount = 0;
    $errorMessages = [];

    foreach ($request->students as $studentJson) {
        try {
            $studentData = json_decode($studentJson, true);
            
            // Validate the decoded data
            if (!isset($studentData['assignment_id']) || !isset($studentData['student_id'])) {
                $errorMessages[] = "Invalid student data format";
                continue;
            }
            
            // Ensure assignment_id matches
            if ($studentData['assignment_id'] != $assignmentId) {
                $errorMessages[] = "Assignment ID mismatch for student: " . $studentData['student_id'];
                continue;
            }

            // Insert into database
            DB::table('batch_assignment_students')->updateOrInsert(
                [
                    'batch_id'      => $batch->id,
                    'assignment_id' => $assignmentId,
                    'student_id'    => $studentData['student_id'],
                ],
                [] // no update fields needed
            );
            
            $successCount++;
            
        } catch (\Exception $e) {
            $errorMessages[] = "Error saving student: " . $e->getMessage();
        }
    }

    if ($successCount > 0) {
        $message = "Successfully added {$successCount} candidate(s) to batch";
        
        if (!empty($errorMessages)) {
            $message .= ". " . count($errorMessages) . " error(s) occurred.";
            // Log errors
            \Log::error('Batch candidate save errors:', $errorMessages);
        }
        
        return redirect()
            ->route('batches.index')
            ->with('success', $message);
    } else {
        return redirect()
            ->back()
            ->with('error', 'Failed to add candidates. Please try again.')
            ->withInput();
    }
}

public function view(Batch $batch)
{
    // Get assignments with forms
    $assignments = $batch->assignments()->with('forms')->get();
    
    // Get candidates with assignment details
    // $candidates = \DB::table('batch_assignment_students')
    //     ->where('batch_assignment_students.batch_id', $batch->id)
    //     ->join('assignments', 'batch_assignment_students.assignment_id', '=', 'assignments.id')
    //     ->select(
    //         'batch_assignment_students.id as candidate_id',
    //         'batch_assignment_students.*',
    //         'assignments.assignment_name',
    //         'assignments.location',
    //         'assignments.state'
    //     )
    //     ->orderBy('batch_assignment_students.created_at', 'desc')
    //     ->get();

    $candidates = DB::table('batch_assignment_students')
    ->join('mobilizations','mobilizations.id','=','batch_assignment_students.student_id')
    ->join('assignments','assignments.id','=','batch_assignment_students.assignment_id')
    ->where('batch_assignment_students.batch_id',$batch->id)
    ->select(
        'batch_assignment_students.id as candidate_id',
        'batch_assignment_students.student_id',
        'mobilizations.name as candidate_name',
        'mobilizations.mobile',
        'assignments.assignment_name',
        'batch_assignment_students.created_at'
    )
    ->get();
    
    // Group candidates by assignment
    $candidatesByAssignment = $candidates->groupBy('assignment_id');

    // Get total candidate count
    $totalCandidates = $candidates->count();

    return view('batches.view', compact('batch', 'assignments', 'candidatesByAssignment', 'totalCandidates'));
}

public function destroyCandidate($candidateId)
{
    try {
        DB::table('batch_assignment_students')->where('id', $candidateId)->delete();
        
        return redirect()->back()->with('success', 'Candidate removed successfully');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to remove candidate: ' . $e->getMessage());
    }
}
}