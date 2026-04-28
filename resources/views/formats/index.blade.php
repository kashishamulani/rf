@extends('layouts.app')

@section('content')
<style>
.tooltip-lock{
    position:relative;
    color:#9ca3af;
    cursor:not-allowed;
    display:inline-flex;
    align-items:center;
}

/* Tooltip box */
.tooltip-lock .tooltip-text{
    visibility:hidden;
    opacity:0;
    position:absolute;
    bottom:140%;
    left:50%;
    transform:translateX(-50%);
    background:#111827;
    color:#fff;
    font-size:12px;
    padding:6px 10px;
    border-radius:6px;
    white-space:nowrap;
    transition:0.2s ease;
}

/* Tooltip arrow */
.tooltip-lock .tooltip-text::after{
    content:"";
    position:absolute;
    top:100%;
    left:50%;
    transform:translateX(-50%);
    border-width:5px;
    border-style:solid;
    border-color:#111827 transparent transparent transparent;
}

/* Show tooltip on hover */
.tooltip-lock:hover .tooltip-text{
    visibility:visible;
    opacity:1;
}
</style>
{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-layer-group" style="color:#6366f1;"></i> Formats
    </h2>

    <a href="{{ route('formats.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
              background:linear-gradient(135deg,#6366f1,#ec4899);
              color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> Add Format
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
                <th style="padding:12px;">Format Type</th>
                <th style="padding:12px;">Created At</th>
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($formats as $index => $format)
            @php
            $isUsed = $format->assignments()->exists();
            @endphp

            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">
                <td style="padding:12px;">{{ $index + 1 }}</td>

                <td style="padding:12px; font-weight:600; color:#111827;">
                    {{ $format->type }}
                </td>

                <td style="padding:12px; color:#6b7280;">
                    {{ $format->created_at->format('d M Y') }}
                </td>

                {{-- ACTIONS --}}
                <td style="padding:12px; text-align:center; display:flex; gap:10px; justify-content:center;">

                    {{-- EDIT --}}
                    <a href="{{ route('formats.edit', $format->id) }}" style="color:#f59e0b;" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    {{-- DELETE --}}
                    @if($isUsed)
                    <span class="tooltip-lock">
                        <i class="fa-solid fa-lock"></i>
                        <span class="tooltip-text">Format already in use</span>
                    </span>
                    @else
                    <form method="POST" action="{{ route('formats.destroy', $format->id) }}"
                        onsubmit="return confirm('Delete this format?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer;"
                            title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="4" style="padding:14px; text-align:center; color:#9ca3af;">
                    No formats found
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

@endsection