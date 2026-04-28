@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:22px; font-weight:600; margin:0; color:#111827;">
            <i class="fa-solid fa-list-check" style="background:linear-gradient(135deg,#6366f1,#ec4899);
               -webkit-background-clip:text;
               -webkit-text-fill-color:transparent;"></i>
            Assignments Overview
        </h2>

        {{-- CREATE BUTTON --}}
        <a href="{{ route('activity-assignments.create') }}" style="display:flex; align-items:center; gap:8px;
           padding:10px 18px;
           background:linear-gradient(135deg,#6366f1,#ec4899);
           color:#fff;
           border-radius:12px;
           font-weight:600;
           text-decoration:none;
           box-shadow:0 8px 20px rgba(99,102,241,0.35);">
            <i class="fa-solid fa-circle-plus"></i>
            Create Assignment
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);
                    color:#065f46;
                    padding:12px 16px;
                    border-radius:12px;
                    margin-bottom:18px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- INFO MESSAGE IF NO ASSIGNMENTS --}}
    @if($assignments->isEmpty())
    <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);
                    color:#92400e;
                    padding:12px 16px;
                    border-radius:12px;
                    margin-bottom:18px;">
        <i class="fa-solid fa-info-circle"></i>
        No assignments with tasks found. Create an assignment and assign phases/activities to see them here.
    </div>
    @endif

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
                           border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px;">#</th>
                    <th style="padding:14px;">Assignment Name</th>
                    <th style="padding:14px;">Total Tasks</th>
                    <th style="padding:14px;">Phases</th>
                    <th style="padding:14px;">Activities</th>
                    <th style="padding:14px;">Team Members</th>
                    <th style="padding:14px; text-align:center;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($assignments as $assignment)
                <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">
                    <td style="padding:14px;">
                        {{ $loop->iteration }}
                    </td>

                    <td style="padding:14px; font-weight:600; color:#3730a3;">
                        {{ $assignment->assignment_name }}
                    </td>

                    <td style="padding:14px;">
                        <span style="background:linear-gradient(135deg,#e0e7ff,#fce7f3);
                                     color:#3730a3;
                                     padding:4px 12px;
                                     border-radius:999px;
                                     font-size:12px;">
                            {{ $assignment->total_assignments ?? $assignment->activity_assignments_count }}
                        </span>
                    </td>

                    <td style="padding:14px;">
                        <span style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);
                                     color:#1e40af;
                                     padding:4px 12px;
                                     border-radius:999px;
                                     font-size:12px;">
                            {{ $assignment->phase_count ?? $assignment->activityAssignments()->where('type', 'phase')->count() }}
                        </span>
                    </td>

                    <td style="padding:14px;">
                        <span style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);
                                     color:#166534;
                                     padding:4px 12px;
                                     border-radius:999px;
                                     font-size:12px;">
                            {{ $assignment->activity_count ?? $assignment->activityAssignments()->where('type', 'activity')->count() }}
                        </span>
                    </td>

                    <td style="padding:14px;">
                        <span style="background:linear-gradient(135deg,#f3e8ff,#e9d5ff);
                                     color:#6b21a8;
                                     padding:4px 12px;
                                     border-radius:999px;
                                     font-size:12px;">
                            {{ $assignment->member_count ?? $assignment->activityAssignments()->distinct('team_member_id')->count('team_member_id') }} Members
                        </span>
                    </td>

                  <td style="padding:14px; text-align:center;">

    <!-- View -->
    <a href="{{ route('activity-assignments.show', $assignment->id) }}"
       style="color:#3b82f6; font-size:16px; margin-right:10px;"
       title="View Details">
        <i class="fa-solid fa-eye"></i>
    </a>

    <!-- Edit -->
    <a href="{{ route('activity-assignments.edit', $assignment->id) }}"
       style="color:#eab308; font-size:16px; margin-right:10px;"
       title="Edit">
        <i class="fa-solid fa-edit"></i>
    </a>

    <!-- Delete -->
    <form action="{{ route('activity-assignments.destroy', $assignment->id) }}"
          method="POST"
          style="display:inline-block;"
          onsubmit="return confirm('Are you sure you want to delete this assignment?');">

        @csrf
        @method('DELETE')

        <button type="submit"
                style="border:none; background:none; color:#ef4444; font-size:16px;"
                title="Delete">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>

</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:16px; text-align:center; color:#9ca3af;">
                        No Assignments with tasks found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

   

</div>
@endsection