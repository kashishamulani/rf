<?php
namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function business()
    {
        $data = DB::table('assignments')
            ->leftJoin('hrs', 'hrs.id', '=', 'assignments.hr_id')
            ->leftJoin('assignment_batch', 'assignment_batch.assignment_id', '=', 'assignments.id')
        ->leftJoin('batches', 'batches.id', '=', 'assignment_batch.batch_id')
        ->leftJoin('invoices', 'invoices.batch_id', '=', 'batches.id')
        ->leftJoin('invoice_payment', 'invoice_payment.invoice_id', '=', 'invoices.id')
        ->leftJoin('payments', 'payments.id', '=', 'invoice_payment.payment_id')

        ->select(
            'assignments.state',
            'hrs.name as hr_name',
            'assignments.assignment_name',
            DB::raw('COUNT(DISTINCT assignments.id) as number'),
            'batches.batch_code',
            'invoices.invoice_number',
            'invoices.batch_value as value',
            DB::raw('SUM(invoice_payment.amount) as payment')
        )
        ->groupBy(
            'assignments.state',
            'hrs.name',
            'assignments.assignment_name',
            'batches.batch_code',
            'invoices.invoice_number',
            'invoices.batch_value'
        )
        ->get();

    return view('reports.business', compact('data'));
}


}