@extends('layouts.app')

@section('content')

<style>
.page-wrap {
    max-width: 1400px;
    margin: auto;
    padding: 24px;
}

.card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
    border: 1px solid #e5e7eb;
}

.badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.badge-indigo {
    background: #e0e7ff;
    color: #3730a3;
}

.badge-green {
    background: #d1fae5;
    color: #065f46;
}

.badge-red {
    background: #fee2e2;
    color: #991b1b;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: .25s;
}

.btn-gray {
    background: #e5e7eb;
    color: #374151;
}

.btn-gray:hover {
    background: #d1d5db;
}

.btn-green {
    background: #22c55e;
    color: #fff;
}

.btn-green:hover {
    background: #16a34a;
}

.btn-red {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.btn-red:hover {
    background: #fecaca;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 10px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.info-box {
    padding: 16px;
    border-radius: 14px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
}

.info-title {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 4px;
}

.info-value {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
}

.progress {
    width: 100%;
    height: 10px;
    background: #e5e7eb;
    border-radius: 999px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(135deg, #6366f1, #ec4899);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

th,
td {
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
}
</style>

<div class="page-wrap">

    {{-- HEADER --}}
    <div style="display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:28px">
        <div>
            <h1 style="font-size:28px;font-weight:800;color:#111827;margin-bottom:8px">
                <i class="fa-solid fa-layer-group" style="color:#6366f1"></i>
                Batch {{ $batch->batch_code }}
            </h1>

            <div style="display:flex;gap:14px;flex-wrap:wrap;color:#6b7280;font-size:14px">
                <span><i class="fa-solid fa-location-dot"></i>
                    {{ $batch->state ?? 'N/A' }} , {{ $batch->district ?? 'N/A' }}
                </span>
                <span><i class="fa-solid fa-building"></i> {{ $batch->address ?? 'N/A' }}</span>
                <span><i class="fa-solid fa-calendar"></i> {{ $batch->created_at->format('d-m-Y') }}</span>
                <span class="badge badge-indigo">{{ $batch->status }}</span>
            </div>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('batches.index') }}" class="btn btn-gray">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('batches.edit',$batch->id) }}" class="btn btn-green">
                <i class="fa-solid fa-pen"></i> Edit Batch
            </a>
        </div>
    </div>

    {{-- BATCH INFO --}}
    <div class="card" style="padding:20px;margin-bottom:28px">
        <div class="grid">
            <div class="info-box">
                <div class="info-title">PO Number</div>
                <div class="info-value">{{ $batch->po?->po_no ?? 'N/A' }}</div>
            </div>

            <div class="info-box">
                <div class="info-title">Training Dates</div>
                <div class="info-value">
                    {{ $batch->training_from ? $batch->training_from->format('d-m-Y') : 'N/A' }}
                    -
                    {{ $batch->training_to ? $batch->training_to->format('d-m-Y') : 'N/A' }}
                </div>
            </div>

            <div class="info-box">
                <div class="info-title">Training Hours</div>
                <div class="info-value">{{ $batch->training_hours ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="info-title">Service Period</div>
                <div class="info-value">
                    {{ $batch->service_from ? \Carbon\Carbon::parse($batch->service_from)->format('d-m-Y') : 'N/A' }}
                    -
                    {{ $batch->service_to ? \Carbon\Carbon::parse($batch->service_to)->format('d-m-Y') : 'N/A' }}
                </div>
            </div>

            <div class="info-box">
                <div class="info-title">State</div>
                <div class="info-value">{{ $batch->state ?? 'N/A' }}</div>
            </div>

            <div class="info-box">
                <div class="info-title">District</div>
                <div class="info-value">{{ $batch->district ?? 'N/A' }}</div>
            </div>

            <!-- NEW: Batch Size -->
            <div class="info-box">
                <div class="info-title">Batch Size</div>
                <div class="info-value">
                    {{ $batch->batch_size }}
                </div>
            </div>
        </div>
    </div>


    {{-- PO DETAILS --}}
    @if($batch->po)
    <div class="card" style="padding:20px;margin-bottom:28px">
        <div class="section-title" style="margin-bottom:16px">
            <i class="fa-solid fa-file-contract"></i>
            PO Details
        </div>

        <div class="grid">
            <div class="info-box">
                <div class="info-title">PO Number</div>
                <div class="info-value">{{ $batch->po->po_no }}</div>
            </div>

            <div class="info-box">
                <div class="info-title">PO Date</div>
                <div class="info-value">{{ $batch->po->po_date ?? 'N/A' }}</div>
            </div>

        </div>
    </div>
    @endif







    {{-- ASSIGNMENTS --}}

    @if($batch->assignments->count())
    <div class="card" style="padding:24px;margin-bottom:32px">

        <div class="section-title" style="margin-bottom:20px">
            <i class="fa-solid fa-clipboard-list" style="color:#6366f1"></i>
            Assignment Details
        </div>

        <div style="overflow-x:auto;border-radius:14px;border:1px solid #e5e7eb">

            <table style="min-width:700px">
                <thead>
                    <tr style="background:#f9fafb;text-align:left">
                        <th style="width:60px">#</th>
                        <th>Assignment Name</th>
                        <th style="text-align:center">Requirement</th>
                        <th style="text-align:center"> In Batch</th>
                        <th style="text-align:center">Billed Qty</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($batch->assignments as $index => $assignment)
                    <tr>
                        <td style="font-weight:600;color:#6b7280">
                            {{ $index + 1 }}
                        </td>

                        <td style="font-weight:600;color:#111827">
                            {{ $assignment->assignment_name }}
                        </td>

                        <td style="text-align:center">
                            <span class="badge badge-indigo">
                                {{ $assignment->requirement ?? 0 }}
                            </span>
                        </td>

                        <td style="text-align:center">
                            <span class="badge badge-green">
                                <!-- {{ $assignment->pivot->build ?? 0 }} -->

                                {{ $assignment->in_batch ?? 0 }}
                            </span>
                        </td>
                        <td style="text-align:center">
                            <span class="badge badge-red">
                                {{ $assignment->billed_qty ?? 0 }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
    @else
    <div class="card" style="padding:30px;margin-bottom:32px;text-align:center;color:#6b7280">
        No Assignments added in this batch.
    </div>
    @endif



    {{-- ================= INVOICE + PAYMENT DETAILS ================= --}}
    @if($invoice)

    <div class="card" style="padding:24px;margin-bottom:32px">

        <div class="section-title" style="margin-bottom:20px">
            <i class="fa-solid fa-file-invoice-dollar" style="color:#22c55e"></i>
            Invoice & Payment Details
        </div>

        {{-- BASIC INVOICE INFO --}}
        <div class="grid" style="margin-bottom:20px">

            <div class="info-box">
                <div class="info-title">Invoice Number</div>
                <div class="info-value">{{ $invoice->invoice_number }}</div>
            </div>

            <div class="info-box">
                <div class="info-title">Invoice Date</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
                </div>
            </div>

            @php
            if($totalPaid <= 0){ $status='Pending' ; $color='#dc2626' ; $bg='#fee2e2' ; } elseif($remaining <=0){
                $status='Paid' ; $color='#166534' ; $bg='#dcfce7' ; } else { $status='Partial' ; $color='#92400e' ;
                $bg='#fef3c7' ; } @endphp <div class="info-box" style="background:{{ $bg }};">
                <div class="info-title">Invoice Status</div>
                <div class="info-value" style="color:{{ $color }}; font-weight:700;">
                    {{ $status }}
                </div>
        </div>
    </div>

    {{-- PAYMENT SUMMARY --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px;">

        <div style="background:#eef2ff; padding:14px; border-radius:12px;">
            <strong>Total Invoice</strong>
            <div style="font-size:18px; font-weight:700; color:#3730a3;">
                â‚¹{{ number_format($totalAmount,2) }}
            </div>
        </div>

        <div style="background:#dcfce7; padding:14px; border-radius:12px;">
            <strong>Total Paid</strong>
            <div style="font-size:18px; font-weight:700; color:#166534;">
                â‚¹{{ number_format($totalPaid,2) }}
            </div>
        </div>

        <div style="background:#fee2e2; padding:14px; border-radius:12px;">
            <strong>Remaining</strong>
            <div style="font-size:18px; font-weight:700; color:#991b1b;">
                â‚¹{{ number_format($remaining,2) }}
            </div>
        </div>

    </div>

    {{-- PAYMENT TABLE --}}
    <div style="overflow-x:auto;border-radius:14px;border:1px solid #e5e7eb">

        <table style="width:100%; border-collapse:collapse;">

            <thead style="background:linear-gradient(135deg,#6366f1,#ec4899);color:white;">
                <tr>
                    <th style="padding:10px;">#</th>
                    <th style="padding:10px;">Payment Advisory</th>
                    <th style="padding:10px;">Date</th>
                    <th style="padding:10px;">Account</th>
                    <th style="padding:10px;">Payment Type</th>
                    <th style="padding:10px;">Paid To This Invoice</th>
                </tr>
            </thead>

            <tbody>

                @forelse($invoice->payments as $index => $payment)

                <tr style="border-bottom:1px solid #e5e7eb">

                    <td style="padding:10px;">{{ $index + 1 }}</td>

                    <td style="padding:10px; font-weight:600; color:#4f46e5;">
                        {{ $payment->payment_advisory_number }}
                    </td>

                    <td style="padding:10px;">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
                    </td>

                    <td style="padding:10px;">
                        {{ $payment->payment_account }}
                    </td>

                    <td style="padding:10px;">
                        @php $type = $payment->pivot->payment_type; @endphp

                        @if($type == 'bill')
                        Bill Amount
                        @elseif($type == 'gst')
                        GST
                        @elseif($type == 'tds')
                        TDS
                        @else
                        -
                        @endif
                    </td>

                    <td style="padding:10px; font-weight:600; color:#16a34a;">
                        â‚¹{{ number_format($payment->pivot->amount,2) }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" style="padding:16px; text-align:center; color:#6b7280;">
                        No payments recorded for this invoice.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@else

<div class="card" style="padding:30px;margin-bottom:32px;text-align:center;color:#6b7280">
    No invoice created for this batch yet.
</div>

@endif

</div>

@endsection
