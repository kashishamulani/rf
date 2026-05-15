@extends('layouts.app')

@section('content')

<style>
    div::-webkit-scrollbar {
        height: 10px;
    }

    div::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
    }

    div::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
</style>

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

    <h2 style="font-size:22px; font-weight:600;">
        <i class="fa-solid fa-chart-column" style="color:#6366f1;"></i>
        Batch Phase Report
    </h2>

    <a href="{{ route('batch-phase-report.create') }}"
        style="
        display:flex;
        align-items:center;
        gap:8px;
        padding:10px 16px;
        background:linear-gradient(135deg,#6366f1,#ec4899);
        color:#fff;
        border-radius:10px;
        font-weight:600;
        text-decoration:none;
    ">
        <i class="fa-solid fa-circle-plus"></i>
        Create Report
    </a>

</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="
    padding:10px 14px;
    background:#22c55e;
    color:white;
    border-radius:8px;
    margin-bottom:12px;
">
    {{ session('success') }}
</div>
@endif

{{-- TABLE WRAPPER --}}
<div style="
    width:100%;
    max-width:100%;
    overflow-x:scroll;
    overflow-y:hidden;
    display:block;
    white-space:nowrap;
    border-radius:12px;
    background:white;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
">

    <table style="
        border-collapse:collapse;
        min-width:max-content;
        width:max-content;
        background:white;
    ">

        <thead>

            <tr style="background:#f3f4f6;">

                {{-- STICKY BATCH HEADER --}}
                <th style="
                    padding:14px;
                    border:1px solid #e5e7eb;
                    min-width:180px;
                    position:sticky;
                    left:0;
                    background:#f3f4f6;
                    z-index:5;
                    text-align:left;
                ">
                    Batch
                </th>

                @foreach($phases as $phase)

                <th style="
                    padding:14px;
                    border:1px solid #e5e7eb;
                    min-width:260px;
                    text-align:left;
                    font-size:14px;
                ">
                    {{ $phase->phase_name }}
                </th>

                @endforeach

            </tr>

        </thead>

        <tbody>

            @forelse($batches as $batch)

            <tr>

                {{-- STICKY BATCH COLUMN --}}
                <td style="
                    padding:14px;
                    border:1px solid #e5e7eb;
                    font-weight:700;
                    background:#fafafa;
                    position:sticky;
                    left:0;
                    z-index:4;
                    min-width:180px;
                ">
                    {{ $batch->batch_code }}
                </td>

                @foreach($phases as $phase)

                @php
                    $report = $batch->phaseReports
                        ->where('phase_id', $phase->id)
                        ->first();
                @endphp

                <td style="
                    padding:14px;
                    border:1px solid #e5e7eb;
                    vertical-align:top;
                    font-size:13px;
                    line-height:1.8;
                    min-width:260px;
                ">

                    <div style="margin-bottom:6px;">
                        <span style="font-weight:600; color:#6366f1;">
                            Status:
                        </span>
                        {{ $report->status ?? '-' }}
                    </div>

                    <div style="margin-bottom:6px;">
                        <span style="font-weight:600; color:#6366f1;">
                            Start:
                        </span>
                        {{ $report->start_date ?? '-' }}
                    </div>

                    <div style="margin-bottom:6px;">
                        <span style="font-weight:600; color:#6366f1;">
                            Expected:
                        </span>
                        {{ $report->expected_end_date ?? '-' }}
                    </div>

                    <div>
                        <span style="font-weight:600; color:#6366f1;">
                            End:
                        </span>
                        {{ $report->end_date ?? '-' }}
                    </div>

                </td>

                @endforeach

            </tr>

            @empty

            <tr>
                <td colspan="{{ count($phases) + 1 }}"
                    style="
                    padding:20px;
                    text-align:center;
                    color:#9ca3af;
                ">
                    No reports found
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection