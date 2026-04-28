@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:22px; font-weight:600; margin:0;">
            <i class="fa-solid fa-user-check" style="color:#6366f1;"></i>
            Member Activity & Assignment
        </h2>

        <a href="{{ route('member.activities.members') }}" style="display:flex; align-items:center; gap:8px;
                  padding:10px 16px;
                  background:linear-gradient(135deg,#0f172a,#334155);
                  color:#fff; border-radius:10px;
                  font-weight:600; text-decoration:none;">
            <i class="fa-solid fa-box-archive"></i>
            Closed Assignments
        </a>
    </div>

    {{-- FILTERS --}}
    <form method="GET" id="filterForm" action="{{ route('member.activities.index') }}" style="margin-bottom:20px;
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
    @if(isset($assignments))
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
                    <th style="padding:12px;">Assignment</th>
                    <th style="padding:12px;">Activity</th>
                    <th style="padding:12px;">Start</th>
                    <th style="padding:12px;">Target</th>
                    <th style="padding:12px;">Status</th>
                    <th style="padding:12px;">Remark</th>
                    <th style="padding:12px; text-align:center;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($assignments as $row)
                <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                    <form action="{{ route('member.activities.update', $row->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <td style="padding:12px;">
                            {{ $loop->iteration }}
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

                        {{-- STATUS --}}
                        <td style="padding:12px;">
                            @php $status = $row->status->status ?? 'open'; @endphp
                            <select name="status" style="padding:8px 10px;
                                   border-radius:8px;
                                   border:1px solid #e5e7eb;
                                   background:#f8fafc;">
                                <option value="open" {{ $status=='open'?'selected':'' }}>🟢 Open</option>
                                <option value="pending" {{ $status=='pending'?'selected':'' }}>🟡 Pending</option>
                                <option value="close" {{ $status=='close'?'selected':'' }}>🟦 Close</option>
                                <option value="cancel" {{ $status=='cancel'?'selected':'' }}>🔴 Cancel</option>
                            </select>
                        </td>

                        {{-- REMARK --}}
                        <td style="padding:12px;">
                            <textarea name="remark" rows="2" placeholder="Add remark…" style="width:220px;
                                     padding:8px 10px;
                                     border-radius:10px;
                                     border:1px solid #e5e7eb;
                                     resize:vertical;">{{ $row->status->remark ?? '' }}</textarea>
                        </td>

                        {{-- ACTION --}}
                        <td style="padding:12px; text-align:center;">
                            <button type="submit" style="padding:10px 16px;
                               background:linear-gradient(135deg,#22c55e,#16a34a);
                               color:#fff;
                               border:none;
                               border-radius:10px;
                               font-weight:600;
                               cursor:pointer;">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </td>
                    </form>
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
    @endif

</div>

{{-- SCRIPTS --}}
<script>
function submitFilter() {
    document.getElementById('filterForm').submit();
}

// Clear filters on hard refresh
if (performance.getEntriesByType("navigation")[0].type === "reload") {
    window.location.href = "{{ route('member.activities.index') }}";
}
</script>
@endsection