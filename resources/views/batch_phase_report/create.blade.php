@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">

    <div style="width:100%; max-width:1200px; display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('batch-phase-report.index') }}"
            style="
            padding:10px 18px;
            background:#e5e7eb;
            color:#374151;
            font-weight:600;
            border-radius:10px;
            text-decoration:none;
        ">
            ← Back
        </a>
    </div>

    <form action="{{ route('batch-phase-report.store') }}"
        method="POST"
        style="
        width:100%;
        max-width:1200px;
        background:white;
        padding:30px;
        border-radius:16px;
        box-shadow:0 10px 30px rgba(0,0,0,0.05);
    ">
        @csrf

        <h2 style="
            font-size:26px;
            font-weight:700;
            margin-bottom:20px;
            text-align:center;
            color:#4f46e5;
        ">
            Batch Phase Report
        </h2>

        <div style="margin-bottom:20px;">
            <label style="font-weight:600;">Select Batch</label>

            <select name="batch_id"
                required
                style="
                width:100%;
                padding:12px;
                border-radius:8px;
                border:1px solid #d1d5db;
            ">
                <option value="">Select Batch</option>

                @foreach($batches as $batch)
                <option value="{{ $batch->id }}">
                    {{ $batch->batch_code }}
                </option>
                @endforeach
            </select>
        </div>

        <div style="overflow-x:auto;">

            <table style="
                width:100%;
                border-collapse:collapse;
                background:white;
            ">

                <thead>

                    <tr style="background:#f3f4f6;">

                        <th style="padding:12px; border:1px solid #e5e7eb;">
                            Phase
                        </th>

                        <th style="padding:12px; border:1px solid #e5e7eb;">
                            Status
                        </th>

                        <th style="padding:12px; border:1px solid #e5e7eb;">
                            Start Date
                        </th>

                        <th style="padding:12px; border:1px solid #e5e7eb;">
                            Expected End
                        </th>

                        <th style="padding:12px; border:1px solid #e5e7eb;">
                            End Date
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($phases as $phase)

                    <tr>

                        <td style="padding:12px; border:1px solid #e5e7eb; font-weight:600;">
                            {{ $phase->phase_name }}
                        </td>

                        <td style="padding:12px; border:1px solid #e5e7eb;">
                            <select
                                name="phase_data[{{ $phase->id }}][status]"
                                style="
                                width:100%;
                                padding:10px;
                                border-radius:8px;
                                border:1px solid #d1d5db;
                            ">
                                <option value="">Select</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </td>

                        <td style="padding:12px; border:1px solid #e5e7eb;">
                            <input type="date"
                                name="phase_data[{{ $phase->id }}][start_date]"
                                style="
                                width:100%;
                                padding:10px;
                                border-radius:8px;
                                border:1px solid #d1d5db;
                            ">
                        </td>

                        <td style="padding:12px; border:1px solid #e5e7eb;">
                            <input type="date"
                                name="phase_data[{{ $phase->id }}][expected_end_date]"
                                style="
                                width:100%;
                                padding:10px;
                                border-radius:8px;
                                border:1px solid #d1d5db;
                            ">
                        </td>

                        <td style="padding:12px; border:1px solid #e5e7eb;">
                            <input type="date"
                                name="phase_data[{{ $phase->id }}][end_date]"
                                style="
                                width:100%;
                                padding:10px;
                                border-radius:8px;
                                border:1px solid #d1d5db;
                            ">
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div style="margin-top:25px; text-align:center;">
            <button type="submit"
                style="
                padding:14px 24px;
                background:linear-gradient(135deg,#6366f1,#ec4899);
                color:white;
                border:none;
                border-radius:12px;
                font-weight:600;
                cursor:pointer;
            ">
                Save Report
            </button>
        </div>

    </form>

</div>

@endsection