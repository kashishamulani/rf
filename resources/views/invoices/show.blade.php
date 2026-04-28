@extends('layouts.app')

@section('content')

<style>
.form-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    padding: 24px;
    width: 100%;
    max-width: 1100px;
}

.grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 16px;
}

.label {
    font-weight: 600;
    color: #4338ca;
    font-size: 14px;
}

.value {
    font-size: 14px;
}

.po-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 14px;
}

.po-table thead {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: white;
}

.po-table th {
    padding: 10px;
    font-size: 13px;
}

.po-table td {
    padding: 9px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 13px;
}

.table-title {
    font-weight: 600;
    color: #4338ca;
    margin-top: 24px;
}
.btn-back{
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    padding: 6px 10px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.btn-back:hover{
    color: #111827;
    background: #f3f4f6;
}
</style>

<div style="padding:12px; display:flex; justify-content:center;">

    <div class="form-card">

        {{-- HEADER --}}
        <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
            <h2 style="background:linear-gradient(135deg,#6366f1,#ec4899);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;">
                Invoice Details
            </h2>

            <a href="{{ route('invoices.index') }}" class="btn-back">← Back</a>
        </div>

        {{-- BASIC DETAILS --}}
        <div class="grid-3">

            <div>
                <div class="label">Invoice Number</div>
                <div class="value">{{ $invoice->invoice_number }}</div>
            </div>

            <div>
                <div class="label">Invoice Date</div>
                <div class="value">{{ $invoice->invoice_date }}</div>
            </div>

            <div>
                <div class="label">Batch Code</div>
                <div class="value">{{ $invoice->batch->batch_code ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Batch Size</div>
                <div class="value">{{ $invoice->batch->batch_size ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Total Amount</div>
                <div class="value">₹ {{ number_format($invoice->total_amount,2) }}</div>
            </div>

        </div>

        @if($invoice->payment_detail)
        <div style="margin-top:10px;">
            <div class="label">Payment Detail</div>
            <div class="value">{{ $invoice->payment_detail }}</div>
        </div>
        @endif


        {{-- ================= ASSIGNMENT ITEMS ================= --}}

        <div class="table-title">Assignment Billing</div>

        <table class="po-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Assignment</th>
                    <th>Requirement</th>
                    <th>Billed Qty</th>
                </tr>
            </thead>

            <tbody>
                @forelse($invoice->assignmentItems as $i => $item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->assignment->assignment_name ?? '-' }}</td>
                    <td>{{ $item->assignment->requirement ?? '-' }}</td>
                    <td>{{ $item->quantity ?? 0 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;">No Assignment Items</td>
                </tr>
                @endforelse
            </tbody>
        </table>


        {{-- ================= PO ITEMS ================= --}}

        <div class="table-title">PO Item Billing</div>

        <table class="po-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Item</th>
                    <th>Rate</th>
                    <th>Billed Qty</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>

                @forelse($invoice->poItems as $i => $item)

                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->poItem->item ?? '-' }}</td>
                    <td>₹ {{ $item->poItem->value ?? 0 }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>₹ {{ number_format(($item->qty * ($item->poItem->value ?? 0)),2) }}</td>
                </tr>

                @empty

                <tr>
                    <td colspan="5" style="text-align:center;">No PO Items</td>
                </tr>

                @endforelse

            </tbody>
        </table>


        {{-- ================= PAYMENT DETAILS ================= --}}

        <div class="table-title">Payment Details</div>

        {{-- SUMMARY --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:16px;">

            <div style="background:#eef2ff; padding:14px; border-radius:12px;">
                <strong>Total Invoice</strong>
                <div style="font-size:18px; font-weight:700; color:#3730a3;">
                    ₹{{ number_format($totalAmount,2) }}
                </div>
            </div>

            <div style="background:#dcfce7; padding:14px; border-radius:12px;">
                <strong>Total Paid</strong>
                <div style="font-size:18px; font-weight:700; color:#166534;">
                    ₹{{ number_format($totalPaid,2) }}
                </div>
            </div>

            <div style="background:#fee2e2; padding:14px; border-radius:12px;">
                <strong>Remaining</strong>
                <div style="font-size:18px; font-weight:700; color:#991b1b;">
                    ₹{{ number_format($remaining,2) }}
                </div>
            </div>

        </div>


        <table class="po-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Payment Advisory</th>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Payment Type</th>
                    <th>Paid To This Invoice</th>
                </tr>
            </thead>

            <tbody>

                @forelse($invoice->payments as $index => $payment)

                <tr>

                    <td>{{ $index + 1 }}</td>

                    <td style="font-weight:600; color:#4f46e5;">
                        {{ $payment->payment_advisory_number }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                    </td>

                    <td>
                        {{ $payment->payment_account }}
                    </td>

                    <td>
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

                    <td style="font-weight:600;">
                        ₹{{ number_format($payment->pivot->amount,2) }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" style="text-align:center;">
                        No payments recorded for this invoice.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>


    </div>
</div>

@endsection