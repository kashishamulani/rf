@extends('layouts.app')

@section('content')
<div style="max-width:1200px; margin:auto; padding:20px;">

    <h2 style="font-size:22px; font-weight:600; margin-bottom:16px;">
        Member Assignments & Activities
    </h2>

    <a href="{{ route('member.activities.index') }}"
        style="padding:8px 14px; background:#e5e7eb; border-radius:8px; text-decoration:none;">
        ← Back
    </a>

    <div style="margin-top:20px; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; background:#fff; border-radius:12px;">
            <thead>
                <tr style="border-bottom:2px solid #e5e7eb;">
                    <th style="padding:12px;">#</th>
                    <th style="padding:12px;">Member</th>
                    <th style="padding:12px;">Assignment</th>
                    <th style="padding:12px;">Activity</th>
                    <th style="padding:12px;">Start Date</th>
                    <th style="padding:12px;">Target / End Date</th>
                    <th style="padding:12px;">Status</th>
                    <th style="padding:12px;">Remark</th>
                    <th style="padding:12px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($assignments as $row)
                <tr style="border-bottom:1px solid #f1f5f9;">

                    <td style="padding:12px;">{{ $loop->iteration }}</td>

                    <td style="padding:12px;">
                        {{ $row->teamMember->name ?? '-' }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->assignment->assignment_name ?? '-' }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->activity->name ?? '-' }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->assigned_at ? \Carbon\Carbon::parse($row->assigned_at)->format('d M Y') : '-' }}
                    </td>

                    <td style="padding:12px;">
                        {{ $row->target_at ? \Carbon\Carbon::parse($row->target_at)->format('d M Y') : '-' }}
                    </td>

                    {{-- Status Form --}}
                    <td style="padding:12px;">
                        <form action="{{ route('member.activities.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="activity_assignment_id" value="{{ $row->id }}">

                            <select name="status"
                                style="padding:6px; border-radius:6px; border:1px solid #ddd;">
                                <option value="open" {{ optional($row->status)->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="pending" {{ optional($row->status)->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="close" {{ optional($row->status)->status == 'close' ? 'selected' : '' }}>Close</option>
                                <option value="cancel" {{ optional($row->status)->status == 'cancel' ? 'selected' : '' }}>Cancel</option>
                            </select>
                    </td>

                    <td style="padding:12px;">
                        <input type="text" name="remark"
                            value="{{ optional($row->status)->remark }}"
                            placeholder="Remark"
                            style="padding:6px; border-radius:6px; border:1px solid #ddd; width:150px;">
                    </td>

                    <td style="padding:12px;">
                        <button type="submit"
                            style="padding:6px 14px; background:#22c55e; color:#fff; border:none; border-radius:6px;">
                            Save
                        </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" style="padding:14px; text-align:center; color:#6b7280;">
                        No assignments found.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
@endsection
