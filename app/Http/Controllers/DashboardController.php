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
        | BASIC COUNTS
        |--------------------------------------------------------------------------
        */

        $formatsCount = Format::count();
        $hrCount = Hr::count();
        $assignmentsCount = Assignment::count();
        $batchesCount = Batch::count();
        $poCount = Po::count();
        $phaseCount = Phase::count();
        $activitiesCount = Activity::count();
        $teamMembersCount = TeamMember::count();
        $activityAssignmentsCount = ActivityAssignment::count();


        /*
        |--------------------------------------------------------------------------
        | REQUIREMENT DATA
        |--------------------------------------------------------------------------
        */

        $totalRequirements = Assignment::sum('requirement');


        $totalBuild = \DB::table('assignment_batch')->sum('build');
        $totalBilledQty = \DB::table('invoice_assignment_items')
    ->join('invoices','invoices.id','=','invoice_assignment_items.invoice_id')
    ->sum('invoice_assignment_items.quantity') ?? 0;

        $totalLeft = $totalRequirements - $totalBuild;




// 1️⃣ Total Billing Amount (Quantity × Value)
$totalBilled = Invoice::sum('batch_value');

// Prevent null
$totalBilled = $totalBilled ?? 0;


// 2️⃣ Total Received (from pivot table)
$totalReceived = \DB::table('invoice_payment')->sum('amount');


// 3️⃣ Remaining
$totalPending = max(0, $totalBilled - $totalReceived);


 

        $totalAssignments = Assignment::count();
        $pendingAssignments = Assignment::where('status', 'Pending')->count();

        $cancelledAssignments = Assignment::where('status', 'Cancelled')->count();

        $inProgressAssignments = Assignment::where('status', 'In Progress')->count();

        $completedAssignments = Assignment::where('status', 'Completed')->count();


        /*
        |--------------------------------------------------------------------------
        | BATCH STATUS
        |--------------------------------------------------------------------------
        */

      $totalBatches   = Batch::count();
    $openBatches    = Batch::where('status', 'Open')->count();
    $closedBatches  = Batch::where('status', 'Closed')->count();
    $cancelledBatches = Batch::where('status', 'Cancelled')->count();
    $onHoldBatches  = Batch::where('status', 'On Hold')->count();
    $billedBatches  = Batch::where('status', 'Billed')->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(

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