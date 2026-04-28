@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:22px; font-weight:600; margin:0; color:#111827;">
            <i class="fa-solid fa-money-bill-wave" style="background:linear-gradient(135deg,#6366f1,#ec4899);
                      -webkit-background-clip:text;
                      -webkit-text-fill-color:transparent;"></i>
            All Payments
        </h2>

        <a href="{{ route('payments.create') }}" style="display:flex; align-items:center; gap:8px;
                  padding:10px 18px;
                  background:linear-gradient(135deg,#6366f1,#ec4899);
                  color:#fff; border-radius:12px;
                  font-weight:600; text-decoration:none;
                  box-shadow:0 8px 20px rgba(99,102,241,0.35);">
            <i class="fa-solid fa-circle-plus"></i> Add Payment
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);
                color:#065f46; padding:12px 16px;
                border-radius:12px; margin-bottom:18px;">
        {{ session('success') }}
    </div>
    @endif
{{-- FILTER SECTION --}}
<form method="GET" action="{{ route('payments.index') }}" 
      style="margin-bottom:18px;
             background:rgba(255,255,255,0.9);
             padding:16px;
             border-radius:14px;
             box-shadow:0 6px 18px rgba(0,0,0,0.06);">

    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">

        {{-- FROM DATE --}}
        <div>
            <label style="font-size:13px; font-weight:600;">From Date</label>
            <input type="date" name="from_date"
                   value="{{ request('from_date') }}"
                   style="width:100%; padding:8px; border-radius:8px; border:1px solid #ddd;">
        </div>

        {{-- TO DATE --}}
        <div>
            <label style="font-size:13px; font-weight:600;">To Date</label>
            <input type="date" name="to_date"
                   value="{{ request('to_date') }}"
                   style="width:100%; padding:8px; border-radius:8px; border:1px solid #ddd;">
        </div>

        {{-- ACCOUNT --}}
        <div>
            <label style="font-size:13px; font-weight:600;">Payment Account</label>
            <select name="account"
                    style="width:100%; padding:8px; border-radius:8px; border:1px solid #ddd;">
                <option value="">All</option>
                @foreach($accounts as $acc)
                    <option value="{{ $acc }}"
                        {{ request('account') == $acc ? 'selected' : '' }}>
                        {{ $acc }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- PAYMENT TYPE --}}
        <div>
            <label style="font-size:13px; font-weight:600;">Payment Type</label>
            <select name="type"
                    style="width:100%; padding:8px; border-radius:8px; border:1px solid #ddd;">
                <option value="">All</option>
                <option value="bill" {{ request('type')=='bill'?'selected':'' }}>Bill Amount</option>
                <option value="gst" {{ request('type')=='gst'?'selected':'' }}>GST</option>
                <option value="tds" {{ request('type')=='tds'?'selected':'' }}>TDS</option>
            </select>
        </div>

    </div>

    <div style="margin-top:12px;">
        <button type="submit"
            style="padding:8px 18px; background:#6366f1; color:#fff; border:none; border-radius:8px;">
            Filter
        </button>

        <a href="{{ route('payments.index') }}"
            style="margin-left:8px; padding:8px 18px; background:#e5e7eb; border-radius:8px; text-decoration:none;">
            Reset
        </a>
    </div>

</form>
   
    {{-- TABLE --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;
                  border-collapse:collapse;
                  background:rgba(255,255,255,0.8);
                  backdrop-filter:blur(16px);
                  border-radius:16px;
                  box-shadow:0 10px 30px rgba(0,0,0,0.08);">

            <thead>
                <tr style="background:linear-gradient(135deg,#eef2ff,#fdf2f8);
                       border-bottom:2px solid #e5e7eb;
                       text-align:left;">
                    <th style="padding:14px;">#</th>
                    <th style="padding:14px;">Advisory No</th>
                    <th style="padding:14px;">Date</th>
                    <th style="padding:14px;">Total Amount</th>
                    <th style="padding:14px;">Account</th>
                    <th style="padding:14px;">Invoices</th>
                    <th style="padding:14px;">Payment Type</th>
                    <th style="padding:14px;">Paid Details</th>
                    <th style="padding:14px; text-align:center;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $payment)
                <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                    <td style="padding:14px;">{{ $loop->iteration }}</td>

                    <td style="padding:14px; font-weight:600; color:#3730a3;">
                        {{ $payment->payment_advisory_number }}
                    </td>

                    <td style="padding:14px;">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                    </td>

                    <td style="padding:14px;">
                        ₹{{ number_format($payment->amount,2) }}
                    </td>

                    <td style="padding:14px;">
                        {{ $payment->payment_account }}
                    </td>

                    {{-- INVOICE LIST --}}
                    <td style="padding:14px;">
                        @forelse($payment->invoices as $inv)
                        <div style="margin-bottom:4px;">
                            <span style="font-weight:600;">
                                {{ $inv->invoice_number }}
                            </span>
                        </div>
                        @empty
                        <span style="color:#9ca3af;">-</span>
                        @endforelse
                    </td>

                    {{-- PAYMENT TYPE --}}
<td style="padding:14px;">
    @forelse($payment->invoices as $inv)

        @php
            $type = $inv->pivot->payment_type ?? '';

            $label = match($type) {
                'bill' => 'Bill Amount',
                'gst'  => 'GST',
                'tds'  => 'TDS',
                default => '-'
            };

            $color = match($type) {
                'bill' => '#2563eb',
                'gst'  => '#16a34a',
                'tds'  => '#dc2626',
                default => '#6b7280'
            };
        @endphp

        <div style="margin-bottom:4px;">
            <span style="font-weight:600; color:{{ $color }};">
                {{ $label }}
            </span>
        </div>

    @empty
        <span style="color:#9ca3af;">-</span>
    @endforelse
</td>

                    {{-- PAID AMOUNT PER INVOICE --}}
                    <td style="padding:14px;">
                        @forelse($payment->invoices as $inv)
                        <div style="margin-bottom:4px; color:#16a34a;">
                            ₹{{ number_format($inv->pivot->amount ?? 0,2) }}
                        </div>
                        @empty
                        <span style="color:#9ca3af;">-</span>
                        @endforelse
                    </td>

                    {{-- ACTIONS --}}
                    <td style="padding:14px; text-align:center; white-space:nowrap;">
                        <div style="display:inline-flex; gap:14px; align-items:center;">

                            {{-- View --}}
                            @if($payment->invoices->count())
                            <a href="{{ route('payments.show', $payment->id) }}" title="View" style="color:#10b981;">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @endif

                            {{-- Edit --}}
                            <a href="{{ route('payments.edit', $payment->id) }}" title="Edit" style="color:#f59e0b;">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this payment?')"
                                    style="background:none; border:none; color:#ef4444; cursor:pointer;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" style="padding:16px; text-align:center; color:#9ca3af;">
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // Check if page was reloaded (F5 / refresh button)
    if (performance.navigation.type === 1) {
        window.location.href = "{{ route('payments.index') }}";
    }

});
</script>
@endsection