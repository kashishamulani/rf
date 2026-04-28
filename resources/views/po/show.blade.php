@extends('layouts.app')

@section('content')

<style>
.container,.content,.wrapper{
    max-width:100%!important;
    padding:0!important;
    margin:0!important;
}
.page-wrap{
    width:100%;
    padding:10px 18px;
}
.card-box{
    background:#fff;
    border-radius:12px;
    padding:14px 18px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}
table td,table th{
    padding:8px 10px;
}
</style>

<div class="page-wrap">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
        <h2 style="font-size:20px; font-weight:700; color:#4f46e5;">
            PO Details
        </h2>

        <a href="{{ route('po.index') }}" style="
            padding:6px 14px;
            background:#e5e7eb;
            color:#374151;
            border-radius:6px;
            text-decoration:none;
            font-weight:600;">
            ← Back
        </a>
    </div>

    {{-- PO INFO --}}
    <div class="card-box" style="margin-bottom:12px;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <tr>
                <td style="font-weight:600; width:200px;">PO / WO Number:</td>
                <td>{{ $po->po_no }}</td>
            </tr>
            <tr>
                <td style="font-weight:600;">Date:</td>
                <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td style="font-weight:600;">Period:</td>
                <td>
                    {{ \Carbon\Carbon::parse($po->period_from)->format('d M Y') }}
                    →
                    {{ \Carbon\Carbon::parse($po->period_to)->format('d M Y') }}
                </td>
            </tr>
            <tr>
                <td style="font-weight:600;">GST:</td>
                <td>{{ $po->gst ?? 0 }}%</td>
            </tr>
        </table>
    </div>

    {{-- ADD ITEMS BUTTON --}}
    <div style="margin-bottom:10px;">
        <a href="{{ route('po.po_items.create', $po->id) }}"
           style="padding:8px 14px; background:#22c55e; color:#fff;
                  border-radius:8px; text-decoration:none; font-weight:600;">
            + Add Items
        </a>
    </div>

    {{-- ITEMS TABLE --}}
    <div class="card-box">
        <h3 style="font-size:16px; font-weight:700; margin-bottom:10px;">
            PO Items
        </h3>

        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f3f4f6; border-bottom:2px solid #e5e7eb;">
                    <th style="text-align:center;">#</th>
                    <th style="text-align:left;">Item</th>
                    <th style="text-align:right;">Rate</th>
                    <th style="text-align:right;">PO Qty</th>
                    <th style="text-align:right;">Used Qty</th>
                    <th style="text-align:right;">Remaining</th>
                    <th style="text-align:right;">Amount</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $grandTotal = 0;
                    $totalUsed = 0;
                    $totalRemaining = 0;
                @endphp

                @forelse($po->items as $index => $item)
                @php
                    $usedQty = (int) ($item->used_quantity ?? 0);
                    $remainingQty = max($item->quantity - $usedQty, 0);
                    $amount = $item->value * $item->quantity;

                    $grandTotal += $amount;
                    $totalUsed += $usedQty;
                    $totalRemaining += $remainingQty;
                @endphp

                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $item->item }}</td>
                    <td style="text-align:right;">₹{{ number_format($item->value,2) }}</td>
                    <td style="text-align:right;">{{ $item->quantity }}</td>

                    <td style="text-align:right; color:#2563eb; font-weight:600;">
                        {{ $usedQty }}
                    </td>

                    <td style="text-align:right; color:#16a34a; font-weight:700;">
                        {{ $remainingQty }}
                    </td>

                    <td style="text-align:right; font-weight:600;">
                        ₹{{ number_format($amount,2) }}
                    </td>

                    <td style="text-align:center; white-space:nowrap;">
                        <a href="{{ url('po/'.$po->id.'/po_items/'.$item->id.'/edit') }}"
                           style="padding:6px 10px; background:#f59e0b; color:white;
                                  border-radius:6px; text-decoration:none; font-size:12px;">
                            ✏️
                        </a>

                        <form action="{{ route('po.po_items.destroy', ['po'=>$po->id,'po_item'=>$item->id]) }}"
                              method="POST" style="display:inline-block;"
                              onsubmit="return confirm('Delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="padding:6px 10px; background:#ef4444; color:white;
                                       border-radius:6px; border:none; font-size:12px; cursor:pointer;">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" style="padding:14px; text-align:center; color:#9ca3af;">
                        No items added in this PO
                    </td>
                </tr>
                @endforelse
            </tbody>

            {{-- FOOTER TOTAL --}}
            @if($po->items->count())
            <tfoot>
                <tr style="background:#f9fafb; border-top:2px solid #e5e7eb;">
                    <td colspan="3" style="text-align:right; font-weight:700;">
                        TOTAL
                    </td>
                    <td style="text-align:right; font-weight:700;">
                        {{ $po->items->sum('quantity') }}
                    </td>
                    <td style="text-align:right; font-weight:700; color:#2563eb;">
                        {{ $totalUsed }}
                    </td>
                    <td style="text-align:right; font-weight:700; color:#16a34a;">
                        {{ $totalRemaining }}
                    </td>
                    <td style="text-align:right; font-weight:700;">
                        ₹{{ number_format($grandTotal,2) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif

        </table>
    </div>

</div>

@endsection