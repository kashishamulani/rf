@extends('layouts.app')
@section('content')

<style>
.form-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    padding: 20px 24px;
    width: 100%;
    max-width: 1300px;
}

.form-group {
    margin-bottom: 12px;
}

.form-label {
    font-weight: 600;
    color: #4338ca;
    margin-bottom: 2px;
    font-size: 14px;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid rgba(99, 102, 241, 0.35);
    font-size: 14px;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
}

.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

/* Assignments */
.assignments-wrapper {
    position: relative;
}

.assignments-dropdown {
    width: 100%;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid rgba(99, 102, 241, 0.35);
    background: #fff;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}

.assignments-options {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 180px;
    overflow-y: auto;
    border: 1px solid rgba(99, 102, 241, 0.35);
    border-radius: 10px;
    background: #fff;
    z-index: 9999;
}

.assignments-options label {
    display: block;
    padding: 6px 10px;
    cursor: pointer;
}

.assignments-options label:hover {
    background: rgba(99, 102, 241, 0.05);
}

.selected-assignments {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    font-weight: 600;
    border-radius: 14px;
    padding: 10px 20px;
    border: none;
    font-size: 14px;
}

.btn-back {
    background: #e5e7eb;
    color: #374151;
    font-weight: 600;
    border-radius: 12px;
    padding: 8px 16px;
    text-decoration: none;
}

/* Alerts */
.alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 10px 14px;
    border-radius: 12px;
    margin-bottom: 14px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    padding: 10px 14px;
    border-radius: 12px;
    margin-bottom: 14px;
}

/* PO Table */
.po-table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 14px;
    overflow: hidden;
    display: none;
    background: white;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
}

.po-table thead tr {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: white;
    font-size: 14px;
}

.po-table th,
.po-table td {
    padding: 10px 12px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
    font-size: 13.5px;
}

.po-table input[type="number"] {
    width: 80px;
    padding: 6px 8px;
    border-radius: 8px;
    border: 1px solid rgba(99, 102, 241, 0.35);
}

.grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.grid-1 {
    display: grid;
    grid-template-columns: 1fr;
}

.assignment-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    cursor: pointer;
    font-size: 13px;
}

.assignment-option:hover {
    background: rgba(99, 102, 241, 0.05);
}

.assignment-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.assignments-wrapper {
    position: relative;
    z-index: 10000;
}

.assignments-options {
    display: none;
    position: absolute;
    top: 105%;
    left: 0;
    width: 100%;
    max-height: 220px;
    overflow-y: auto;
    border: 1px solid rgba(99, 102, 241, 0.35);
    border-radius: 12px;
    background: #fff;
    z-index: 99999;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.form-card {
    overflow: visible !important;
}

.grid-3 {
    margin-top: 5px;
}
</style>



<div style="padding:12px; width:100%; display:flex; flex-direction:column; align-items:center;">

    <div style="width:100%; max-width:1300px; display:flex; justify-content:flex-end; margin-bottom:12px;">
        <a href="{{ route('batches.index') }}" class="btn-back">← Back</a>
    </div>

    <form action="{{ route('batches.update',$batch->id) }}" method="POST" class="form-card">
        @csrf
        @method('PUT')

        <h2
            style="text-align:center;margin-bottom:16px;background:linear-gradient(135deg,#6366f1,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
            Edit Batch
        </h2>
        @if ($errors->any())
        <div class="alert-error">
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="grid-3">

            <div class="form-group">
                <label class="form-label">Batch Code *</label>
                <input type="text" name="batch_code" value="{{ $batch->batch_code }}" class="form-input">
            </div>



            <div class="form-group">
                <label class="form-label">State *</label>

                <select name="state" id="stateSelect" class="form-select">
                    <option value="">Select State</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">District *</label>

                <select name="district" id="districtSelect" class="form-select">
                    <option value="">Select District</option>
                </select>
            </div>

        </div>

        <div class="grid-3">

            <div class="form-group">
                <label class="form-label">Address *</label>
                <textarea name="address" rows="2" class="form-textarea">{{ $batch->address }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Batch Size</label>
                <input type="number" name="batch_size" value="{{ $batch->batch_size }}" min="1" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select">
                    <option {{ $batch->status=='Open'?'selected':'' }}>Open</option>
                    <option {{ $batch->status=='Closed'?'selected':'' }}>Closed</option>
                    <option {{ $batch->status=='In Progress'?'selected':'' }}>In Progress</option>
                </select>
            </div>

        </div>


        <div class="grid-1">

            <div class="form-group assignments-wrapper">
                <label class="form-label">Assignments *</label>

                <div class="assignments-dropdown" id="assignmentsToggle">
                    Select Assignments ▼
                </div>

                <div class="assignments-options" id="assignmentsOptions">


                    <div style="text-align:right;padding:6px;">
                        <button type="button" id="closeAssignments"
                            style="background:#ef4444;color:#fff;border:none;padding:4px 8px;border-radius:6px;font-size:12px;">
                            Close ✕
                        </button>
                    </div>
                    @foreach($assignments as $a)
                    <label class="assignment-option">
                        <input type="checkbox" class="assignment-check" name="assignments[]" value="{{ $a->id }}"
                            data-id="{{ $a->id }}" data-name="{{ $a->assignment_name }}"
                            data-requirement="{{ $a->requirement }}"
                            {{ in_array($a->id,$batchAssignmentIds)?'checked':'' }}>

                        <span class="assignment-text">
                            {{ $a->assignment_name }}
                        </span>
                    </label>
                    @endforeach
                </div>

                <div class="selected-assignments" id="selectedAssignments"></div>
            </div>

            <div style="margin-top:18px; width:100%;">
                <div style="overflow-x:auto;">
                    <table id="assignmentTable" class="po-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Assignment Name</th>
                                <th>Requirement</th>
                                <th>Remaining</th>
                                <th>In Batch</th>
                            </tr>
                        </thead>
                        <tbody id="assignmentTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>



        <div class="grid-3">

            <div class="form-group">
                <label class="form-label">Training From *</label>
                <input type="date" name="training_from"
                    value="{{ old('training_from', $batch->training_from ? \Carbon\Carbon::parse($batch->training_from)->format('Y-m-d') : '') }}"
                    class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Training To *</label>
                <input type="date" name="training_to"
                    value="{{ old('training_to', $batch->training_to ? \Carbon\Carbon::parse($batch->training_to)->format('Y-m-d') : '') }}"
                    class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Training Hours *</label>
                <input type="number" step="0.01" name="training_hours"
                    value="{{ old('training_hours', $batch->training_hours) }}" class="form-input">
            </div>

        </div>


        <div class="grid-2" style="grid-template-columns: repeat(2, 1fr);">

            <div class="form-group">
                <label class="form-label">Service Period From *</label>

                <input type="date" name="service_from"
                    value="{{ old('service_from', $batch->service_from ? \Carbon\Carbon::parse($batch->service_from)->format('Y-m-d') : '') }}"
                    class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Service Period To *</label>

                <input type="date" name="service_to"
                    value="{{ old('service_to', $batch->service_to ? \Carbon\Carbon::parse($batch->service_to)->format('Y-m-d') : '') }}"
                    class="form-input">
            </div>

        </div>
        <div class="grid-2" style="grid-template-columns: repeat(3, 1fr);">

            <div class="form-group">
                <label class="form-label">PO / WO </label>
                <select name="po_id" id="poSelect" class="form-select">
                    <option value="">--Select--</option>
                    @foreach($pos as $po)
                    <option value="{{ $po->id }}" {{ $batch->po_id==$po->id?'selected':'' }}>
                        {{ $po->po_no }}
                    </option>
                    @endforeach
                </select>
            </div>

        </div>


        <div style="text-align:center;margin-top:20px;">
            <button type="submit" class="btn-primary">Update Batch</button>
        </div>

    </form>
</div>
<script>
    window.stateDropdownId = "stateSelect";
    window.cityDropdownId = "districtSelect";

    // edit page old values
    window.selectedState = "{{ old('state', $batch->state) }}";
    window.selectedDistrict = "{{ old('district', $batch->district) }}";
</script>

<script src="{{ asset('js/state.js') }}"></script>
<script>
const oldState = "{{ $batch->state }}";
const oldDistrict = "{{ $batch->district }}";


const toggle = document.getElementById('assignmentsToggle');
const options = document.getElementById('assignmentsOptions');
const selectedDiv = document.getElementById('selectedAssignments');

toggle.onclick = () => options.style.display =
    options.style.display === 'block' ? 'none' : 'block';

function showAssignments() {
    let sel = [...document.querySelectorAll('#assignmentsOptions input:checked')]
        .map(e => e.parentElement.textContent.trim());
    selectedDiv.textContent =
        sel.length ? `Selected: ${sel.join(', ')}` : '';
}
document.querySelectorAll('#assignmentsOptions input')
    .forEach(cb => cb.onchange = showAssignments);
showAssignments();


const poSelect = document.getElementById('poSelect');
const tbody = document.getElementById('poItemsBody');
const table = document.getElementById('poItemsTable');

function loadPoItems(id) {
    fetch(`/po/${id}/items`).then(r => r.json())
        .then(items => {
            if (tbody) {
                tbody.innerHTML = '';
                table.style.display = 'table';

                items.forEach((it, i) => {
                    let rem = it.remaining_qty ??
                        (it.quantity - it.used_quantity);

                    // let old = oldItems[it.id] ?? '';

                    tbody.insertAdjacentHTML('beforeend', `
<tr>
<td>${i+1}</td>
<td>${it.item}</td>
<td>${it.quantity}</td>
<td>${rem}</td>
<td>${it.value}</td>
<td>
<input type="number"
name="batch_items[${it.id}]"
value=""
max="${rem}"
class="form-input">
</td>
</tr>`);
                });
            }
        });
}

if (poSelect.value) loadPoItems(poSelect.value);
poSelect.onchange = () => loadPoItems(poSelect.value);



const oldBuilds = @json($assignmentBuilds);
const assignmentTable = document.getElementById('assignmentTable');
const assignmentTbody = document.getElementById('assignmentTableBody');
const batchSizeInput = document.querySelector('input[name="batch_size"]');


function updateAssignmentTable() {

    assignmentTbody.innerHTML = '';

    const selected = document.querySelectorAll('.assignment-check:checked');

    if (!selected.length) {
        assignmentTable.style.display = 'none';
        return;
    }

    assignmentTable.style.display = 'table';

    const promises = Array.from(selected).map((cb, index) => {

        const id = cb.dataset.id;
        const name = cb.dataset.name;

        return fetch(`/assignments/${id}/remaining?batch_id={{ $batch->id }}`)
            .then(res => res.json())
            .then(data => {



                let inputValue = '';

                if (oldBuilds[id] !== undefined) {
                    inputValue = oldBuilds[id];
                } else if (data.student_count > 0) {
                    inputValue = data.student_count;
                } else if (data.current_build > 0) {
                    inputValue = data.current_build;
                }

                return {
                    index: index + 1,
                    id,
                    name,
                    requirement: data.requirement,
                    remaining: data.remaining,
                    current_build: data.current_build,
                    student_count: data.student_count,
                    inputValue
                };
            });
    });

    Promise.all(promises).then(results => {

        results.forEach(data => {

            const actualRemaining =
                data.remaining + data.current_build - data.inputValue;

            const row = `
            <tr>

                <td>${data.index}</td>

                <td>${data.name}</td>

                <td>${data.requirement}</td>

                <td class="remaining-cell">
                    ${actualRemaining}
                </td>

                <td>
                    <input type="number"
                        name="builds[${data.id}]"
                        value="${data.inputValue}"
                        min="0"
                        max="${data.remaining + data.current_build}"
                        class="form-input build-input"
                        data-max="${data.remaining + data.current_build}"
                        data-original="${data.current_build}">
                </td>

            </tr>
            `;

            assignmentTbody.insertAdjacentHTML('beforeend', row);
        });

        attachBuildValidation();
    });
}


function attachBuildValidation() {

    document.querySelectorAll('.build-input').forEach(input => {

        input.addEventListener('input', function() {

            let max = parseInt(this.dataset.max) || 0;
            let val = parseInt(this.value) || 0;

            if (val > max) {

                alert('In batch cannot exceed remaining quantity!');

                this.value = max;

                val = max;
            }

            const row = this.closest('tr');

            const remainingCell = row.querySelector('.remaining-cell');

            remainingCell.innerText = max - val;
        });
    });
}

document.querySelectorAll('.assignment-check')
    .forEach(cb => {
        cb.addEventListener('change', updateAssignmentTable);
    });



// 👇 VERY IMPORTANT → LOAD OLD BUILDS ON PAGE LOAD
updateAssignmentTable();






const closeBtn = document.getElementById('closeAssignments');

// STOP CLICK INSIDE DROPDOWN
options.addEventListener('click', function(e) {
    e.stopPropagation();
});

// CLOSE BUTTON
closeBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    options.style.display = 'none';
});

// CLICK OUTSIDE CLOSE
document.addEventListener('click', function(e) {
    if (!toggle.contains(e.target) && !options.contains(e.target)) {
        options.style.display = 'none';
    }
});






function updateAssignmentTable() {

    assignmentTbody.innerHTML = '';

    const selected = document.querySelectorAll('.assignment-check:checked');

    if (!selected.length) {
        assignmentTable.style.display = 'none';
        return;
    }

    assignmentTable.style.display = 'table';

    const promises = Array.from(selected).map((cb, index) => {

        const id = cb.dataset.id;
        const name = cb.dataset.name;

        return fetch(`/assignments/${id}/remaining?batch_id={{ $batch->id }}`)
            .then(res => res.json())
            .then(data => {

                let inputValue = '';

                // ✅ PRIORITY: actual students in batch > old build > current build
                if (data.student_count > 0) {
                    inputValue = data.student_count; // real candidates moved to batch
                } else if (oldBuilds[id] !== undefined) {
                    inputValue = oldBuilds[id];
                } else if (data.current_build > 0) {
                    inputValue = data.current_build;
                }

                return {
                    index: index + 1,
                    id,
                    name,
                    requirement: data.requirement,
                    remaining: data.remaining,
                    current_build: data.current_build,
                    student_count: data.student_count,
                    inputValue
                };
            });
    });

    Promise.all(promises).then(results => {

        results.forEach(data => {

            const actualRemaining = data.remaining + data.current_build - data.inputValue;


            const isLocked = data.student_count > 0;

            const row = `
            <tr>
                <td>${data.index}</td>
                <td>${data.name}</td>
                <td>${data.requirement}</td>
                <td class="remaining-cell">${actualRemaining}</td>
                <td style="position:relative;">
                    <input type="number"
                        name="builds[${data.id}]"
                        value="${data.inputValue}"
                        min="0"
                        max="${data.remaining + data.current_build}"
                        class="form-input build-input"
                        data-max="${data.remaining + data.current_build}"
                        data-original="${data.current_build}"
                        ${isLocked ? 'readonly style="background:#f0fdf4;border-color:#22c55e;cursor:not-allowed;"' : ''}>

                    ${isLocked
                        ? `<small style="display:block;color:#16a34a;font-size:11px;margin-top:3px;">
                                <i class="fa-solid fa-users"></i> ${data.student_count} candidates in batch
                           </small>`
                        : `<small style="display:block;color:#9ca3af;font-size:11px;margin-top:3px;">
                                No candidates yet — enter manually
                           </small>`
                    }
                </td>
            </tr>
            `;

            assignmentTbody.insertAdjacentHTML('beforeend', row);
        });

        attachBuildValidation();
    });
}
</script>
@endsection