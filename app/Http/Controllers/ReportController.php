<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function business(Request $request)
    {
        $query = DB::table('assignments')
            ->leftJoin('hrs', 'hrs.id', '=', 'assignments.hr_id')
            ->leftJoin('assignment_batch', 'assignment_batch.assignment_id', '=', 'assignments.id')
            ->leftJoin('batches', 'batches.id', '=', 'assignment_batch.batch_id')
            ->leftJoin('invoices', 'invoices.batch_id', '=', 'batches.id')
            ->leftJoin('invoice_payment', 'invoice_payment.invoice_id', '=', 'invoices.id')

            ->select(
                'assignments.state',

                DB::raw('COUNT(DISTINCT assignments.id) as total_assignments'),

                DB::raw('COUNT(DISTINCT batches.id) as total_batches'),

                DB::raw("
                    GROUP_CONCAT(DISTINCT assignments.assignment_name 
                    SEPARATOR ', ') as assignment_names
                "),

                DB::raw("
                    GROUP_CONCAT(DISTINCT batches.batch_code 
                    SEPARATOR ', ') as batch_names
                "),

                DB::raw('SUM(invoices.batch_value) as total_value'),

                DB::raw('SUM(invoice_payment.amount) as total_payment')
            );

        // FILTERS

        if ($request->filled('state')) {
            $query->where('assignments.state', 'like', '%' . $request->state . '%');
        }

        if ($request->filled('hr')) {
            $query->where('hrs.name', 'like', '%' . $request->hr . '%');
        }

        // DATE FILTER
        if ($request->filled('from_date')) {
            $query->whereDate('assignments.created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('assignments.created_at', '<=', $request->to_date);
        }

        $data = $query
            ->groupBy('assignments.state')
            ->orderBy('assignments.state')
            ->get();

        return view('reports.business', compact('data'));
    }
}