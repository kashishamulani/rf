@extends('layouts.app')

@section('content')

<div style="max-width:1200px; margin:auto; background:white; padding:28px; border-radius:18px;">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:22px;">
        <h2 style="font-size:26px; font-weight:700; color:#4f46e5;">
            Assignment Progress
        </h2>

        <a href="{{ route('assignments.index') }}"
           style="padding:8px 14px; background:#e5e7eb; color:#4f46e5; border-radius:8px; text-decoration:none;">
            ← Back
        </a>
    </div>

    {{-- ASSIGNMENT SELECT --}}
    <div style="margin-bottom:24px;">
        <label style="font-weight:600;">Select Assignment</label>
        <form method="GET">
            <select name="assignment_id" onchange="this.form.submit()"
                style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; margin-top:6px;">
                <option value="">-- Select Assignment --</option>
                @foreach($assignments as $assignment)
                    <option value="{{ $assignment->id }}"
                        @selected(request('assignment_id') == $assignment->id)>
                        {{ $assignment->assignment_name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($selectedAssignment)

    {{-- BASIC INFO --}}
    <div style="margin-bottom:30px;">
        <h3 style="margin-bottom:12px;">Basic Information</h3>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px;">
            <div><strong>Name</strong><br>{{ $selectedAssignment->assignment_name }}</div>
            <div><strong>Date</strong><br>{{ \Carbon\Carbon::parse($selectedAssignment->date)->format('d M Y') }}</div>
            <div>
                <strong>Status</strong><br>
                <span style="padding:4px 12px; border-radius:20px; background:#22c55e; color:white;">
                    {{ $selectedAssignment->status }}
                </span>
            </div>

            <div><strong>State</strong><br>{{ $selectedAssignment->state }}</div>
            <div><strong>District</strong><br>{{ $selectedAssignment->district }}</div>
            <div><strong>Deadline</strong><br>{{ \Carbon\Carbon::parse($selectedAssignment->deadline)->format('d M Y') }}</div>
        </div>
    </div>

    {{-- ACTIVITY PROGRESS TABLE --}}
    <div style="margin-top:40px;">
        <h3 style="font-size:18px; font-weight:600; margin-bottom:12px;">
            Phase & Activity Progress
        </h3>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th style="padding:10px;">#</th>
                        <th style="padding:10px;">Phase</th>
                        <th style="padding:10px;">Activity</th>
                        <th style="padding:10px;">Assigned Date</th>
                        <th style="padding:10px;">Assigned To</th>
                        <th style="padding:10px;">Deadline</th>
                        <th style="padding:10px;">Completed</th>
                        <th style="padding:10px;">Status</th>
                        <th style="padding:10px;">Comment</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($activityAssignments as $item)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:10px;">{{ $loop->iteration }}</td>
                        <td style="padding:10px;">{{ $item->phase->phase_name ?? '-' }}</td>
                        <td style="padding:10px; font-weight:600;">
                            {{ $item->activity->name ?? '-' }}
                        </td>
                        <td style="padding:10px;">
                            {{ optional($item->assigned_at)->format('d M Y') }}
                        </td>
                        <td style="padding:10px;">
                            {{ $item->teamMember->name ?? '-' }}
                        </td>
                        <td style="padding:10px;">
                            {{ optional($item->target_at)->format('d M Y') }}
                        </td>
                        <td style="padding:10px;">
                            {{ optional($item->completed_at)->format('d M Y') ?? '-' }}
                        </td>
                        <td style="padding:10px;">
                            @php
                                $statusColors = [
                                    'Pending' => '#f59e0b',
                                    'In Progress' => '#0ea5e9',
                                    'Completed' => '#22c55e',
                                    'Cancelled' => '#ef4444',
                                ];
                            @endphp
                            <span style="
                                padding:4px 12px;
                                border-radius:20px;
                                color:white;
                                @php
    $status = $item->status->status ?? 'pending';
@endphp

background: {{ $statusColors[$status] ?? '#6b7280' }};
                            ">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td style="padding:10px;">
                            {{ $item->comment ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:14px; text-align:center; color:#6b7280;">
                            No activity data found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @endif

</div>

@endsection
