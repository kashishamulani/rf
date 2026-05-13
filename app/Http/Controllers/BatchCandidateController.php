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
        'students.*' => 'required|string'
    ]);

    $assignmentId = $request->assignment_id;
    $successCount = 0;
    $errorMessages = [];

    foreach ($request->students as $studentJson) {
        try {
            $studentData = json_decode($studentJson, true);

            if (!isset($studentData['student_id'])) {
                $errorMessages[] = "Invalid student data";
                continue;
            }

            DB::table('batch_assignment_students')->updateOrInsert(
                [
                    'batch_id'      => $batch->id,
                    'assignment_id' => $assignmentId,
                    'student_id'    => $studentData['student_id'],
                ],
                [
                    // ✅ STORE FULL DATA (IMPORTANT)
                    'name'     => $studentData['name'] ?? null,
                    'mobile'   => $studentData['mobile'] ?? null,
                    'email'    => $studentData['email'] ?? null,
                    'district' => $studentData['district'] ?? null,
                    'state'    => $studentData['state'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $successCount++;

        } catch (\Exception $e) {
            $errorMessages[] = $e->getMessage();
        }
    }

    if ($successCount > 0) {
        return redirect()
            ->route('batches.index')
            ->with('success', "Added {$successCount} candidates successfully");
    }

    return redirect()->back()->with('error', 'Failed to add candidates');
}

public function view(Batch $batch)
{
    $assignments = $batch->assignments()->with('forms')->get();

    $batchCandidates = DB::table('batch_assignment_students')
        ->join('mobilizations', 'mobilizations.id', '=', 'batch_assignment_students.student_id')
        ->join('assignments', 'assignments.id', '=', 'batch_assignment_students.assignment_id')
        ->where('batch_assignment_students.batch_id', $batch->id)
        ->whereIn('batch_assignment_students.assignment_id', $assignments->pluck('id'))
        ->select(
            'batch_assignment_students.id as candidate_id',
            'batch_assignment_students.assignment_id',
            'batch_assignment_students.student_id',
            'mobilizations.name as candidate_name',
            'mobilizations.mobile',
            'mobilizations.email',
            'assignments.assignment_name',
            'batch_assignment_students.created_at'
        )
        ->distinct()
        ->get();

    $candidatesByAssignment = $batchCandidates->groupBy('assignment_id');
    $totalCandidates = $batchCandidates->count();

    return view('batches.view', compact(
        'batch',
        'assignments',
        'candidatesByAssignment',
        'totalCandidates'
    ));
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

public function destroy(Batch $batch, $candidateId)
{
    try {
        DB::table('batch_assignment_students')
            ->where('id', $candidateId)
            ->where('batch_id', $batch->id) // safety
            ->delete();

        return redirect()->back()->with('success', 'Candidate removed successfully');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to remove candidate: ' . $e->getMessage());
    }
}

}