@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')


<style>
/* INPUTS */
.f-input {
    width: 100%;
    height: 36px;
    padding: 6px 10px;
    font-size: 13px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: #fff;
    color: #111827;
}

/* FIX SELECT TEXT ISSUE */
select,
option {
    color: #111827 !important;
    background: #fff !important;
}

/* BUTTONS */
.btn-primary {
    padding: 6px 14px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
}

.btn-light {
    padding: 6px 14px;
    background: #e5e7eb;
    color: #111;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}

/* FILTER GRID */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 10px;
}

/* TABLE */
.table-wrap {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
}

th,
td {
    padding: 8px 6px;
    font-size: 12px;
    white-space: nowrap;
}

/* FIX WIDTHS */
th:nth-child(1) {
    width: 30px;
}

th:nth-child(2) {
    width: 80px;
}

th:nth-child(3) {
    width: 120px;
}

th:nth-child(4) {
    width: 100px;
}

th:nth-child(5) {
    width: 50px;
}

th:nth-child(6) {
    width: 60px;
}

th:nth-child(7) {
    width: 60px;
}

th:nth-child(8) {
    width: 60px;
}

th:nth-child(9) {
    width: 50px;
}

th:nth-child(10) {
    width: 60px;
}

th:nth-child(11) {
    width: 60px;
}

th:nth-child(12) {
    width: 50px;
}

th:nth-child(13) {
    width: 50px;
}

/* MID SCREEN COMPACT */
@media (max-width: 1280px) {

    th,
    td {
        padding: 4px !important;
        font-size: 11px !important;
    }

    th:nth-child(1) {
        width: 20px;
    }

    th:nth-child(2) {
        width: 60px;
    }

    th:nth-child(3) {
        width: 120px;
    }

    th:nth-child(4) {
        width: 80px;
    }

    th:nth-child(5) {
        width: 40px;
    }

    th:nth-child(6) {
        width: 40px;
    }

    th:nth-child(7) {
        width: 40px;
    }

    th:nth-child(8) {
        width: 40px;
    }

    th:nth-child(9) {
        width: 40px;
    }

    th:nth-child(10) {
        width: 60px;
    }

    th:nth-child(11) {
        width: 60px;
    }

    th:nth-child(12) {
        width: 30px;
    }

    th:nth-child(13) {
        width: 50px;
    }

    .f-input {
        height: 30px;
        padding: 4px 6px;
        font-size: 12px;
    }
}

/* MOBILE */
@media (max-width:768px) {
    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    table {
        min-width: 100%;
    }

    .f-input {
        font-size: 12px;
    }
}
</style>
{{-- ================= HEADER ================= --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; font-weight:700; color:#111827;">
        <i class="fa-solid fa-list-check" style="color:#6366f1;"></i> Assignments
    </h2>

    <a href="{{ route('assignments.create') }}" style="display:flex; align-items:center; gap:8px; padding:10px 16px;
                background:linear-gradient(135deg,#6366f1,#ec4899);
                color:#fff; border-radius:10px; font-weight:600; text-decoration:none;">
        <i class="fa-solid fa-circle-plus"></i> Add Assignment
    </a>
</div>

{{-- ================= SUCCESS MESSAGE ================= --}}
@if(session('success'))
<div id="successMessage"
    style="padding:10px 14px; background:#22c55e; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="errorMessage"
    style="padding:10px 14px; background:#ef4444; color:white; border-radius:8px; margin-bottom:12px;">
    {{ session('error') }}
</div>
@endif
{{-- ================= FILTER SECTION ================= --}}
<form method="GET" style="
background:white;
padding:6px;
border-radius:12px;
margin-bottom:14px;
box-shadow:0 4px 12px rgba(0,0,0,0.05);
">

   <div class="filter-grid">

    {{-- FROM DATE --}}
    <div class="filter-field">
        <label class="f-label">From Date</label>

        <input type="date"
               name="from_date"
               value="{{ request('from_date') }}"
               class="f-input">
    </div>

    {{-- TO DATE --}}
    <div class="filter-field">
        <label class="f-label">To Date</label>

        <input type="date"
               name="to_date"
               value="{{ request('to_date') }}"
               class="f-input">
    </div>

    {{-- DEADLINE --}}
    <div class="filter-field">
        <label class="f-label">Deadline From</label>

        <input type="date"
               name="deadline_from"
               value="{{ request('deadline_from') }}"
               class="f-input">
    </div>

    {{-- STATUS --}}
    <div class="filter-field">
        <label class="f-label">&nbsp;</label>

        <select name="status" class="f-input">
            <option value="">Status</option>

            <option value="Pending"
                {{ request('status') == 'Pending' ? 'selected' : '' }}>
                Pending
            </option>

            <option value="In Progress"
                {{ request('status') == 'In Progress' ? 'selected' : '' }}>
                In Progress
            </option>

            <option value="Completed"
                {{ request('status') == 'Completed' ? 'selected' : '' }}>
                Completed
            </option>

            <option value="Cancelled"
                {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                Cancelled
            </option>
        </select>
    </div>

    {{-- STATE --}}
    <div class="filter-field">
        <label class="f-label">&nbsp;</label>

        <select name="state" id="state" class="f-input">
            <option value="">Select State</option>
        </select>
    </div>

    {{-- DISTRICT --}}
    <div class="filter-field">
        <label class="f-label">&nbsp;</label>

        <select name="district" id="district" class="f-input">
            <option value="">All Districts</option>
        </select>
    </div>

    {{-- BILLING --}}
    <div class="filter-field">
        <label class="f-label">&nbsp;</label>

        <select name="billing_status" class="f-input">
            <option value="">Billing</option>

            <option value="not_billed"
                {{ request('billing_status') == 'not_billed' ? 'selected' : '' }}>
                Not Billed
            </option>
        </select>
    </div>

    {{-- BATCH --}}
    <div class="filter-field">
        <label class="f-label">&nbsp;</label>

        <select name="in_batch" class="f-input">
            <option value="">Batch</option>

            <option value="0"
                {{ request('in_batch') == '0' ? 'selected' : '' }}>
                Not in batch
            </option>
        </select>
    </div>

</div>

<div style="margin-top:10px; display:flex; justify-content:flex-end; gap:8px;">
    <a href="{{ route('assignments.index') }}" class="btn-light">
        Reset
    </a>

    <button type="submit" class="btn-primary">
        Apply
    </button>
</div>
</form>
{{-- ================= TABLE ================= --}}
<div class="table-wrap">
    <table style="width:100%; border-collapse:collapse; background:white; border-radius:14px; overflow:hidden;
                box-shadow:0 10px 25px rgba(0,0,0,0.06);">

        <thead>
            <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb; text-align:left;">
                <th style="padding:6px;">#</th>
                <th>Date</th>
                <th>Assignment</th>
                <th style="width:140px;">Location</th> <!-- smaller -->
                <th>Req.</th>
                <th style="text-align:center;">Regs</th>
                <!-- <th style="text-align:center;">
                    Batch Assigned
                </th> -->
                <th>In Batch</th>
                <th>Billed</th>
                <th>Left</th>
                <th>Deadline</th>
                <th>Status</th>
                <th style="text-align:center;">View</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>

            @php
            $totalRequirement = 0;
            $totalRegistered = 0;
            $totalBatchAssigned = 0;
            $totalBuild = 0;

            $totalLeft = 0;
            $totalBilled = 0;

            $totalRegistered += $assignment->mobilizations_count ?? 0;

            $totalBatchAssigned += $assignment->batch_assigned_count ?? 0;
            @endphp
            @forelse($assignments as $assignment)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <!-- count -->
                <td style="padding:6px; font-weight:600;">{{ $loop->iteration }}</td>
                <!-- Date -->
                <td>{{ \Carbon\Carbon::parse($assignment->date)->format('d-m-Y') }}</td>
                <!-- assignment name  -->
                <td style="font-weight:700;">
                    {{ $assignment->assignment_name }}
                    <br>

                    @php
                    $batchCodes = $assignment->batches->pluck('batch_code')->toArray();
                    @endphp

                    <small onclick="openBatchModal(this)" data-batches='@json($batchCodes)'
                        style="color:#6366f1; cursor:pointer; font-weight:600;">
                        {{ count($batchCodes) }} batches
                    </small>
                </td>
                <!-- Location -->
                <td style="color:#6b7280;width:140px;font-size:13px;">

                    @php
                    $locationText = implode(', ', array_filter([
                    optional($assignment->stateData)->name,
                    $assignment->district
                    ]));
                    @endphp

                    <div title="{{ $locationText }}">
                        {{ Str::limit($locationText, 20) }}
                    </div>

                    @if($assignment->location)
                    <small title="{{ $assignment->location }}">
                        {{ Str::limit($assignment->location, 20) }}
                    </small>
                    @endif

                </td>
                <!-- requirements -->

                <td>
                    {{ $assignment->requirement ?? 0 }}
                </td>


                <!-- Register  -->
                <td style="text-align:center;">
                    <a href="{{ route('assignments.registrations', $assignment->id) }}"
                        style="background:#0ea5e9;color:#fff;padding:6px 12px;border-radius:999px;text-decoration:none;display:inline-block;">
                        <i class="fa-solid fa-users"></i>
                        {{ $assignment->mobilizations_count ?? 0 }}
                    </a>
                </td>

                <!-- <td style="text-align:center;">
                    <span style="
background:#ede9fe;color:#5b21b6;padding:6px 12px;border-radius:999px;font-weight:700;display:inline-block;">
                        <i class="fa-solid fa-layer-group"></i>
                        {{ $assignment->batch_assigned_count ?? 0 }}
                    </span>
                </td> -->

                <!-- In Batch -->

                <!-- <td>
                    <span style="font-weight:700;">
                        {{ $assignment->total_build ?? 0 }}
                    </span>
                </td> -->
                <!-- In Batch -->
                <td>
                    @php
                    $manualBuild = $assignment->total_build ?? 0;
                    $actualCount = $assignment->actual_in_batch ?? 0;
                    $display = max($manualBuild, $actualCount);
                    @endphp

                    <!-- <span style="font-weight:700;">{{ $display }}</span> -->
                    <span style="font-weight:700;">{{ $actualCount }}</span>

                    @if($actualCount > 0)
                    <br>
                    <!-- <small style="color:#16a34a; font-size:11px;">
            <i class="fa-solid fa-users"></i> {{ $actualCount }} actual
        </small> -->
                    @endif
                </td>


                <td>
                    <span style="color:#22c55e;font-weight:700;">
                        {{ $assignment->billed_qty ?? 0 }}
                    </span>
                </td>

                <td>
                    <span style="color:#f59e0b;font-weight:600;">
                        {{ ($assignment->requirement ?? 0) - ($assignment->total_build ?? 0) }}
                    </span>
                </td>


                <td>{{ \Carbon\Carbon::parse($assignment->deadline_date)->format('d-m-Y') }}</td>

                <td>
                    @php
                    $colors = [
                    'Pending' => '#f59e0b',
                    'In Progress' => '#0ea5e9',
                    'Completed' => '#22c55e',
                    'Cancelled' => '#ef4444',
                    ];
                    @endphp
                    <span
                        style="padding:4px 12px; border-radius:999px;background:{{ $colors[$assignment->status] ?? '#6b7280' }};color:white; font-size:10px;">
                        {{ $assignment->status ?? 'Pending' }}
                    </span>
                </td>

                <td style="text-align:center;">
                    <a href="{{ route('assignments.show', $assignment->id) }}" style="color:#6366f1;">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </td>

                <td style="text-align:center;">
                    <div style="display:flex; gap:10px; justify-content:center; align-items:center;">

                        <a href="{{ route('assignments.edit', $assignment->id) }}" style="color:#f59e0b;">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        @php $inUse = $assignment->batches->count() > 0; @endphp

                        <form action="{{ route('assignments.destroy',$assignment->id) }}" method="POST"
                            style="position:relative; display:inline-block;"
                            onsubmit="return confirm('Are you sure you want to delete this assignment?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" {{ $inUse ? 'disabled' : '' }}
                                title="{{ $inUse ? 'Assignment is in use and cannot be deleted' : 'Delete Assignment' }}"
                                style="background: {{ $inUse ? '#e5e7eb' : '#ef4444' }};color: white;border: none;padding: 6px 12px;border-radius: 8px;cursor: {{ $inUse ? 'not-allowed' : 'pointer' }};opacity: {{ $inUse ? '0.6' : '1' }};transition: 0.2s;"
                                onmouseover="if({{ $inUse ? 'true' : 'false' }}) this.nextElementSibling.style.opacity=1"
                                onmouseout="if({{ $inUse ? 'true' : 'false' }}) this.nextElementSibling.style.opacity=0">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            @if($inUse)
                            <span
                                style="position:absolute;bottom:120%;left:50%;transform:translateX(-50%);background:#111827;color:white;padding:4px 8px;border-radius:6px;font-size:12px;white-space:nowrap;opacity:0;pointer-events:none;transition:.2s;">
                                In Use
                            </span>
                            @endif

                        </form>

                </td>
                @php
                $totalRequirement += $assignment->requirement ?? 0;
                $totalBuild += $assignment->total_build ?? 0;
                $totalLeft += ($assignment->requirement ?? 0) - ($assignment->total_build ?? 0);
                $totalBilled += $assignment->billed_qty ?? 0;
                @endphp
            </tr>

            @empty
            <tr>
                <td colspan="11" style="padding:14px; text-align:center; color:#6b7280;">
                    No records found
                </td>
            </tr>
            @endforelse

            <tr style="background:#f9fafb;font-weight:700;border-top:2px solid #e5e7eb;">
                <td colspan="4" style="padding:6px;text-align:right;">TOTAL</td>
                <td>{{ $totalRequirement }}</td>
                <td>{{ $totalRegs }}</td>
                <td style="color:#5b21b6;">{{ $totalBatchAssigned }}</td>
                <td>{{ $totalBuild }}</td>
                <td style="color:#22c55e;">{{ $totalBilled }}</td>
                <td style="color:#f59e0b;">{{ $totalLeft }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>
</div>


{{-- ================= BATCH MODAL ================= --}}
<!-- <div id="batchModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:100;">
        <div
            style="background:white; width:95%; max-width:720px; margin:6% auto; padding:22px; border-radius:16px; box-shadow:0 25px 50px rgba(0,0,0,.15);">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 style="font-size:20px; font-weight:700; color:#4f46e5;">
                    <i class="fa-solid fa-layer-group"></i> Assign Batches
                </h3>
                <button onclick="closeBatchModal()"
                    style="border:none; background:#f3f4f6; padding:6px 10px; border-radius:6px;">✖</button>
            </div>

            <div id="batchList" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px;">
            </div>

            <button onclick="saveBatches()" style="margin-top:18px; width:100%; padding:13px;
                    background:linear-gradient(135deg,#6366f1,#ec4899);
                    color:white; font-weight:700; border:none; border-radius:12px;">
                💾 Save Batches
            </button>
        </div>
</div> -->



{{-- REGISTER MODAL --}}
<div id="registerModal"
    style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;justify-content:center;align-items:center;">
    <div
        style="background:white;width:90%;max-width:800px;padding:20px;border-radius:12px;box-shadow:0 25px 50px rgba(0,0,0,0.15);max-height:90%;overflow:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="font-size:20px;font-weight:700;color:#4f46e5;">
                <i class="fa-solid fa-users"></i> Registered Candidates
            </h3>

            <button onclick="closeRegisterModal()" class="btn btn-danger">
                ✖
            </button>
        </div>

        {{-- Batch + Assign --}}
        <div style="display:flex;gap:12px;margin-bottom:15px;flex-wrap:wrap;">

            <select id="batchSelect" style="padding:8px 12px;border-radius:8px;border:1px solid #ddd;">
                <option value="">Select Batch</option>
            </select>

            <button onclick="assignSelectedCandidates()" class="btn btn-success">
                <i class="fa-solid fa-user-check"></i>
                Assign Selected
            </button>

        </div>

        <table id="registerTable" class="table">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th>
                        <input type="checkbox" onclick="selectAllCandidates(this)">
                    </th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>District</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <button onclick="assignSelectedCandidates()"
            style="margin-top:12px;width:100%;padding:6px;background:linear-gradient(135deg,#6366f1,#ec4899);color:white;font-weight:700;border:none;border-radius:10px;">
            Assign Selected To Batch
        </button>
    </div>
</div>



<div id="batchModal" style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.5);
justify-content:center;
align-items:center;
z-index:2000;
">

    <div style="
        background:#fff;
        border-radius:12px;
        padding:24px;
        max-width:400px;
        width:90%;
        position:relative;
    ">

        <h3 style="
            margin-top:0;
            margin-bottom:16px;
            font-size:18px;
            font-weight:600;
        ">
            Batch Details
        </h3>

        <ul id="batchList" style="
            padding-left:20px;
            margin-bottom:16px;
            list-style:disc;
        ">
        </ul>

        <button onclick="closeBatchModal()" style="
            padding:8px 16px;
            background:#6366f1;
            color:white;
            border:none;
            border-radius:8px;
            cursor:pointer;
        ">
            Close
        </button>

    </div>
</div>

<script>
function populateDistricts(cities, isInitialLoad) {
    const districtSelect = document.getElementById('district');

    districtSelect.innerHTML = '<option value="">All Districts</option>';

    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city.name;
        option.textContent = city.name;

        // Check if this district should be selected
        if (selectedDistrict && selectedDistrict === city.name && isInitialLoad) {
            option.selected = true;
        }

        districtSelect.appendChild(option);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    
    document.getElementById('state').addEventListener('change', function() {

    });
});


function closeBatchModal() {
    document.getElementById('batchModal').style.display = 'none';
}

function saveBatches() {
    const ids = [...document.querySelectorAll('#batchList input:checked')]
        .map(cb => parseInt(cb.value));

    fetch(`/assignments/${currentAssignmentId}/batches`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                batch_ids: ids
            })
        })
        .then(res => res.json())
        .then(data => {
            alert('✅ Batches assigned successfully!');
            closeBatchModal();
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong');
        });
}

let currentAssignmentId = null;

function openRequestsModal(assignmentId) {
    currentAssignmentId = assignmentId;

    const modal = document.getElementById('registerModal');
    const tbody = document.querySelector('#registerTable tbody');
    const batchSelect = document.getElementById('batchSelect');

    tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align:center;padding:10px;">
                        Loading...
                    </td>
                </tr>
            `;

    modal.style.display = 'flex';

    fetch(`/api/assignment-batches/${assignmentId}`, {
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => {
            batchSelect.innerHTML = `<option value="">Select Batch</option>`;

            if (data.batches && data.batches.length) {
                data.batches.forEach(batch => {
                    batchSelect.innerHTML += `
                                <option value="${batch.id}">
                                    ${batch.batch_code ?? 'Batch #' + batch.id}
                                </option>
                            `;
                });
            }
        })
        .catch(err => {
            console.error('Failed to load batches', err);
            batchSelect.innerHTML = `<option value="">Unable to load batches</option>`;
        });

    fetch(`/assignments/${assignmentId}/mobilizations`, {
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `
                            <tr>
                                <td colspan="7" style="text-align:center;padding:10px;">
                                    No candidates found
                                </td>
                            </tr>
                        `;
                return;
            }

            data.forEach((candidate, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                            <td style="padding:6px; border:1px solid #e5e7eb;">
                                <input type="checkbox" class="candidate-checkbox" value="${candidate.id}">
                            </td>
                            <td style="padding:6px; border:1px solid #e5e7eb;">${index+1}</td>
                            <td style="padding:6px; border:1px solid #e5e7eb;">${candidate.name ?? '-'}</td>
                            <td style="padding:6px; border:1px solid #e5e7eb;">${candidate.email ?? '-'}</td>
                            <td style="padding:6px; border:1px solid #e5e7eb;">${candidate.mobile ?? '-'}</td>
                            <td style="padding:6px; border:1px solid #e5e7eb;">${candidate.district ?? '-'}</td>
                            <td style="padding:6px; border:1px solid #e5e7eb;">${candidate.state ?? '-'}</td>
                        `;
                tbody.appendChild(tr);
            });
        });
}

function assignSelectedCandidates() {
    const selected = [...document.querySelectorAll('.candidate-checkbox:checked')]
        .map(cb => cb.value);

    const batchId = document.getElementById('batchSelect').value;

    if (!batchId) {
        alert("Please select batch");
        return;
    }

    if (selected.length === 0) {
        alert("Please select candidates");
        return;
    }

    fetch(`/assign-bulk-candidates/${currentAssignmentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                candidate_ids: selected,
                batch_id: batchId
            })
        })
        .then(res => res.json())
        .then(data => {
            alert("Candidates Assigned Successfully");
            closeRegisterModal();
            location.reload();
        });
}

function closeRegisterModal() {
    document.getElementById('registerModal').style.display = 'none';
}

function selectAllCandidates(master) {
    const checkboxes = document.querySelectorAll('.candidate-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    setTimeout(function() {

        let success = document.getElementById("successMessage");
        let error = document.getElementById("errorMessage");

        if (success) {
            success.style.transition = "opacity 0.5s";
            success.style.opacity = "0";
            setTimeout(() => success.remove(), 500);
        }

        if (error) {
            error.style.transition = "opacity 0.5s";
            error.style.opacity = "0";
            setTimeout(() => error.remove(), 500);
        }

    }, 4000); // disappears after 4 seconds

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // Check if page was reloaded (F5 / refresh button)
    if (performance.navigation.type === 1) {
        window.location.href = "{{ route('assignments.index') }}";
    }

});
</script>


<script>
function showTooltip(el) {
    let batches = JSON.parse(el.dataset.batches);
    let tooltip = el.parentElement.querySelector('.batch-tooltip');

    tooltip.innerHTML = batches.join("<br>");
    tooltip.style.display = "block";
}

function hideTooltip(el) {
    let tooltip = el.parentElement.querySelector('.batch-tooltip');
    tooltip.style.display = "none";
}

function openBatchModal(el) {
    let batches = JSON.parse(el.dataset.batches || "[]");

    let html = "";

    if (batches.length === 0) {
        html = "<div>No batches assigned</div>";
    } else {
        batches.forEach(function(batch) {
            html += `<div style="padding:6px 0;border-bottom:1px solid #eee;">${batch}</div>`;
        });
    }

    document.getElementById("batchList").innerHTML = html;
    document.getElementById("batchModal").style.display = "flex";
}

function closeBatchModal() {
    document.getElementById("batchModal").style.display = "none";
}
</script>


{{-- STATE/DISTRICT CONFIG --}}
<script>
window.stateDropdownId = "state";
window.cityDropdownId = "district";

window.selectedState = "{{ request('state') }}";
window.selectedDistrict = "{{ request('district') }}";
</script>

<script src="{{ asset('js/state.js') }}"></script>
@endsection