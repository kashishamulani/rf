@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-file-invoice" style="color:#6366f1;"></i> PO Master
    </h2>

    <a href="{{ route('po.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
              background:linear-gradient(135deg,#6366f1,#ec4899);
              color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> New PO
    </a>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="padding:10px 14px; background:#22c55e; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('success') }}
</div>
@endif

{{-- ERROR MESSAGE --}}
@if(session('error'))
<div style="padding:10px 14px; background:#ef4444; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('error') }}
</div>
@endif

{{-- TABLE --}}
<div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; background:white;
               border-radius:12px;
               box-shadow:0 4px 12px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb; text-align:left;">
                <th style="padding:12px;">#</th>
                <th style="padding:12px;">PO / WO Number</th>
                <th style="padding:12px;">Date</th>
                <th style="padding:12px;">Batches</th>
                <th style="padding:12px; text-align:center;">Details</th> {{-- NEW COLUMN --}}
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>


        <tbody>
            @forelse($pos as $index => $po)
            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                <td style="padding:12px;">{{ $index + 1 }}</td>
                <td style="padding:12px; font-weight:600; color:#111827;">{{ $po->po_no }}</td>
                <td style="padding:12px; color:#374151;">
                    {{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}
                </td>
                <td style="padding:12px; text-align:center;">
                    {{ $po->batches_count }}
                </td>
                {{-- DETAILS COLUMN --}}
                <td style="padding:12px; text-align:center;">
                    <a href="{{ route('po.show', $po->id) }}" title="View PO Details"
                        style="padding:6px 12px; background:#6366f1; color:white; border-radius:6px; text-decoration:none; font-size:13px;">
                        View
                    </a>
                </td>

                {{-- ACTIONS COLUMN --}}
                <td style="padding:12px; text-align:center; display:flex; gap:10px; justify-content:center;">

                    {{-- Edit --}}
                    <a href="{{ route('po.edit', $po->id) }}" title="Edit PO" style="color:#f59e0b;">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    {{-- Delete --}}
                    {{-- Delete --}}
                    @if($po->batches_count > 0)

                    <span title="Cannot delete: linked to batches" style="color:#9ca3af; cursor:not-allowed;">
                        <i class="fa-solid fa-trash"></i>
                    </span>

                    @else

                    <form method="POST" action="{{ route('po.destroy', $po->id) }}"
                        onsubmit="return confirm('Delete this PO?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit" title="Delete PO"
                            style="background:none;border:none;color:#ef4444;cursor:pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                    @endif

                    {{-- Add Item --}}
                    <a href="{{ route('po.po_items.create', $po->id)}}" title="Add Item" style="color:#10b981;">
                        <i class="fa-solid fa-circle-plus"></i>
                    </a>
                </td>
            </tr>


            @empty
            <tr>
                <td colspan="4" style="padding:14px; text-align:center; color:#9ca3af;">No PO records found</td>
            </tr>
            @endforelse
        </tbody>


    </table>
</div>

@endsection