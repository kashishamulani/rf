@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f0f4ff, #e6f7ff);
        font-family: 'Poppins', sans-serif;
        color: #1f2937;
    }

    .page-wrapper {
        max-width: 1000px;
        margin: auto;
        padding: 20px;
    }

    h2.page-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #1e3a8a;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #6366f1;
        text-decoration: none;
        border-radius: 999px;
        background: rgba(99,102,241,0.1);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .back-btn:hover {
        background: linear-gradient(135deg,#6366f1,#ec4899);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(99,102,241,0.35);
    }

    .member-card {
        margin-bottom: 30px;
        border-radius: 16px;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.08);
        padding: 20px;
        box-shadow: 0 14px 35px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .member-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 45px rgba(0,0,0,0.12);
    }

    .member-card h3 {
        margin-bottom: 12px;
        font-size: 18px;
        font-weight: 600;
        color: #0f172a;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-top: 10px;
    }

    thead {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    }

    thead th {
        padding: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #1e40af;
        text-align: left;
        border-bottom: 1px solid rgba(0,0,0,0.08);
        text-transform: uppercase;
    }

    tbody td {
        padding: 12px;
        color: #0f172a;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    tbody tr:hover {
        background: rgba(99, 102, 241, 0.08);
        transition: background 0.2s ease;
    }

    .no-assignments {
        color: #6b7280;
        margin-top: 8px;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        h2.page-title {
            font-size: 20px;
        }

        thead th, tbody td {
            padding: 8px;
            font-size: 13px;
        }

        .member-card {
            padding: 16px;
        }

        .back-btn {
            font-size: 13px;
            padding: 6px 12px;
        }
    }
</style>

<div class="page-wrapper">

    <h2 class="page-title">Team Members List</h2>

    <a href="{{ route('member.activities.index') }}" class="back-btn">
        ← Back to Activities
    </a>

    @foreach($members as $member)
    <div class="member-card">
        <h3>{{ $member->name }} ({{ $member->designation }})</h3>

        @if($member->assignments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Assignment</th>
                    <th>Activity</th>
                    <th>Start Date</th>
                    <th>Target Date</th>
                    <th>Status</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($member->assignments as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->assignment->assignment_name ?? '-' }}</td>
                    <td>{{ $row->activity->name ?? '-' }}</td>
                    <td>{{ $row->assigned_at?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $row->target_at?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $row->status->status ?? '-' }}</td>
                    <td>{{ $row->status->remark ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="no-assignments">No assignments found for this member.</p>
        @endif
    </div>
    @endforeach

</div>
@endsection
