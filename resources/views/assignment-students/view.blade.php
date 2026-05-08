@extends('layouts.app')

@section('content')

<style>
    .data-wrapper {
        background: #fff;
        padding: 25px 10px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }

    .data-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 15px;
    }

    .data-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        background: linear-gradient(135deg, #6366f1, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #ec4899);
        color: #fff;
    }

    .btn-secondary {
        background: #6b7280;
        color: #fff;
    }

    .btn:hover {
        transform: translateY(-1px);
        opacity: 0.9;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .data-table tr {
        border-bottom: 1px solid #f1f5f9;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .data-table th {
        background: #f8fafc;
        padding: 14px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
    }

    .data-table td {
        padding: 14px;
        font-size: 14px;
    }

    .label {
        font-weight: 600;
        color: #374151;
        min-width: 200px;
    }

    .value {
        color: #6b7280;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Eye-catching Badge Styling */
    .badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        /* Pill shape */
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border: 1px solid transparent;
    }

    .badge-success {
        background: #dcfce7;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .badge-danger {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .badge-warning {
        background: #fef3c7;
        color: #b45309;
        border-color: #fde68a;
    }

    .badge-info {
        background: #ff0000;
        color: #ffffff;
        border-color: #bae6fd;


    }

    .badge-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    /* Title section alignment */
    .title-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Horizontal Compact Table Layout */
    .horizontal-container {
        width: 100%;
        overflow-x: auto;
        margin-top: 15px;
        padding-bottom: 10px;
    }

    .compact-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 12px 0;
        table-layout: fixed;
    }

    .compact-table th {
        padding: 12px 10px;
        border-radius: 10px 10px 0 0;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-bottom: none;
        width: 20%;
        vertical-align: top;
    }

    .compact-table td {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 10px 10px;
        padding: 12px;
        vertical-align: top;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .info-item {
        display: grid;
        grid-template-columns: 85px 1fr;
        gap: 8px;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        margin: 0 auto;
        font-size: 12px;
        color: #1e293b;
        font-weight: 600;
        word-break: break-all;
    }

    .compact-table i {
        display: block;
        font-size: 18px;
        margin-bottom: 5px;
        opacity: 0.8;
    }

    /* Status specific styling for table items */
    .info-value .badge {
        padding: 2px 8px;
        font-size: 10px;
    }

    @media (max-width: 1200px) {
        .horizontal-container {
            cursor: grab;
        }
    }
</style>

<div class="data-wrapper">

    <div class="data-header">
        <div>
            <div class="title-row">
                <h2>Student Assignment Data</h2>
                @if($data)
                @php
                $progress = $data->progress->name ?? null;
                $class = match ($progress) {
                'Completed' => 'badge-success',
                'In Progress' => 'badge-info',
                'Pending' => 'badge-warning',
                'Rejected' => 'badge-danger',
                default => 'badge-secondary'
                };
                @endphp
                <span class="badge {{ $class }}">
                    {{ $progress ?? 'Not Set' }}
                </span>
                @endif
            </div>

            <p style="margin: 5px 0 0 0; font-size: 14px; color: #6b7280;">
                {{ $student->name }} • {{ $assignment->assignment_name }}
            </p>
        </div>
        <div style="display:flex; gap:10px;">


            <a href="{{ route('assignment.students.form', [$assignment->id, $student->id]) }}" class="btn btn-primary">
                <i class="fa-solid fa-edit"></i> Edit Data
            </a>
            <a href="{{ route('assignments.registrations', $assignment->id) }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

        </div>
    </div>

    @if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="error-message">
        {{ session('error') }}
    </div>
    @endif

    @if(!$data)
    <div class="empty-state">
        <h3 style="color: #374151; margin-bottom: 10px;">No Data Available</h3>
        <p>This student doesn't have any assignment data yet.</p>
        <a href="{{ route('assignment.students.form', [$assignment->id, $student->id]) }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Data
        </a>
    </div>
    @else
    <div class="horizontal-container">
        <table class="compact-table">
            <thead>
                <tr>
                    <th style="background:#f3e8ff; color:#6b21a8;">
                        <i class="fa-solid fa-id-card"></i> Samarth Details
                    </th>
                    <th style="background:#fef9c3; color:#854d0e;">
                        <i class="fa-solid fa-file-invoice"></i> UAN Details
                    </th>
                    <th style="background:#e0f2fe; color:#0369a1;">
                        <i class="fa-solid fa-folder-open"></i> Document Details
                    </th>
                    <th style="background:#fdf2f8; color:#9d174d;">
                        <i class="fa-solid fa-user-pen"></i> Registration Details
                    </th>
                    <th style="background:#dcfce7; color:#065f46;">
                        <i class="fa-solid fa-briefcase"></i> Placement Details
                    </th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- Samarth Details -->
                    <td>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="badge {{ $data->samarth_done ? 'badge-success' : 'badge-danger' }}">
                                    {{ $data->samarth_done ? '✓ Completed' : '✗ Pending' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Samarth ID</span>
                            <span class="info-value">{{ $data->samarth_id ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Certificate</span>
                            <span class="info-value">
                                @if($data->samarth_certificate)
                                <a href="{{ asset('uploads/samarth/' . $data->samarth_certificate) }}" target="_blank"
                                    style="color:#22c55e;text-decoration:none;font-weight:600; font-size: 11px;">
                                    <i class="fa-solid fa-eye" style="display:inline; font-size:11px;"></i> View File
                                </a>
                                @else
                                -
                                @endif
                            </span>
                        </div>
                    </td>

                    <!-- UAN Details -->
                    <td>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="badge {{ $data->uan_done ? 'badge-success' : 'badge-danger' }}">
                                    {{ $data->uan_done ? '✓ Completed' : '✗ Pending' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">UAN Number</span>
                            <span class="info-value">{{ $data->uan_number ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Certificate</span>
                            <span class="info-value">
                                @if($data->uan_certificate)
                                <a href="{{ asset('uploads/uan/' . $data->uan_certificate) }}" target="_blank"
                                    style="color:#22c55e;text-decoration:none;font-weight:600; font-size: 11px;">
                                    <i class="fa-solid fa-eye" style="display:inline; font-size:11px;"></i> View File
                                </a>
                                @else
                                -
                                @endif
                            </span>
                        </div>
                    </td>

                    <!-- Document Details -->
                    <td>
                        <div class="info-item">
                            <span class="info-label">Documents Sub.</span>
                            <span class="info-value">
                                <span class="badge {{ $data->documents_done ? 'badge-success' : 'badge-danger' }}">
                                    {{ $data->documents_done ? '✓ Yes' : '✗ No' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Offer Letter</span>
                            <span class="info-value">
                                <span class="badge {{ $data->offer_letter_done ? 'badge-success' : 'badge-danger' }}">
                                    {{ $data->offer_letter_done ? '✓ Yes' : '✗ No' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Offer Letter Date</span>
                            <span class="info-value">
                                {{ $data->offer_letter_date ? \Carbon\Carbon::parse($data->offer_letter_date)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">File</span>
                            <span class="info-value">
                                @if($data->offer_letter_file)
                                <a href="{{ asset('uploads/offer_letters/' . $data->offer_letter_file) }}"
                                    target="_blank"
                                    style="color:#22c55e;text-decoration:none;font-weight:600; font-size: 11px;">
                                    <i class="fa-solid fa-eye" style="display:inline; font-size:11px;"></i> View File
                                </a>
                                @else
                                -
                                @endif
                            </span>
                        </div>
                    </td>

                    <!-- Registration Details -->
                    <td>
                        <div class="info-item">
                            <span class="info-label">Reg ID / Password</span>
                            <span class="info-value">
                                {{ $data->registration_id ?? '-' }} / {{ $data->registration_password ?? '-' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Reg. Number</span>
                               <span class="info-value">{{ $data->registration_number ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">EC Num / Date</span>
                            <span class="info-value">
                                {{ $data->ec_number ?? '-' }}
                                <br>
                                <small
                                    style="color:#94a3b8">{{ $data->ec_date ? \Carbon\Carbon::parse($data->ec_date)->format('d M Y') : '' }}</small>
                            </span>
                        </div>
                    </td>

                    <!-- Placement Details -->
                    <td>
                        <div class="info-item">
                            <span class="info-label">Date of Placement</span>
                            <span class="info-value">
                                {{ $data->date_of_placement ? \Carbon\Carbon::parse($data->date_of_placement)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Company</span>
                            <span class="info-value"
                                style="line-height:1.2">{{ $data->placement_company ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Offering</span>
                            <span class="info-value">{{ $data->placement_offering ?? '-' }}</span>
                        </div>
                    </td>


                </tr>
            </tbody>
        </table>
    </div>
    @endif

</div>

@endsection