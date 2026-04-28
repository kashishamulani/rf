@extends('layouts.app')

@section('content')

<div style="max-width:1200px; margin:auto; background:white; padding:28px; border-radius:18px;">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:22px;">
        <h2 style="font-size:26px; font-weight:700; color:#4f46e5;">
            Payment Details
        </h2>

        <div style="display:flex; gap:10px;">
            <a href="{{ route('payments.index') }}"
               style="padding:8px 14px; background:#e5e7eb; color:#4f46e5;
                      border-radius:8px; text-decoration:none;">
                ← Back
            </a>

            <a href="{{ route('payments.edit', $payment->id) }}"
               style="padding:8px 14px; background:#f59e0b; color:white;
                      border-radius:8px; text-decoration:none;">
                Edit
            </a>
        </div>
    </div>

    {{-- ================= BASIC INFO ================= --}}
    <div style="margin-bottom:28px;">
        <h3 style="margin-bottom:12px;">Payment Information</h3>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
            <div>
                <strong>Advisory Number</strong><br>
                {{ $payment->payment_advisory_number }}
            </div>

            <div>
                <strong>Payment Date</strong><br>
                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
            </div>

            <div>
                <strong>Total Amount</strong><br>
                <span style="font-size:18px; font-weight:700; color:#16a34a;">
                    ₹{{ number_format($payment->amount,2) }}
                </span>
            </div>

            <div>
                <strong>Payment Account</strong><br>
                {{ $payment->payment_account }}
            </div>

            <div>
                <strong>Created At</strong><br>
                {{ $payment->created_at->format('d M Y h:i A') }}
            </div>

            <div>
                <strong>Updated At</strong><br>
                {{ $payment->updated_at->format('d M Y h:i A') }}
            </div>
        </div>
    </div>

    {{-- ================= INVOICE BREAKDOWN ================= --}}
    <div style="margin-top:40px;">
        <h3 style="font-size:18px; font-weight:600; margin-bottom:12px;">
            Invoice Breakdown
        </h3>

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:10px;">#</th>
                    <th style="padding:10px;">Invoice Number</th>
                    <th style="padding:10px;">Invoice Amount</th>
                    <th style="padding:10px;">Paid From This Payment</th>
                    <th style="padding:10px;">View Invoice</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payment->invoices as $index => $invoice)
                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="padding:10px;">{{ $index + 1 }}</td>

                    <td style="padding:10px; font-weight:600; color:#4f46e5;">
                        {{ $invoice->invoice_number }}
                    </td>

                    <td style="padding:10px;">
                        ₹{{ number_format($invoice->total_amount ?? 0,2) }}
                    </td>

                    <td style="padding:10px; font-weight:600; color:#16a34a;">
                        ₹{{ number_format($invoice->pivot->amount ?? 0,2) }}
                    </td>

                    <td style="padding:10px;">
                        <a href="{{ route('invoices.payments', $invoice->id) }}"
                           style="color:#0ea5e9; font-weight:600;">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5"
                        style="text-align:center; padding:14px; color:#6b7280;">
                        No invoices linked to this payment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
