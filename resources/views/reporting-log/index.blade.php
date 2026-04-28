@extends('layouts.app')

@section('content')



<style>
@media print {
    body {
        background: #fff !important;
    }

    #filterForm,
    button {
        display: none !important;
    }

    table {
        font-size: 12px;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    th,
    td {
        padding: 8px !important;
    }
}
</style>


<div class="p-6" id="printArea">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:22px; font-weight:600; margin:0;">
            📊 Reporting Log
        </h2>

        <button onclick="printReport()" style="padding:10px 16px;
                  background:linear-gradient(135deg,#2563eb,#1e40af);
                  color:#fff; border-radius:10px;
                  font-weight:600; border:none;">
            🖨 Print
        </button>
    </div>

    {{-- FILTERS --}}
    <form method="GET" id="filterForm" action="{{ route('reporting.log') }}" style="margin-bottom:20px;
                 display:grid;
                 grid-template-columns:1fr 1fr 1fr;
                 gap:14px;
                 background:rgba(255,255,255,0.75);
                 backdrop-filter:blur(14px);
                 padding:16px;
                 border-radius:14px;">

        {{-- Member --}}
        <div>
            <label style="font-size:13px; color:#374151;">Team Member</label>
            <select name="member_id" onchange="submitFilter()" style="width:100%; padding:10px;
                           border-radius:10px;
                           border:1px solid #e5e7eb;">
                <option value="">All Members</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                    {{ $member->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- From Date --}}
        <div>
            <label style="font-size:13px; color:#374151;">From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" oninput="submitFilter()" style="width:100%; padding:10px;
                          border-radius:10px;
                          border:1px solid #e5e7eb;">
        </div>

        {{-- To Date --}}
        <div>
            <label style="font-size:13px; color:#374151;">To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" oninput="submitFilter()" style="width:100%; padding:10px;
                          border-radius:10px;
                          border:1px solid #e5e7eb;">
        </div>
    </form>

    {{-- TABLE --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;
                  border-collapse:collapse;
                  background:#ffffff;
                  border-radius:14px;
                  box-shadow:0 6px 20px rgba(0,0,0,0.06);">

            <thead>
                <tr style="background:#f9fafb;
                       border-bottom:2px solid #e5e7eb;
                       text-align:left;">
                    <th style="padding:12px;">#</th>
                    <th style="padding:12px;">Member</th>
                    <th style="padding:12px;">Assignment</th>
                    <th style="padding:12px;">Activity</th>
                    <th style="padding:12px;">Start</th>
                    <th style="padding:12px;">Target</th>
                    <th style="padding:12px;">Status</th>
                    <th style="padding:12px;">Remark</th>
                </tr>
            </thead>

            <tbody>
                @forelse($assignments as $row)
                <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                    <td style="padding:12px;">
                        {{ $loop->iteration }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->teamMember->name ?? '-' }}
                    </td>

                    <td style="padding:12px; font-weight:600;">
                        {{ $row->assignment->assignment_name ?? '-' }}
                    </td>

                    <td style="padding:12px; color:#6b7280;">
                        {{ $row->activity->name ?? '-' }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->assigned_at?->format('d M Y') ?? '-' }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->target_at?->format('d M Y') ?? '-' }}
                    </td>

                    <td style="padding:12px;">
                        {{ ucfirst($row->status->status ?? 'open') }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->status->remark ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:16px; text-align:center; color:#6b7280;">
                        No assignments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function submitFilter() {
    document.getElementById('filterForm').submit();
}

function printReport() {
    let printContents = document.getElementById('printArea').innerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>

<style>
@media print {

    form,
    button,
    a {
        display: none !important;
    }

    table {
        font-size: 12px;
    }
}
</style>
@endsection