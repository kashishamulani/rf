@extends('layouts.app')

@section('content')

<style>
.section {
    margin-bottom: 40px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 14px;
    color: #374151;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 14px;
}

.card {
    background: #fff;
    border-radius: 12px;
    padding: 14px 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
}

.card h3 {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #6b7280;
}

.card .value {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
}
</style>


{{-- ================= REQUIREMENT SECTION ================= --}}
<div class="section">

    <div class="section-title">Requirement Overview</div>

    <div class="cards">

        <div class="card">
            <h3>Total Requirement</h3>
            <div class="value">{{ $totalRequirements }}</div>
        </div>

        <div class="card">
            <h3>Total In Batch</h3>
            <div class="value">{{ $totalBuild }}</div>
        </div>

        <div class="card">
            <h3>Total Left</h3>
            <div class="value">{{ $totalLeft }}</div>
        </div>

        <div class="card">
            <h3>Total Billed</h3>
            <div class="value">{{ $totalBilledQty }}</div>
        </div>
    </div>
</div>


{{-- ================= BILLING SECTION ================= --}}
<div class="section">

    <div class="section-title">Billing Overview</div>

    <div class="cards">

        <div class="card">
            <h3>Total Bill Amount</h3>
            <div class="value">{{ $totalBilled }}</div>
        </div>

        <div class="card">
            <h3>Total Received</h3>
            <div class="value">{{ $totalReceived }}</div>
        </div>

        <div class="card">
            <h3>Total Pending</h3>
            <div class="value">{{ $totalPending }}</div>
        </div>

    </div>
</div>


{{-- ================= ASSIGNMENT SECTION ================= --}}
<div class="section">

    <div class="section-title">Assignments</div>

    <div class="cards">

        <div class="card">
            <h3>Total Assignment</h3>
            <div class="value">{{ $totalAssignments }}</div>
        </div>

        <div class="card">
            <h3>Pending</h3>
            <div class="value">{{ $pendingAssignments }}</div>
        </div>

        <div class="card">
            <h3>Cancelled</h3>
            <div class="value">{{ $cancelledAssignments }}</div>
        </div>

        <div class="card">
            <h3>In Progress</h3>
            <div class="value">{{ $inProgressAssignments }}</div>
        </div>

        <div class="card">
            <h3>Completed</h3>
            <div class="value">{{ $completedAssignments }}</div>
        </div>

    </div>
</div>


{{-- ================= BATCH SECTION ================= --}}
<div class="section">

    <div class="section-title">Batches</div>

    <div class="cards">

        <div class="card">
            <h3>Total Batches</h3>
            <div class="value">{{ $totalBatches }}</div>
        </div>

        <div class="card">
            <h3>Open</h3>
            <div class="value">{{ $openBatches }}</div>
        </div>

        <div class="card">
            <h3>Closed</h3>
            <div class="value">{{ $closedBatches }}</div>
        </div>

        <div class="card">
            <h3>Cancelled</h3>
            <div class="value">{{ $cancelledBatches }}</div>
        </div>

        <div class="card">
            <h3>On Hold</h3>
            <div class="value">{{ $onHoldBatches }}</div>
        </div>

        <!-- <div class="card">
            <h3>Billed</h3>
            <div class="value">{{ $billedBatches }}</div>
        </div> -->

    </div>
</div>

@endsection