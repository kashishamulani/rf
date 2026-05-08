<?php

namespace App\Http\Controllers;

use App\Models\Format;
use App\Models\Hr;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Po;
use App\Models\Phase;
use App\Models\Activity;
use App\Models\TeamMember;
use App\Models\ActivityAssignment;
use App\Models\Invoice;
use App\Models\Payment;

class DashboardController extends Controller
{
public function index()
{
    /*
    |--------------------------------------------------------------------------
    | FINANCIAL YEAR (DEFAULT = 2026-2027)
    |--------------------------------------------------------------------------
    */

    $fy = request('fy') ?? '2026-2027';

    [$startYear, $endYear] = explode('-', $fy);

    $startDate = $startYear . '-04-01';
    $endDate   = $endYear . '-03-31';


    /*
    |--------------------------------------------------------------------------
    | BASIC COUNTS (WITH FY FILTER)
    |--------------------------------------------------------------------------
    */

    $formatsCount = Format::whereBetween('created_at', [$startDate, $endDate])->count();
    $hrCount = Hr::whereBetween('created_at', [$startDate, $endDate])->count();
    $assignmentsCount = Assignment::whereBetween('created_at', [$startDate, $endDate])->count();
    $batchesCount = Batch::whereBetween('created_at', [$startDate, $endDate])->count();
    $poCount = Po::whereBetween('created_at', [$startDate, $endDate])->count();
    $phaseCount = Phase::whereBetween('created_at', [$startDate, $endDate])->count();
    $activitiesCount = Activity::whereBetween('created_at', [$startDate, $endDate])->count();
    $teamMembersCount = TeamMember::whereBetween('created_at', [$startDate, $endDate])->count();
    $activityAssignmentsCount = ActivityAssignment::whereBetween('created_at', [$startDate, $endDate])->count();


    /*
    |--------------------------------------------------------------------------
    | REQUIREMENT DATA
    |--------------------------------------------------------------------------
    */

    $totalRequirements = Assignment::whereBetween('created_at', [$startDate, $endDate])
        ->sum('requirement');

    $totalBuild = \DB::table('assignment_batch')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('build');

    $totalBilledQty = \DB::table('invoice_assignment_items')
        ->join('invoices', 'invoices.id', '=', 'invoice_assignment_items.invoice_id')
        ->whereBetween('invoices.created_at', [$startDate, $endDate])
        ->sum('invoice_assignment_items.quantity') ?? 0;

    $totalLeft = $totalRequirements - $totalBuild;


    /*
    |--------------------------------------------------------------------------
    | BILLING DATA
    |--------------------------------------------------------------------------
    */

    $totalBilled = Invoice::whereBetween('created_at', [$startDate, $endDate])
        ->sum('batch_value') ?? 0;

    $totalReceived = \DB::table('invoice_payment')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('amount');

    $totalPending = max(0, $totalBilled - $totalReceived);


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT STATUS
    |--------------------------------------------------------------------------
    */

    $totalAssignments = Assignment::whereBetween('created_at', [$startDate, $endDate])->count();

    $pendingAssignments = Assignment::where('status', 'Pending')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    $cancelledAssignments = Assignment::where('status', 'Cancelled')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    $inProgressAssignments = Assignment::where('status', 'In Progress')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    $completedAssignments = Assignment::where('status', 'Completed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | BATCH STATUS
    |--------------------------------------------------------------------------
    */

    $totalBatches = Batch::whereBetween('created_at', [$startDate, $endDate])->count();

    $openBatches = Batch::where('status', 'Open')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    $closedBatches = Batch::where('status', 'Closed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    $cancelledBatches = Batch::where('status', 'Cancelled')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    $onHoldBatches = Batch::where('status', 'On Hold')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    $billedBatches = Batch::where('status', 'Billed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view('dashboard', compact(

        'fy',

        'formatsCount',
        'hrCount',
        'assignmentsCount',
        'batchesCount',
        'poCount',
        'phaseCount',
        'activitiesCount',
        'teamMembersCount',
        'activityAssignmentsCount',

        // Requirement
        'totalRequirements',
        'totalBuild',
        'totalLeft',
        'totalBilledQty',

        // Billing
        'totalBilled',
        'totalReceived',
        'totalPending',

        // Assignment
        'totalAssignments',
        'pendingAssignments',
        'cancelledAssignments',
        'inProgressAssignments',
        'completedAssignments',

        // Batch
        'totalBatches',
        'openBatches',
        'closedBatches',
        'cancelledBatches',
        'onHoldBatches',
        'billedBatches'
    ));
}   
}