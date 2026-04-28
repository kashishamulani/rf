<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Batch;
use Barryvdh\DomPDF\Facade\Pdf;
   use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
public function index(Request $request)
{
    $query = Invoice::with([
        'batch',
        'poItems.poItem',
        'assignmentItems.assignment',
        'payments'
    ]);

    // FILTER BY ASSIGNMENT
    if ($request->assignment_id) {

        $query->whereHas('assignmentItems', function ($q) use ($request) {
            $q->where('assignment_id', $request->assignment_id);
        });

    }

    $invoices = $query->orderBy('id','desc')->get();

    foreach ($invoices as $invoice) {

        // subtotal from PO items
        $subtotal = $invoice->poItems->sum(function ($item) {
            return $item->qty * $item->poItem->value;
        });

        // invoice amount
        $invoice->batch_value = $subtotal * 1.18;

        // ✅ total paid
        $invoice->total_paid = $invoice->payments->sum(function ($payment) {
            return $payment->pivot->amount;
        });

        // remaining
        $invoice->remaining_amount = $invoice->batch_value - $invoice->total_paid;
    }

    $assignments = \App\Models\Assignment::select('id','assignment_name')->get();

    return view('invoices.index', compact('invoices','assignments'));
}
public function create()
{
    $batches = Batch::select('id','batch_code','batch_size','po_id')
        ->get()
        ->filter(function ($batch) {

            $totalQty = $batch->batch_size ?? 0;

            $usedQty = Invoice::where('batch_id', $batch->id)
                ->sum('batch_quantity');

            return ($totalQty - $usedQty) > 0;
        });

    return view('invoices.create', compact('batches'));
}
public function store(Request $request)
{
    $request->validate([
        'invoice_number'   => 'required|string|unique:invoices,invoice_number',
        'invoice_date'     => 'required|date',
        'batch_id'         => 'required|exists:batches,id',
        'batch_quantity'   => 'required|numeric|min:1',
    ]);

    DB::beginTransaction();

    try {

        // 🔒 Lock batch row
        $batch = Batch::where('id', $request->batch_id)
            ->lockForUpdate()
            ->firstOrFail();

        $totalBatchQty = $batch->batch_size ?? 0;

        if ($totalBatchQty <= 0) {
            DB::rollBack();
            return back()->withErrors([
                'batch_id' => 'Batch size is not defined.'
            ]);
        }

        // 🔢 Already invoiced qty
        $usedQty = Invoice::where('batch_id', $batch->id)
            ->sum('batch_quantity');

        $remainingQty = $totalBatchQty - $usedQty;

        if ($remainingQty <= 0) {
            DB::rollBack();
            return back()->withErrors([
                'batch_id' => 'No remaining quantity in this batch.'
            ]);
        }

        if ($request->batch_quantity > $remainingQty) {
            DB::rollBack();
            return back()->withErrors([
                'batch_quantity' => "Only {$remainingQty} quantity remaining in this batch."
            ]);
        }

        // ✅ CREATE INVOICE
        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date'   => $request->invoice_date,
            'batch_id'       => $batch->id,
            'batch_quantity' => $request->batch_quantity,
            'status'         => 'pending',
            'payment_detail' => $request->payment_detail,
        ]);

        if ($request->has('billed_assignments')) {

            foreach ($request->billed_assignments as $assignmentId => $qty) {

                if ($qty > 0) {

                    DB::table('invoice_assignment_items')->insert([
                        'invoice_id'    => $invoice->id,
                        'assignment_id' => $assignmentId,
                        'quantity'      => $qty,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);

                }

            }
        }

        if ($request->has('billed_po_items')) {

            foreach ($request->billed_po_items as $poItemId => $qty) {

                if ($qty > 0) {

                    DB::table('invoice_po_items')->insert([
                        'invoice_id' => $invoice->id,
                        'batch_id'   => $batch->id,
                        'po_item_id' => $poItemId,
                        'qty'        => $qty,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                }

            }

        }
        $subtotal = DB::table('invoice_po_items')
            ->join('po_items', 'po_items.id', '=', 'invoice_po_items.po_item_id')
            ->where('invoice_po_items.invoice_id', $invoice->id)
            ->sum(DB::raw('invoice_po_items.qty * po_items.value'));

        $batchValue = $subtotal * 1.18; // include GST

       $invoice->update([
    'batch_value' => $batchValue
    ]);


        DB::commit();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully!');

    } catch (\Exception $e) {

        DB::rollBack();
        throw $e;
    }
}
public function edit($id)
{
    $invoice = Invoice::with([
        'assignmentItems',
        'poItems'
    ])->findOrFail($id);

    $batches = Batch::select('id','batch_code','batch_size','po_id')
        ->get()
        ->filter(function ($batch) use ($invoice) {

            $totalQty = $batch->batch_size ?? 0;

            $usedQty = Invoice::where('batch_id', $batch->id)
                ->where('id','!=',$invoice->id)
                ->sum('batch_quantity');

            return ($totalQty - $usedQty) > 0;
        });

    return view('invoices.edit', compact('invoice','batches'));
}

public function update(Request $request, $id)
{
    $invoice = Invoice::findOrFail($id);

    $request->validate([
        'invoice_number'   => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
        'invoice_date'     => 'required|date',
        'batch_id'         => 'required|exists:batches,id',
        'batch_quantity'   => 'required|numeric|min:1',
    ]);

    DB::beginTransaction();

    try {

        // 🔒 Prevent edit if payment exists
        if ($invoice->payments()->exists()) {
            DB::rollBack();
            return back()->withErrors([
                'batch_quantity' => 'Cannot modify invoice after payment is recorded.'
            ]);
        }

        // 🔒 Lock batch
        $batch = Batch::where('id', $request->batch_id)
            ->lockForUpdate()
            ->firstOrFail();

        $batchSize = $batch->batch_size ?? 0;

        if ($batchSize <= 0) {
            DB::rollBack();
            return back()->withErrors([
                'batch_id' => 'Batch size is not defined.'
            ]);
        }

        // 🔢 Used quantity excluding this invoice
        $usedQty = Invoice::where('batch_id', $batch->id)
            ->where('id','!=',$invoice->id)
            ->sum('batch_quantity');

        $remainingQty = $batchSize - $usedQty;

        if ($remainingQty <= 0) {
            DB::rollBack();
            return back()->withErrors([
                'batch_id' => 'No remaining quantity in this batch.'
            ]);
        }

        if ($request->batch_quantity > $remainingQty) {
            DB::rollBack();
            return back()->withErrors([
                'batch_quantity' => "Only {$remainingQty} quantity remaining in this batch."
            ]);
        }

        /*
        ========================
        UPDATE INVOICE
        ========================
        */

        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date'   => $request->invoice_date,
            'batch_id'       => $batch->id,
            'batch_quantity' => $request->batch_quantity,
            'payment_detail' => $request->payment_detail,
        ]);

        /*
        ========================
        DELETE OLD ITEMS
        ========================
        */

        DB::table('invoice_assignment_items')
            ->where('invoice_id',$invoice->id)
            ->delete();

        DB::table('invoice_po_items')
            ->where('invoice_id',$invoice->id)
            ->delete();

        /*
        ========================
        SAVE ASSIGNMENT ITEMS
        ========================
        */

        if ($request->has('billed_assignments')) {

            foreach ($request->billed_assignments as $assignmentId => $qty) {

                if ($qty > 0) {

                    DB::table('invoice_assignment_items')->insert([
                        'invoice_id'    => $invoice->id,
                        'assignment_id' => $assignmentId,
                        'quantity'      => $qty,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);

                }
            }
        }

        /*
        ========================
        SAVE PO ITEMS
        ========================
        */

        if ($request->has('billed_po_items')) {

            foreach ($request->billed_po_items as $poItemId => $qty) {

                if ($qty > 0) {

                    DB::table('invoice_po_items')->insert([
                        'invoice_id' => $invoice->id,
                        'batch_id'   => $batch->id,
                        'po_item_id' => $poItemId,
                        'qty'        => $qty,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                }
            }
        }

        /*
        ========================
        RECALCULATE BATCH VALUE
        ========================
        */

        $subtotal = DB::table('invoice_po_items')
            ->join('po_items', 'po_items.id','=','invoice_po_items.po_item_id')
            ->where('invoice_po_items.invoice_id',$invoice->id)
            ->sum(DB::raw('invoice_po_items.qty * po_items.value'));

        $batchValue = $subtotal * 1.18;

        $invoice->update([
            'batch_value' => $batchValue
        ]);

        DB::commit();

        return redirect()->route('invoices.index')
            ->with('success','Invoice updated successfully!');

    } catch (\Exception $e) {

        DB::rollBack();
        throw $e;

    }
}

public function destroy($id)
{
    DB::beginTransaction();

    try {

        $invoice = Invoice::findOrFail($id);

        // delete assignment billing
        DB::table('invoice_assignment_items')
            ->where('invoice_id', $invoice->id)
            ->delete();

        // delete po billing
        DB::table('invoice_po_items')
            ->where('invoice_id', $invoice->id)
            ->delete();

        // delete invoice
        $invoice->delete();

        DB::commit();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');

    } catch (\Exception $e) {

        DB::rollBack();
        throw $e;

    }
}

    // ✅ Generate Invoice PDF (DYNAMIC ITEMS)
public function pdf($id)
{
    $invoice = Invoice::with([
    'batch.po',
    'poItems.poItem',
    'assignmentItems.assignment'
    ])->findOrFail($id);

    // Calculate subtotal from billed PO items
    $subtotal = DB::table('invoice_po_items')
        ->join('po_items', 'po_items.id', '=', 'invoice_po_items.po_item_id')
        ->where('invoice_po_items.invoice_id', $invoice->id)
        ->selectRaw('SUM(invoice_po_items.qty * po_items.value) as total')
        ->value('total');

    $subtotal = $subtotal ?? 0;

    // GST
    $gst = $subtotal * 0.18;

    // Final amount
    $total = $subtotal + $gst;

    $pdf = Pdf::loadView('invoices.pdf', [
        'invoice' => $invoice,
        'subtotal' => $subtotal,
        'gst' => $gst,
        'total' => $total
    ])->setPaper('A4','portrait');

    $safeInvoiceNumber = preg_replace('/[^A-Za-z0-9_\-]/', '-', $invoice->invoice_number);
    $filename = 'invoice_' . $safeInvoiceNumber . '.pdf';

    return $pdf->stream($filename);
}
public function updateStatus($id, Request $request)
{
    $invoice = Invoice::findOrFail($id);
    $invoice->status = $request->status;
    $invoice->save();

    return response()->json(['success' => true]);
}
public function payments($id)
{
    $invoice = Invoice::with(['payments', 'poItems.poItem'])
        ->findOrFail($id);

    $totalAmount = $invoice->batch_value; // ✅ changed
    $totalPaid   = $invoice->payments->sum('pivot.amount');
    $remaining   = $totalAmount - $totalPaid;

    return view('invoices.payments', compact(
        'invoice',
        'totalAmount',
        'totalPaid',
        'remaining'
    ));
}


private function getUsedBatchQty($batchId, $ignoreInvoiceId = null)
{
    return Invoice::where('batch_id', $batchId)
        ->when($ignoreInvoiceId, function ($q) use ($ignoreInvoiceId) {
            $q->where('id', '!=', $ignoreInvoiceId);
        })
        ->sum('batch_quantity');
}

// public function batchAssignments($id)
// {
//     $assignments = DB::table('assignment_batch')
//         ->join('assignments','assignments.id','=','assignment_batch.assignment_id')
//         ->where('assignment_batch.batch_id',$id)
//         ->select(
//             'assignments.id',
//             'assignments.assignment_name',
//             'assignments.requirement',
//             'assignment_batch.build'
//         )
//         ->get()
//         ->map(function ($a) {

//            $billed = DB::table('invoice_assignment_items')
//     ->join('invoices','invoices.id','=','invoice_assignment_items.invoice_id')
//     ->where('assignment_id',$a->id)
//     ->sum('invoice_assignment_items.quantity');

//             $a->billed_qty = $billed;
//             $a->remaining_qty = $a->requirement - $billed;

//             return $a;
//         });

//     return response()->json($assignments);
// }


public function batchAssignments($batchId)
{
    $assignments = DB::table('assignment_batch')
        ->join('assignments', 'assignments.id', '=', 'assignment_batch.assignment_id')
        ->where('assignment_batch.batch_id', $batchId)
        ->select(
            'assignments.id',
            'assignments.assignment_name',
            'assignments.requirement'
        )
        ->get()
        ->map(function ($a) use ($batchId) {
            // ✅ FIX: Get the build value from assignment_batch pivot table
            $inBatch = DB::table('assignment_batch')
                ->where('batch_id', $batchId)
                ->where('assignment_id', $a->id)
                ->value('build') ?? 0;
            
            // ✅ If build is not set in assignment_batch, count from batch_assignment_students
            if ($inBatch == 0) {
                $inBatch = DB::table('batch_assignment_students')
                    ->where('batch_id', $batchId)
                    ->where('assignment_id', $a->id)
                    ->count();
            }

            // ✅ Already billed from invoices
            $billed = DB::table('invoice_assignment_items')
                ->join('invoices', 'invoices.id', '=', 'invoice_assignment_items.invoice_id')
                ->where('invoices.batch_id', $batchId)
                ->where('invoice_assignment_items.assignment_id', $a->id)
                ->sum('invoice_assignment_items.quantity');

            $a->build = $inBatch; // This will show "In Batch"
            $a->remaining = $inBatch - $billed;

            return $a;
        });

    return response()->json($assignments);
}

public function batchPoItems($id)
{
    $batch = Batch::with('po.items')->findOrFail($id);

    $items = $batch->po->items->map(function ($item) {

        $billed = DB::table('invoice_po_items')
            ->where('po_item_id', $item->id)
            ->sum('qty');

        $item->billed_qty = $billed;
        $item->remaining_qty = $item->quantity - $billed;

        return $item;
    });

    return response()->json([
        'po_no' => $batch->po->po_no,
        'items' => $items
    ]);
}
public function getBatchValue($id)
{
    $totalValue = DB::table('invoice_po_items')
        ->join('po_items', 'po_items.id', '=', 'invoice_po_items.po_item_id')
        ->where('invoice_po_items.batch_id', $id)
        ->selectRaw('SUM(invoice_po_items.qty * po_items.value) as total')
        ->value('total');

    $totalQty = DB::table('invoice_po_items')
        ->where('batch_id',$id)
        ->sum('qty');

    return response()->json([
        'total_value' => $totalValue ?? 0,
        'total_quantity' => $totalQty ?? 0
    ]);
}

public function getBatchInfo($id)
{
    $batch = Batch::select('id','batch_code','batch_size','po_id')
        ->findOrFail($id);

    $totalValue = DB::table('invoice_po_items')
        ->join('po_items', 'po_items.id','=','invoice_po_items.po_item_id')
        ->where('invoice_po_items.batch_id',$id)
        ->selectRaw('SUM(invoice_po_items.qty * po_items.value) as total')
        ->value('total');

    $totalQty = DB::table('invoice_po_items')
        ->where('batch_id',$id)
        ->sum('qty');

    $used_quantity = Invoice::where('batch_id',$id)
        ->sum('batch_quantity');

    $remaining_quantity = ($batch->batch_size ?? 0) - $used_quantity;

    return response()->json([
        'batch_size' => $batch->batch_size ?? 0,
        'batch_code' => $batch->batch_code,
        'po_id' => $batch->po_id,
        'total_value' => $totalValue ?? 0,
        'total_quantity' => $totalQty ?? 0,
        'used_quantity' => $used_quantity,
        'remaining_quantity' => $remaining_quantity
    ]);
}

public function show($id)
{
    $invoice = Invoice::with([
        'batch',
        'poItems.poItem',
        'assignmentItems.assignment',
        'payments' // load payments relation
    ])->findOrFail($id);

    // Total Invoice Amount
    $totalAmount = $invoice->batch_value;

    // Total Paid for this invoice
    $totalPaid = $invoice->payments->sum(function ($payment) {
        return $payment->pivot->amount;
    });

    // Remaining amount
    $remaining = $totalAmount - $totalPaid;

    return view('invoices.show', compact(
        'invoice',
        'totalAmount',
        'totalPaid',
        'remaining'
    ));
}

public function fullPdf($id)
{
    $invoice = Invoice::with([
        'batch.po',
        'batch.assignments.format',
        'poItems.poItem',
        'assignmentItems.assignment'
    ])->findOrFail($id);

    $batch = $invoice->batch;

    /*
    ===============================
    PREPARE BATCH DATA (2nd PAGE)
    ===============================
    */

    /*
================================
CANDIDATES TRAINED (IN BATCH)
================================
*/
$candidates = DB::table('assignment_batch')
    ->where('batch_id', $batch->id)
    ->sum('build');


/*
================================
PLACED TRAINEES (BILLED)
================================
*/
$placed = DB::table('invoice_assignment_items')
    ->join('invoices','invoices.id','=','invoice_assignment_items.invoice_id')
    ->where('invoices.batch_id',$batch->id)
    ->sum('invoice_assignment_items.quantity');


$batchData = [
    'training_partner'  => 'E-Biz Technocrats Pvt. Ltd.',
    'vendor_code'       => '3546912',
    'training_location' => $batch->district ?? '-',
    'batch_code'        => $batch->batch_code,
    'format' => $batch->assignments
                ->pluck('format.type')
                ->filter()
                ->unique()
                ->implode(', ') ?: '-',
    'start_date'        => $batch->training_from,
    'end_date'          => $batch->training_to,

    // ✅ NOW FROM ASSIGNMENTS
    'candidates' => $candidates,
    'placed'     => $placed,

    'completed'  => 'Yes',
];

    $pdf = Pdf::loadView('pdf.invoice-batch-combined', [
        'invoice' => $invoice,
        'batchData' => $batchData
    ])->setPaper('A4','portrait');

    return $pdf->stream('Invoice-With-Batch.pdf');
}
}