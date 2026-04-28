@extends('layouts.app')

@section('content')

<div style="max-width:1200px; margin:auto; background:white; padding:28px; border-radius:18px;">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:22px;">
        <h2 style="font-size:26px; font-weight:700; color:#4f46e5;">
            Invoice Payments – {{ $invoice->invoice_number }}
        </h2>

        <a href="{{ route('invoices.index') }}" style="padding:8px 14px; background:#e5e7eb; color:#4f46e5;
                  border-radius:8px; text-decoration:none;">
            ← Back
        </a>
    </div>


    {{-- ================= SUMMARY ================= --}}
    <div style="margin-bottom:28px;">
        <h3 style="margin-bottom:12px;">Summary</h3>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
            <div style="background:#eef2ff; padding:18px; border-radius:14px;">
                <strong>Total Invoice</strong>
                <div style="font-size:22px; font-weight:700; color:#3730a3;">
                    ₹{{ number_format($totalAmount,2) }}
                </div>
            </div>

            <div style="background:#dcfce7; padding:18px; border-radius:14px;">
                <strong>Total Paid</strong>
                <div style="font-size:22px; font-weight:700; color:#166534;">
                    ₹{{ number_format($totalPaid,2) }}
                </div>
            </div>

            <div style="background:#fee2e2; padding:18px; border-radius:14px;">
                <strong>Remaining</strong>
                <div style="font-size:22px; font-weight:700; color:#991b1b;">
                    ₹{{ number_format($remaining,2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- ================= PAYMENT HISTORY ================= --}}
   <div style="margin-top:40px;">
    <h3 style="font-size:18px; font-weight:600; margin-bottom:12px;">
        Payment History
    </h3>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f3f4f6;">
                <th style="padding:10px;">#</th>
                <th style="padding:10px;">Payment Advisory</th>
                <th style="padding:10px;">Date</th>
                <th style="padding:10px;">Account</th>
                <th style="padding:10px;">Payment Type</th> <!-- NEW -->
                <th style="padding:10px; text-align:right;">Paid To This Invoice</th>
            </tr>
        </thead>

        <tbody>
            @forelse($invoice->payments as $index => $payment)
            <tr style="border-bottom:1px solid #e5e7eb;">
                <td style="padding:10px;">{{ $index + 1 }}</td>

                <td style="padding:10px; font-weight:600; color:#4f46e5;">
                    {{ $payment->payment_advisory_number }}
                </td>

                <td style="padding:10px;">
                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                </td>

                <td style="padding:10px;">
                    {{ $payment->payment_account }}
                </td>

                <!-- NEW COLUMN -->
                <td style="padding:10px;">
                    @php
                        $type = $payment->pivot->payment_type;
                    @endphp

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

                <td style="padding:10px; font-weight:600; text-align:right;">
                    ₹{{ number_format($payment->pivot->amount,2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:14px; color:#6b7280;">
                    No payments recorded for this invoice.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>

@endsection