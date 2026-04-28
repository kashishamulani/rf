@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-users" style="color:#6366f1;"></i> Team Members
    </h2>

    <a href="{{ route('team-members.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
              background:linear-gradient(135deg,#6366f1,#ec4899);
              color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> Add Member
    </a>
</div>




{{-- ================= FILTER SECTION ================= --}}
<form id="filterForm" method="GET" style="background:white; padding:16px; border-radius:12px;
             margin-bottom:18px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">

    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:14px;">

        {{-- Search --}}
        <div>
            <label>Name / Email / Mobile</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                onkeyup="autoSubmit()" style="width:100%; padding:8px;
                          border:1px solid #ddd; border-radius:6px;">
        </div>

        {{-- Designation --}}
        <div>
            <label>Designation</label>
            <input type="text" name="designation" value="{{ request('designation') }}" onchange="autoSubmit()" style="width:100%; padding:8px;
                          border:1px solid #ddd; border-radius:6px;">
        </div>

        {{-- Status --}}
        <div>
            <label>Status</label>
            <select name="status" onchange="autoSubmit()" style="width:100%; padding:8px;
                           border:1px solid #ddd; border-radius:6px;">
                <option value="">All</option>
                <option value="1" @selected(request('status')==='1' )>Active</option>
                <option value="0" @selected(request('status')==='0' )>Inactive</option>
            </select>
        </div>

        {{-- From Date --}}
        <div>
            <label>From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" onchange="autoSubmit()" style="width:100%; padding:8px;
                          border:1px solid #ddd; border-radius:6px;">
        </div>

        {{-- To Date --}}
        <div>
            <label>To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" onchange="autoSubmit()" style="width:100%; padding:8px;
                          border:1px solid #ddd; border-radius:6px;">
        </div>

    </div>
</form>

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
                <th style="padding:12px;">Name</th>
                <th style="padding:12px;">Designation</th>
                <th style="padding:12px;">Email</th>
                <th style="padding:12px;">Mobile</th>
                <th style="padding:12px;">Status</th>
                <th style="padding:12px; text-align:center;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($members as $index => $member)
            <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                <td style="padding:12px;">{{ $index + 1 }}</td>

                <td style="padding:12px; font-weight:600; color:#111827;">
                    {{ $member->name }}
                </td>

                <td style="padding:12px; color:#374151;">
                    {{ $member->designation }}
                </td>

                <td style="padding:12px; color:#6b7280;">
                    {{ $member->email }}
                </td>

                <td style="padding:12px; color:#374151;">
                    {{ $member->mobile }}
                </td>

                {{-- STATUS BADGE --}}
                <td style="padding:12px;">
                    @php
                    $statusColor = $member->status ? '#22c55e' : '#ef4444';
                    @endphp
                    <span style="padding:4px 12px; border-radius:999px;
                                 background:{{ $statusColor }};
                                 color:white; font-size:13px;">
                        {{ $member->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>

                {{-- ACTIONS --}}
                <td style="padding:12px; text-align:center; display:flex; gap:10px; justify-content:center;">

                    {{-- EDIT --}}
                    <a href="{{ route('team-members.edit', $member->id) }}" title="Edit Member" style="color:#f59e0b;">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    {{-- DELETE --}}
                    @if($member->assignments_count > 0)

                    {{-- Disabled Delete --}}
                    <span title="Cannot delete — member is assigned to activities">
                        <button disabled style="background:none;border:none;color:#9ca3af;cursor:not-allowed;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </span>

                    @else

                    @php
                    $hasAssignments = $member->assignments_count > 0;
                    @endphp

                    <form method="POST" action="{{ route('team-members.destroy', $member->id) }}"
                        onsubmit="return confirm('Delete this member?');" style="margin:0;">

                        @csrf
                        @method('DELETE')

                        <button type="submit" {{ $hasAssignments ? 'disabled' : '' }}
                            title="{{ $hasAssignments ? 'Cannot delete — assigned to activities' : 'Delete Member' }}"
                            style="
            background:none;
            border:none;
            cursor: {{ $hasAssignments ? 'not-allowed' : 'pointer' }};
            color: {{ $hasAssignments ? '#9ca3af' : '#ef4444' }};
            opacity: {{ $hasAssignments ? '0.6' : '1' }};
        ">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                    </form>

                    @endif

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7" style="padding:14px; text-align:center; color:#9ca3af;">
                    No team members found
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>




<div id="deleteTooltip" style="display:none;
            position:fixed;
            background:#111827;
            color:#fff;
            padding:6px 10px;
            font-size:12px;
            border-radius:6px;
            z-index:999;">
    Member assigned to activities
</div>

<script>
function showDeleteMsg(e) {
    const tooltip = document.getElementById('deleteTooltip');
    tooltip.style.display = 'block';
    tooltip.style.top = (e.pageY + 10) + 'px';
    tooltip.style.left = (e.pageX + 10) + 'px';
}

function hideDeleteMsg() {
    document.getElementById('deleteTooltip').style.display = 'none';
}
</script>
<script>
function autoSubmit() {
    document.getElementById('filterForm').submit();
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // If page is loaded with query parameters, clear them on refresh
    if (window.location.search.length > 0) {
        const url = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, url);
    }
});
</script>

@endsection