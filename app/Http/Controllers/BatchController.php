<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Assignment;
use App\Models\Po;
use App\Models\BatchPoItem;
use App\Models\PoItem;
use App\Models\Candidate;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;

use App\Models\InvoicePoItem;

class BatchController extends Controller
{
// public function index()
// {
//     $batches = Batch::with([
//         'po',
//         'assignments' => function ($q) {
//             $q->withPivot('build');
//         },
//         'invoice',
//         'invoice.payments'
//     ])
//     ->orderBy('id','desc')
//     ->get();

//     return view('batches.index', compact('batches'));
// }

public function index(Request $request)
{
    $query = Batch::with([
        'po',
        'assignments' => function ($q) {
            $q->withPivot('build');
        },
        'invoice',
        'invoice.payments'
    ]);

    /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

    // Filter by Batch Code
    if ($request->filled('batch_code')) {
        $query->where('batch_code', 'like', '%' . $request->batch_code . '%');
    }

    // Filter by Assignment
    if ($request->filled('assignment_id')) {
        $assignmentId = $request->assignment_id;

        $query->whereHas('assignments', function ($q) use ($assignmentId) {
            $q->where('assignments.id', $assignmentId);
        });
    }


    // Filter by Date Range
if ($request->filled('from_date')) {
    $query->whereDate('created_at', '>=', $request->from_date);
}

if ($request->filled('to_date')) {
    $query->whereDate('created_at', '<=', $request->to_date);
}

    $batches = $query
        ->orderBy('id', 'desc')
        ->get();

    // Actual counts
    foreach ($batches as $batch) {

        $actualCounts = DB::table('batch_assignment_students')
            ->where('batch_id', $batch->id)
            ->select('assignment_id', DB::raw('COUNT(*) as actual_count'))
            ->groupBy('assignment_id')
            ->pluck('actual_count', 'assignment_id');

        foreach ($batch->assignments as $assignment) {
            $assignment->actual_in_batch = $actualCounts[$assignment->id] ?? 0;
        }
    }

    // Assignment dropdown data
    $assignments = Assignment::orderBy('assignment_name')
        ->get();

    return view('batches.index', compact(
        'batches',
        'assignments'
    ));
}

    public function create()
{
    $assignments = Assignment::latest()->get();
    $pos = Po::latest()->get();

    return view('batches.create', compact('assignments','pos'));
}


public function show($id)
{
    $batch = Batch::with([
        'po',
        'assignments' => function($q){
            $q->withPivot('build');
        },
        'invoice.payments'
    ])->findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | ONLY FETCH CANDIDATES ADDED IN THIS BATCH
    |--------------------------------------------------------------------------
    */

    $candidates = DB::table('batch_assignment_students as bas')
        ->join('mobilizations as m', 'm.id', '=', 'bas.student_id')
        ->join('assignments as a', 'a.id', '=', 'bas.assignment_id')
        ->where('bas.batch_id', $batch->id)
        ->select(
            'bas.id',
            'bas.batch_id',
            'bas.assignment_id',
            'bas.student_id',
            'm.id as candidate_id',
            'm.name as candidate_name',
            'm.mobile',
            'a.assignment_name',
            'bas.created_at'
        )
        ->orderBy('bas.created_at', 'desc')
        ->get();

    $candidatesByAssignment = $candidates->groupBy('assignment_id');

    $totalCandidates = $candidates->count();

    /*
    |--------------------------------------------------------------------------
    | EXISTING COUNTS
    |--------------------------------------------------------------------------
    */

    foreach ($batch->assignments as $assignment) {

        $assignment->in_batch = DB::table('batch_assignment_students')
            ->where('batch_id', $batch->id)
            ->where('assignment_id', $assignment->id)
            ->count();

        $assignment->billed_qty = DB::table('invoice_assignment_items')
            ->join('invoices','invoices.id','=','invoice_assignment_items.invoice_id')
            ->where('invoices.batch_id',$batch->id)
            ->where('invoice_assignment_items.assignment_id',$assignment->id)
            ->sum('invoice_assignment_items.quantity');
    }

    $invoice = $batch->invoice;

    $totalAmount = 0;
    $totalPaid = 0;
    $remaining = 0;

    if ($invoice) {

        $totalAmount = $invoice->batch_value ?? 0;

        $totalPaid = $invoice->payments->sum(function ($payment) {
            return $payment->pivot->amount;
        });

        $remaining = $totalAmount - $totalPaid;
    }

    return view('batches.show', compact(
        'batch',
        'invoice',
        'totalAmount',
        'totalPaid',
        'remaining',
        'candidatesByAssignment',
        'totalCandidates'
    ));
}
public function store(Request $request)
{
    $request->validate([
        'batch_code' => 'required|string|max:255|unique:batches,batch_code',
        'po_id' => 'nullable|exists:pos,id',
        'state' => 'required',
        'district' => 'required',
        'training_from' => 'required|date',
        'training_to' => 'required|date|after_or_equal:training_from',
        'training_hours' => 'required|numeric|min:0',
        'assignments' => 'nullable|array',
        'batch_size' => 'nullable|integer|min:1',
        'service_from' => 'required|date',
        'service_to' => 'required|date|after_or_equal:service_from',
    ]);

    DB::beginTransaction();

    try {

        $batch = Batch::create($request->only([
            'batch_code',
            'state',
            'district',
            'address',
            'status',
            'training_from',
            'training_to',
            'training_hours',
            'po_id',
            'batch_size',
             'service_from',
             'service_to'

        ]));

        if ($request->assignments) {

            $syncData = [];

            foreach ($request->assignments as $assignmentId) {

                $build = $request->builds[$assignmentId] ?? 0;

                $syncData[$assignmentId] = [
                    'build' => $build
                ];
            }

            $batch->assignments()->sync($syncData);
        }

        DB::commit();

        return redirect()->route('batches.index')
            ->with('success','Batch created successfully');

    } catch (\Exception $e) {

        DB::rollback();

        return back()->withErrors($e->getMessage())->withInput();
    }
}
public function edit($id)
{
    $batch = Batch::with('assignments')->findOrFail($id);

    $assignments = Assignment::latest()->get();
    $pos = Po::latest()->get();

    $batchAssignmentIds = $batch->assignments->pluck('id')->toArray();

    $assignmentBuilds = $batch->assignments
        ->pluck('pivot.build','id')
        ->toArray();

    return view('batches.edit',compact(
        'batch',
        'assignments',
        'pos',
        'batchAssignmentIds',
        'assignmentBuilds'
    ));
}
public function update(Request $request, $id)
{
    $request->validate([
        'batch_code' => 'required|string|max:255|unique:batches,batch_code,' . $id,
        'po_id' => 'nullable|exists:pos,id',
        'state' => 'required',
        'district' => 'required',
        'training_from' => 'required|date',
        'training_to' => 'required|date|after_or_equal:training_from',
        'training_hours' => 'required|numeric|min:0',
        'assignments' => 'nullable|array',
        'batch_size' => 'nullable|integer|min:1',
         'service_from' => 'required|date',
        'service_to' => 'required|date|after_or_equal:service_from',
    ]);

    DB::beginTransaction();

    try {

        $batch = Batch::findOrFail($id);

        $batch->update($request->only([
            'batch_code',
            'state',
            'district',
            'address',
            'status',
            'training_from',
            'training_to',
            'training_hours',
            'po_id',
            'batch_size',
             'service_from',
             'service_to'
        ]));

        $syncData = [];

        foreach ($request->assignments ?? [] as $assignmentId) {

            $build = $request->builds[$assignmentId] ?? 0;

            $syncData[$assignmentId] = [
                'build' => $build
            ];
        }

        $batch->assignments()->sync($syncData);

        DB::commit();

        return redirect()
            ->route('batches.index',$batch->id)
            ->with('success','Batch updated successfully');

    } catch (\Exception $e) {

        DB::rollback();

        return back()->withErrors($e->getMessage())->withInput();
    }
}

public function destroy($id)
{
    $batch = Batch::findOrFail($id);

    $batch->assignments()->detach();

    $batch->delete();

    return redirect()->route('batches.index')
        ->with('success','Batch deleted successfully');
}
public function updateStatus(Request $request, $id)
{
    // Example logic
    $batch = Batch::findOrFail($id);
    $batch->status = $request->status;
    $batch->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}

public function assignCandidates(Request $request)
{
    try {

        $request->validate([
            'candidate_ids' => 'required|array',
            'batch_ids'     => 'required|array',
            'assignment_id' => 'required|exists:assignments,id',
        ]);

        foreach ($request->batch_ids as $batchId) {

            foreach ($request->candidate_ids as $candidateId) {

                DB::table('batch_assignment_students')->updateOrInsert(
                    [
                        'batch_id'      => $batchId,
                        'assignment_id' => $request->assignment_id,
                        'student_id'    => $candidateId,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Candidates assigned successfully'
        ]);

    } catch (\Exception $e) {

        \Log::error($e);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function batchPdf($id)
{
    $batch = Batch::with('assignments')->findOrFail($id);

    /*
    ===============================
    CANDIDATES TRAINED (IN BATCH)
    ===============================
    */
    $candidates = DB::table('assignment_batch')
        ->where('batch_id', $batch->id)
        ->sum('build');


    /*
    ===============================
    PLACED TRAINEES (BILLED QTY)
    ===============================
    */
    $placed = DB::table('invoice_assignment_items')
        ->join('invoices', 'invoices.id', '=', 'invoice_assignment_items.invoice_id')
        ->where('invoices.batch_id', $batch->id)
        ->sum('invoice_assignment_items.quantity');


    $data = [
        'training_partner'  => 'e-Biz Technocrats Pvt. Ltd.',
        'vendor_code'       => '3546912',
        'training_location' => $batch->district ?? '-',
        'batch_code'        => $batch->batch_code,
        'format'            => $batch->format ?? '-',
        'start_date'        => $batch->training_from,
        'end_date'          => $batch->training_to,

        // ✅ FROM ASSIGNMENTS
        'candidates'        => $candidates,
        'placed'            => $placed,

        'completed'         => 'Yes',
    ];

    $pdf = Pdf::loadView('batches.batch-pdf', $data)
        ->setPaper('A4', 'portrait');

    return $pdf->stream('Batch-Completion-Report.pdf');
}
public function getAssignments($id)
{
    $batch = Batch::with('assignments')->findOrFail($id);

    $data = [];

    foreach ($batch->assignments as $assignment) {

        $built = $batch->assignments()
            ->where('assignment_id',$assignment->id)
            ->first()
            ->pivot
            ->build ?? 0;

        $data[] = [
            'id' => $assignment->id,
            'assignment_name' => $assignment->assignment_name,
            'requirement' => $assignment->requirement,
            'remaining' => $assignment->remaining ?? $assignment->requirement,
            'build' => $built
        ];
    }

    return response()->json($data);
}

public function getPoItems($batchId)
{
    try {
        \Log::info('Fetching PO items for batch: ' . $batchId);
        
        // Check if batch exists
        $batch = Batch::find($batchId);
        if (!$batch) {
            \Log::error('Batch not found: ' . $batchId);
            return response()->json(['error' => 'Batch not found'], 404);
        }
        
        // Get batch PO items
        $batchItems = BatchPoItem::with('poItem')
            ->where('batch_id', $batchId)
            ->get();
        
        \Log::info('Found batch items count: ' . $batchItems->count());
        
        $data = [];
        
        foreach ($batchItems as $batchItem) {
            if (!$batchItem->poItem) {
                \Log::warning('PO Item not found for batch item ID: ' . $batchItem->id);
                continue;
            }
            
            $item = $batchItem->poItem;
            
            // Calculate billed quantity
            $billed = InvoicePoItem::where('po_item_id', $item->id)
                        ->sum('qty');
            
            $remaining = $batchItem->qty - $billed;
            
            $data[] = [
                'id' => $item->id,
                'item_name' => $item->item,
                'po_qty' => $batchItem->qty,
                'remaining' => $remaining,
                'rate' => $item->value
            ];
        }
        
        return response()->json($data);
        
    } catch (\Exception $e) {
        \Log::error('Error in getPoItems for batch ' . $batchId . ': ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getPoItemsByPo($poId)
{
    $items = PoItem::where('po_id', $poId)->get();

    $items = $items->map(function ($item) {
        return [
            'id' => $item->id,
            'item' => $item->item,
            'value' => $item->value,
            'quantity' => $item->quantity,
            'used_quantity' => $item->used_quantity,
            'remaining_qty' => $item->quantity - $item->used_quantity,
        ];
    });

    return response()->json($items);
}

public function trackingSheet($batch_id)
{
    $batch = Batch::findOrFail($batch_id);

    $students = DB::table('batch_assignment_students')
        ->join('mobilizations','mobilizations.id','=','batch_assignment_students.student_id')
        ->leftJoin('assignment_students', function($join){
            $join->on('assignment_students.mobilization_id','=','mobilizations.id')
                 ->on('assignment_students.assignment_id','=','batch_assignment_students.assignment_id');
        })
        ->select(
            'mobilizations.name',
            'mobilizations.mobile',
            'mobilizations.gender',
            'assignment_students.registration_number',
            'assignment_students.samarth_done',
            'assignment_students.date_of_placement',
            'assignment_students.placement_company',
            'assignment_students.placement_offering',
            'assignment_students.ec_number'
        )
        ->where('batch_assignment_students.batch_id',$batch_id)
        ->get();

    $pdf = Pdf::loadView('pdf.batch-tracking-sheet',compact('batch','students'));

    return $pdf->stream('batch-tracking-sheet.pdf'); // 👈 open in browser
}
}