@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-layer-group" style="color:#6366f1;"></i> Phase Master
    </h2>

    <a href="{{ route('phase.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
              background:linear-gradient(135deg,#6366f1,#ec4899);
              color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> Add Phase
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

                <th style="padding:12px;">Phase Order</th>
                <th style="padding:12px;">Phase Name</th>
                <th style="padding:12px; text-align:center;">Activities</th>
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($phases as $index => $phase)
            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                <td style="padding:12px;">{{ $index + 1 }}</td>



                <td style="padding:12px; color:#374151;">
                    {{ $phase->phase_order }}
                </td>
                <td style="padding:12px; font-weight:600; color:#111827;">
                    {{ $phase->phase_name }}
                </td>
                <td style="text-align:center;">
                    {{ $phase->activities_count }}
                </td>

                {{-- ACTIONS --}}
                <td style="padding:12px; text-align:center; display:flex; gap:10px; justify-content:center;">

                    {{-- EDIT --}}
                    <a href="{{ route('phase.edit', $phase->id) }}" title="Edit Phase" style="color:#f59e0b;">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    {{-- DELETE --}}
                    @if($phase->activities_count > 0)

                    {{-- Disabled Delete with Hover Message --}}
                    <span title="Cannot delete — this phase is used in activities">
                        <button disabled style="
                background:none;
                border:none;
                color:#9ca3af;
                cursor:not-allowed;
            ">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </span>

                    @else

                    {{-- Active Delete --}}
                    <form method="POST" action="{{ route('phase.destroy', $phase->id) }}"
                        onsubmit="return confirm('Delete this phase?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Delete Phase"
                            style="background:none; border:none; color:#ef4444; cursor:pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                    @endif

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:14px; text-align:center; color:#9ca3af;">
                    No phase records found
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

@endsection