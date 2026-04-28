@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="font-size:22px; font-weight:600; margin:0; color:#111827;">
                <i class="fa-solid fa-eye" style="background:linear-gradient(135deg,#6366f1,#ec4899);
                   -webkit-background-clip:text;
                   -webkit-text-fill-color:transparent;"></i>
                Assignment Details: {{ $assignment->assignment_name }}
            </h2>
            <p style="color:#6b7280; margin-top:5px;">
                Created: {{ $assignment->created_at->format('d M Y') }}
            </p>
        </div>

        <div style="display:flex; gap:10px;">
            <!-- <a href="{{ route('activity-assignments.edit', $assignment->id) }}" style="display:flex; align-items:center; gap:8px;
               padding:10px 18px;
               background:linear-gradient(135deg,#eab308,#f59e0b);
               color:#fff;
               border-radius:12px;
               font-weight:600;
               text-decoration:none;">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a> -->
            <a href="{{ route('activity-assignments.index') }}" style="display:flex; align-items:center; gap:8px;
               padding:10px 18px;
               background:#6b7280;
               color:#fff;
               border-radius:12px;
               font-weight:600;
               text-decoration:none;">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>




    {{-- PHASES AND ACTIVITIES TABLE --}}
    <div style="overflow-x:auto;">
        @forelse($groupedAssignments as $phaseId => $data)
        {{-- Phase Header --}}
        <div style="margin-top: {{ !$loop->first ? '20px' : '0' }};">
            <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:linear-gradient(135deg,#eef2ff,#f5f3ff);
        padding:14px 18px;
        border-radius:14px;
        border:1px solid #e5e7eb;
        box-shadow:0 4px 10px rgba(0,0,0,0.05);
    ">
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="
                width:10px;
                height:34px;
                border-radius:6px;
                background:linear-gradient(135deg,#6366f1,#8b5cf6);
                display:inline-block;"></span>

                    <div>
                        <div style="font-size:17px; font-weight:600; color:#1f2937;">
                            {{ $data['phase']->phase_name ?? 'Phase '.$phaseId }}
                        </div>

                        @if(!empty($data['phase_assignment']))
                        <div style="font-size:12px; color:#6b7280;">
                            Whole phase assigned
                        </div>
                        @endif
                    </div>
                </div>

                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    @if(!empty($data['phase_assignment']))
                    <span style="
                    background:#e0e7ff;
                    color:#3730a3;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:600;">
                        WHOLE PHASE
                    </span>
                    @endif

                    <span style="
                background:#e5e7eb;
                color:#4b5563;
                padding:4px 10px;
                border-radius:999px;
                font-size:12px;">
                        {{ count($data['phase_assignment'] ?? []) + count($data['activities']) }} items
                    </span>
                </div>
            </div>
        </div>
        <table style="width:100%;border-collapse:separate;border-spacing:0;background:rgba(255,255,255,0.85);backdrop-filter:blur(14px);border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.06);margin-top:6px;margin-bottom:14px;overflow:hidden;">

            <thead>
                <tr style="background:linear-gradient(135deg,#eef2ff,#fdf2f8);
                               border-bottom:2px solid #e5e7eb;">
                    <th style="padding:12px; width:60px;">S.No</th>
                    <th style="padding:12px;">Activity</th>
                    <th style="padding:12px;">Team Member(s)</th>
                    <th style="padding:12px;">Start Date</th>
                    <th style="padding:12px;">Days</th>
                    <th style="padding:12px;">Target Date</th>
                    <!-- <th style="padding:12px;">Status</th> -->
                </tr>
            </thead>

            <tbody>
                @php $serialNo = 1; @endphp
                {{-- WHOLE PHASE ASSIGNMENT ROW --}}
                @if(!empty($data['phase_assignment']))
                @php
                $phaseAssignments = $data['phase_assignment'];
                $memberCount = count($phaseAssignments);

                $today = \Carbon\Carbon::today();
                $overdue = 0;
                $dueSoon = 0;
                $onTrack = 0;

                foreach($phaseAssignments as $pa) {
                if ($pa->target_date) {
                $target = \Carbon\Carbon::parse($pa->target_date);
                if ($today > $target) $overdue++;
                elseif ($today->diffInDays($target) <= 3) $dueSoon++; else $onTrack++; } } @endphp <tr style="background:#f9fafb;
border-bottom:1px solid #e5e7eb;
height:54px;
vertical-align:middle;">

                    {{-- Serial --}}
                    <td style="padding:14px; text-align:center;">
                        {{ $serialNo++ }}
                    </td>

                    {{-- Label --}}
                    <td style="padding:12px 14px;">
                        <div style="font-weight:600; color:#374151;">
                            Whole Phase Work
                        </div>
                        <div style="font-size:12px; color:#9ca3af;">
                            Applies to entire phase
                        </div>
                    </td>

                    {{-- Members --}}
                    <td style="padding:14px;">
                        <div style="display:flex; flex-direction:column; gap:6px;">
                            @foreach($phaseAssignments as $pa)
                            <span style="
                    background:#eef2ff;
                    color:#3730a3;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:12px;
                    width:fit-content;">
                                {{ $pa->teamMember->name ?? 'N/A' }}
                            </span>
                            @endforeach
                        </div>
                    </td>

                    {{-- Start --}}
                    <td style="padding:14px; color:#6b7280;">
                        {{ $memberCount == 1 && $phaseAssignments[0]->start_date
            ? \Carbon\Carbon::parse($phaseAssignments[0]->start_date)->format('d M Y')
            : '—' }}
                    </td>

                    {{-- Days --}}
                    <td style="padding:14px; text-align:center; color:#6b7280;">
                        {{ $memberCount == 1 ? ($phaseAssignments[0]->days ?? '—') : '—' }}
                    </td>

                    {{-- Target --}}
                    <td style="padding:14px; color:#6b7280;">
                        {{ $memberCount == 1 && $phaseAssignments[0]->target_date
            ? \Carbon\Carbon::parse($phaseAssignments[0]->target_date)->format('d M Y')
            : 'Entire Phase' }}
                    </td>

                    {{-- Status --}}
                    <!-- <td style="padding:14px;">
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            @if($overdue > 0)
                            <span
                                style="background:#ef444420; color:#ef4444; padding:3px 8px; border-radius:999px; font-size:11px;">
                                {{ $overdue }} Overdue
                            </span>
                            @endif

                            @if($dueSoon > 0)
                            <span
                                style="background:#f59e0b20; color:#f59e0b; padding:3px 8px; border-radius:999px; font-size:11px;">
                                {{ $dueSoon }} Due Soon
                            </span>
                            @endif

                            @if($onTrack > 0)
                            <span
                                style="background:#10b98120; color:#10b981; padding:3px 8px; border-radius:999px; font-size:11px;">
                                {{ $onTrack }} On Track
                            </span>
                            @endif

                            @if($overdue==0 && $dueSoon==0 && $onTrack==0)
                            <span style="color:#9ca3af; font-size:12px;">Pending</span>
                            @endif
                        </div>
                    </td> -->

                    </tr>
                    @endif

                    {{-- ACTIVITY ROWS --}}
                    @foreach($data['activities'] as $activity)
                    @php
                    $activityName = $activity->activity->name ?? 'N/A';
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;
height:54px;
vertical-align:middle;">
                        <td style="padding:12px; text-align:center;">{{ $serialNo++ }}</td>
                        <!-- <td style="padding:12px; color:#6b7280;">
                            {{ $data['phase']->phase_name ?? 'N/A' }}
                        </td> -->
                        <td style="padding:12px;">
                            {{ $activityName }}
                        </td>
                        <td style="padding:12px;">
                            <span style="background:linear-gradient(135deg,#f3e8ff,#e9d5ff);
                                             color:#6b21a8;
                                             padding:4px 12px;
                                             border-radius:999px;
                                             font-size:12px;
                                             display:inline-block;">
                                {{ $activity->teamMember->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td style="padding:12px;">
                            {{ $activity->start_date ? \Carbon\Carbon::parse($activity->start_date)->format('d M Y') : 'N/A' }}
                        </td>
                        <td style="padding:12px; text-align:center;">
                            {{ $activity->days ?? '0' }}
                        </td>
                        <td style="padding:12px;">
                            {{ $activity->target_date ? \Carbon\Carbon::parse($activity->target_date)->format('d M Y') : 'N/A' }}
                        </td>
                        <!-- <td style="padding:12px;">
                            @php
                            $today = \Carbon\Carbon::today();
                            $target = $activity->target_date ? \Carbon\Carbon::parse($activity->target_date) : null;
                            $status = 'pending';
                            $color = '#9ca3af';

                            if ($target) {
                            if ($today > $target) {
                            $status = 'overdue';
                            $color = '#ef4444';
                            } elseif ($today->diffInDays($target) <= 3) { $status='due soon' ; $color='#f59e0b' ; } else
                                { $status='on track' ; $color='#10b981' ; } } @endphp <span style="background-color:{{ $color }}20;
                                             color:{{ $color }};
                                             padding:4px 12px;
                                             border-radius:999px;
                                             font-size:12px;
                                             text-transform:capitalize;
                                             display:inline-block;">
                                {{ $status }}
                                </span>
                        </td> -->
                    </tr>
                    @endforeach

                    {{-- If no assignments for this phase --}}
                    @if(empty($data['phase_assignment']) && count($data['activities']) == 0)
                    <tr>
                        <td colspan="8" style="padding:16px; text-align:center; color:#9ca3af;">
                            No assignments for this phase.
                        </td>
                    </tr>
                    @endif
            </tbody>
        </table>
        @empty
        <div style="background:#f9fafb; padding:40px; text-align:center; border-radius:16px;">
            <i class="fa-solid fa-clipboard-list" style="font-size:48px; color:#9ca3af; margin-bottom:16px;"></i>
            <h3 style="color:#374151; margin-bottom:8px;">No Assignments Found</h3>
            <p style="color:#6b7280;">No phases or activities have been assigned for this assignment yet.</p>
            <a href="{{ route('activity-assignments.edit', $assignment->id) }}" style="display:inline-block; margin-top:16px; padding:10px 20px; 
                          background:linear-gradient(135deg,#6366f1,#ec4899);
                          color:#fff; border-radius:8px; text-decoration:none;">
                <i class="fa-solid fa-plus"></i> Add Assignments
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection