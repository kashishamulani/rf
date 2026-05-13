@extends('layouts.app') {{-- Includes navbar & sidebar --}}

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:22px; font-weight:600; margin:0; color:#111827;">
            <i class="fa-solid fa-layer-group" style="background:linear-gradient(135deg,#6366f1,#ec4899);
                      -webkit-background-clip:text;
                      -webkit-text-fill-color:transparent;"></i>
            All Batches
        </h2>

        <a href="{{ route('batches.create') }}" style="display:flex; align-items:center; gap:8px;
                  padding:10px 18px;
                  background:linear-gradient(135deg,#6366f1,#ec4899);
                  color:#fff; border-radius:12px;
                  font-weight:600; text-decoration:none;
                  box-shadow:0 8px 20px rgba(99,102,241,0.35);">
            <i class="fa-solid fa-circle-plus"></i> Add Batch
        </a>
    </div>

    {{-- MESSAGES --}}
    @if(session('success'))
    <div style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);
                    color:#065f46; padding:12px 16px;
                    border-radius:12px; margin-bottom:18px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:linear-gradient(135deg,#ef4444,#dc2626);
                    color:#fff; padding:12px 16px;
                    border-radius:12px; margin-bottom:18px;">
        {{ session('error') }}
    </div>
    @endif



    {{-- FILTERS --}}
    <div style="background:white;
            padding:18px;
            border-radius:16px;
            margin-bottom:20px;
            box-shadow:0 6px 20px rgba(0,0,0,0.06);">

        <form method="GET" action="{{ route('batches.index') }}">

            <div style="display:grid;
                    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
                    gap:16px;
                    align-items:end;">

                {{-- Batch Code --}}
                <div>
                    <label style="font-weight:600; margin-bottom:6px; display:block;">
                        Batch Code
                    </label>

                    <input type="text" name="batch_code" value="{{ request('batch_code') }}"
                        placeholder="Search batch code" style="width:100%;
                              padding:10px 14px;
                              border:1px solid #d1d5db;
                              border-radius:10px;">
                </div>

                {{-- Assignment --}}
                <div>
                    <label style="font-weight:600; margin-bottom:6px; display:block;">
                        Assignment
                    </label>

                    <select name="assignment_id" style="width:100%;
                               padding:10px 14px;
                               border:1px solid #d1d5db;
                               border-radius:10px;">

                        <option value="">All Assignments</option>

                        @foreach($assignments as $assignment)
                        <option value="{{ $assignment->id }}"
                            {{ request('assignment_id') == $assignment->id ? 'selected' : '' }}>
                            {{ $assignment->assignment_name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                {{-- From Date --}}
                <div>
                    <label style="font-weight:600; margin-bottom:6px; display:block;">
                        From Date
                    </label>

                    <input type="date" name="from_date" value="{{ request('from_date') }}" style="width:100%;
                  padding:10px 14px;
                  border:1px solid #d1d5db;
                  border-radius:10px;">
                </div>

                {{-- To Date --}}
                <div>
                    <label style="font-weight:600; margin-bottom:6px; display:block;">
                        To Date
                    </label>

                    <input type="date" name="to_date" value="{{ request('to_date') }}" style="width:100%;
                  padding:10px 14px;
                  border:1px solid #d1d5db;
                  border-radius:10px;">
                </div>

                {{-- Buttons --}}
                <div style="display:flex; gap:10px;">

                    <button type="submit" style="padding:10px 18px;
                               border:none;
                               border-radius:10px;
                               background:linear-gradient(135deg,#6366f1,#8b5cf6);
                               color:white;
                               font-weight:600;
                               cursor:pointer;">

                        <i class="fa-solid fa-magnifying-glass"></i>
                        Filter
                    </button>

                    <a href="{{ route('batches.index') }}" style="padding:10px 18px;
                          border-radius:10px;
                          background:#f3f4f6;
                          color:#111827;
                          text-decoration:none;
                          font-weight:600;">

                        Reset
                    </a>

                </div>

            </div>

        </form>
    </div>

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
                    <th style="padding:14px;">Batch Code</th>
                    <th style="padding:14px;">Location</th>

                    {{-- ✅ ADD THIS --}}
                    <th style="padding:14px;">Batch Size</th>

                    <th style="padding:14px;">Assignments</th>
                    <th style="padding:14px;">Status</th>
                    <th style="padding:14px;">Created</th>
                    <th style="padding:14px;">Candidates</th>
                    <th style="padding:14px; text-align:center;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($batches as $batch)
                <tr style="border-bottom:1px solid #f1f5f9; vertical-align:middle;">

                    <td style="padding:14px;">{{ $loop->iteration }}</td>

                    <td style="padding:14px; font-weight:600; color:#3730a3;">
                        {{ $batch->batch_code }}
                    </td>

                    <td style="padding:14px; color:#6b7280; max-width:200px;">
                        <strong>{{ $batch->district }}</strong><br>
                        <small>{{ \Illuminate\Support\Str::limit($batch->address,40) }}</small>
                    </td>

                    <td style="padding:14px; text-align:center;">
                        <span style="background:linear-gradient(135deg,#dbeafe,#e0e7ff);
                 color:#1e40af;
                 padding:4px 12px;
                 border-radius:999px;
                 font-size:12px;
                 font-weight:600;">
                            {{ $batch->batch_size ?? 0 }}
                        </span>
                    </td>


                    {{-- ASSIGNMENTS --}}
                    <!-- <td style="padding:14px;">
                        @if($batch->assignments->count())
                        @foreach($batch->assignments as $a)
                        <span style="background:linear-gradient(135deg,#e0e7ff,#fce7f3);color:#3730a3;padding:4px 10px;border-radius:999px;font-size:12px;margin:2px;display:inline-flex;align-items:center;gap:6px;">

                            {{ $a->assignment_name }}

                            <span style="background:#6366f1;color:white;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;">
                                {{ $a->pivot->build ?? 0 }}
                            </span>

                        </span>
                        @endforeach
                        @else
                        <span style="color:#9ca3af;">—</span>
                        @endif
                    </td> -->

                    {{-- ASSIGNMENTS --}}
                    <td style="padding:14px;">
                        @if($batch->assignments->count())
                        @foreach($batch->assignments as $a)
                        @php
                        $manualBuild = $a->pivot->build ?? 0;
                        $actualCount = $a->actual_in_batch ?? 0;
                        $display = $actualCount > 0 ? $actualCount : $manualBuild;
                        @endphp

                        <span
                            style="background:linear-gradient(135deg,#e0e7ff,#fce7f3);color:#3730a3;padding:4px 10px;border-radius:999px;font-size:12px;margin:2px;display:inline-flex;align-items:center;gap:6px;">

                            {{ $a->assignment_name }}

                            <span style="background:{{ $actualCount > 0 ? '#16a34a' : '#6366f1' }};
                         color:white;
                         padding:2px 8px;
                         border-radius:999px;
                         font-size:11px;
                         font-weight:600;"
                                title="{{ $actualCount > 0 ? 'Actual candidates in batch' : 'Manual build entry' }}">
                                {{ $display }}
                            </span>

                        </span>
                        @endforeach
                        @else
                        <span style="color:#9ca3af;">—</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td style="padding:14px;">
                        <form action="{{ route('batches.status', $batch->id) }}" method="POST"
                            style="display:flex; gap:8px; align-items:center;">
                            @csrf
                            @method('PATCH')

                            <select name="status" style="padding:6px 14px;
                                           border-radius:999px;
                                           border:1px solid #6366f1;
                                           background:#fff;
                                           font-size:13px;">
                                <option value="Open" @selected($batch->status=='Open')>Open</option>
                                <option value="Closed" @selected($batch->status=='Closed')>Closed</option>
                                <option value="Cancelled" @selected($batch->status=='Cancelled')>Cancelled</option>
                                <option value="On Hold" @selected($batch->status=='On Hold')>On Hold</option>
                                <option value="Billed" @selected($batch->status=='Billed')>Billed</option>
                            </select>

                            <button type="submit" style="background:linear-gradient(135deg,#22c55e,#16a34a);
                                           color:white;
                                           padding:6px 14px;
                                           border-radius:999px;
                                           border:none;
                                           font-size:13px;
                                           cursor:pointer;">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                    </td>

                    <td style="padding:14px;">
                        {{ $batch->created_at->format('d M Y') }}
                    </td>

                    {{-- CANDIDATES --}}
                    <td style="padding:14px;">
                        <div style="display:flex; gap:12px; align-items:center;">
                            <a href="{{ route('batches.view', $batch->id) }}" title="View Candidates"
                                style="color:#6366f1; font-size:16px;">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <!-- <a href="{{ route('batches.candidates.create', $batch->id) }}" title="Add Candidate"
                                style="color:#22c55e; font-size:16px;">
                                <i class="fa-solid fa-user-plus"></i>
                            </a> -->
                        </div>
                    </td>




                    {{-- ACTIONS --}}
                    <td style="padding:14px; text-align:center; white-space:nowrap;">
                        <div style="display:inline-flex; gap:16px; justify-content:center; align-items:center;">

                            {{-- View --}}
                            <a href="{{ route('batches.show', $batch->id) }}" title="View" style="color:#3b82f6;">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('batches.edit', $batch->id) }}" title="Edit" style="color:#f59e0b;">
                                <i class="fa-solid fa-pen"></i>
                            </a>


                            {{-- Completion PDF --}}
                            @if($batch->invoice)
                            <a href="{{ route('invoices.full.pdf', $batch->invoice->id) }}" target="_blank"
                                title="Completion PDF" style="color:#ef4444;">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>
                            @else
                            <span title="Invoice not created yet" style="color:#d1d5db; cursor:not-allowed;">
                                <i class="fa-solid fa-file-pdf"></i>
                            </span>
                            @endif
                            </a>
                            {{-- Delete --}}
                            <form action="{{ route('batches.destroy', $batch->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this batch?')"
                                    style="background:none; border:none; color:#ef4444; cursor:pointer;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="10" style="padding:16px; text-align:center; color:#9ca3af;">
                        No batches found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    {{-- Candidates Modal --}}
    <div class="modal fade" id="candidatesModal" tabindex="-1" aria-labelledby="candidatesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="candidatesModalLabel">Registered Candidates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Assignment</th>
                                <th>Registered At</th>
                            </tr>
                        </thead>
                        <tbody id="candidatesTableBody">
                            {{-- AJAX content goes here --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection