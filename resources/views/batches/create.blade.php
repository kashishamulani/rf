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
    z-index: 10000;
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
    top: 105%;
    left: 0;
    width: 100%;
    max-height: 220px;
    overflow-y: auto;
    border: 1px solid rgba(99, 102, 241, 0.35);
    border-radius: 12px;
    background: #ffffff;
    z-index: 99999;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.form-card {
    overflow: visible !important;
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

.assignment-option input {
    cursor: pointer;
}

.assignment-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
    </style>

    <div style="padding:12px; width:100%; display:flex; flex-direction:column; align-items:center;">

        <div style="width:100%; max-width:1300px; display:flex; justify-content:flex-end; margin-bottom:12px;">
            <a href="{{ route('batches.index') }}" class="btn-back">← Back</a>
        </div>

        <form action="{{ route('batches.store') }}" method="POST" class="form-card">
            @csrf

            @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
            @endif

            <h2 style="text-align:center; margin-bottom:16px;
                background:linear-gradient(135deg,#6366f1,#ec4899);
                -webkit-background-clip:text;
                -webkit-text-fill-color:transparent;">
                Add New Batch
            </h2>

            {{-- BASIC --}}
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Batch Code<span style="color:#ef4444;">*</span></label>
                    <input type="text" name="batch_code" value="{{ old('batch_code') }}" required class="form-input">
                </div>


                <div class="form-group">
                    <label class="form-label">State<span style="color:#ef4444;">*</span></label>
                    <select name="state" id="stateSelect" class="form-select">
                        <option value="">-- Select State --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">City<span style="color:#ef4444;">*</span></label>
                    <select name="district" id="districtSelect" class="form-select">
                        <option value="">-- Select City --</option>
                    </select>
                </div>
            </div>


            <div class="grid-3">

                <div class="form-group">
                    <label class="form-label">
                        Address<span style="color:#ef4444;">*</span>
                    </label>
                    <textarea name="address" rows="2" class="form-textarea">{{ old('address') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Batch Size
                    </label>
                    <input type="number" name="batch_size" min="1" value="{{ old('batch_size') }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Status<span style="color:#ef4444;">*</span>
                    </label>
                    <select name="status" class="form-select">
                        <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress
                        </option>
                    </select>
                </div>

            </div>


            <div class="grid-1">
                <div class="form-group assignments-wrapper">
                    <label class="form-label">
                        Assignments<span style="color:#ef4444;">*</span>
                    </label>

                    <div class="assignments-dropdown" id="assignmentsToggle">
                        Select Assignments <i class="fa-solid fa-caret-down"></i>
                    </div>

                    <div class="assignments-options" id="assignmentsOptions">
                        <div style="text-align:right; padding:6px;">
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
                                {{ in_array($a->id, old('assignments', [])) ? 'checked' : '' }}>

                            <span class="assignment-text">
                                {{ $a->assignment_name }}
                            </span>
                        </label>
                        @endforeach
                    </div>

                    <div class="selected-assignments" id="selectedAssignments"></div>
                </div>
            </div>

            {{-- ASSIGNMENT BUILD TABLE --}}
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



            {{-- TRAINING --}}
            <div class="grid-2" style="grid-template-columns: repeat(3, 1fr);">
                <div class="form-group">
                    <label class="form-label">Training From<span style="color:#ef4444;">*</span></label>
                    <input type="date" name="training_from" value="{{ old('training_from') }}" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Training To<span style="color:#ef4444;">*</span></label>
                    <input type="date" name="training_to" value="{{ old('training_to') }}" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Training Hours<span style="color:#ef4444;">*</span></label>
                    <input type="number" step="0.01" name="training_hours" value="{{ old('training_hours') }}"
                        class="form-input">
                </div>
            </div>


            {{-- SERVICE PERIOD --}}
            <div class="grid-2" style="grid-template-columns: repeat(2, 1fr);">

                <div class="form-group">
                    <label class="form-label">
                        Service Period From<span style="color:#ef4444;">*</span>
                    </label>

                    <input type="date" name="service_from" value="{{ old('service_from') }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Service Period To<span style="color:#ef4444;">*</span>
                    </label>

                    <input type="date" name="service_to" value="{{ old('service_to') }}" class="form-input">
                </div>

            </div>

            {{-- PO + STATUS --}}
            <div class="grid-2" style="grid-template-columns: repeat(3, 1fr);">
                <div class="form-group">
                    <label class="form-label">PO / WO</label>
                    <select name="po_id" id="poSelect" class="form-select">
                        <option value="">-- Select PO --</option>
                        @foreach($pos as $po)
                        <option value="{{ $po->id }}" {{ old('po_id') == $po->id ? 'selected' : '' }}>
                            {{ $po->po_no }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="text-align:center; margin-top:20px;">
                <button type="submit" class="btn-primary">Add Batch</button>
            </div>



            <script>
            const oldAssignments = @json(old('assignments', []));
            const oldBuilds = @json(old('builds', []));
            </script>
        </form>
    </div>

    <script>
const toggle = document.getElementById('assignmentsToggle');
const options = document.getElementById('assignmentsOptions');
const selectedDiv = document.getElementById('selectedAssignments');

toggle.addEventListener('click', () => {
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function(e) {
    if (!toggle.contains(e.target) && !options.contains(e.target)) {
        options.style.display = 'none';
    }
});

document.querySelectorAll('.assignment-check').forEach(cb => {
    cb.addEventListener('change', function() {

        const selected = Array.from(document.querySelectorAll('.assignment-check:checked'))
            .map(i => i.dataset.name);

        selectedDiv.textContent = selected.length ?
            `Selected: ${selected.join(', ')}` :
            '';
    });
});
// ================= STATE / DISTRICT =================
const stateSelect = document.getElementById('stateSelect');
const districtSelect = document.getElementById('districtSelect');

// Load States
fetch('/states')
    .then(res => {
        if (!res.ok) {
            throw new Error('Failed to fetch states: ' + res.status);
        }
        return res.json();
    })
    .then(states => {
        console.log('States:', states);
        states.forEach(state => {
            stateSelect.innerHTML += `<option value="${state.iso2}">${state.name}</option>`;
        });
    })
    .catch(error => {
        console.error('Error loading states:', error);
        alert('Failed to load states. Please check console for details.');
    });

// Load Districts
stateSelect.addEventListener('change', function() {
    const stateCode = this.value;
    districtSelect.innerHTML = `<option value="">Loading...</option>`;

    if (!stateCode) return;

    fetch(`/districts/${stateCode}`)
        .then(res => {
            if (!res.ok) {
                throw new Error('Failed to fetch districts: ' + res.status);
            }
            return res.json();
        })
        .then(districts => {
            console.log('Districts for', stateCode, ':', districts);
            districtSelect.innerHTML = `<option value="">-- Select City --</option>`;
            districts.forEach(d => {
                districtSelect.innerHTML += `<option value="${d.name}">${d.name}</option>`;
            });
        })
        .catch(error => {
            console.error('Error loading districts:', error);
            districtSelect.innerHTML = `<option value="">-- Select City --</option>`;
            alert('Failed to load districts. Please check console for details.');
        });
});




// ================= ASSIGNMENT TABLE =================
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

    selected.forEach((cb, index) => {

        const id = cb.dataset.id;
        const name = cb.dataset.name;

        fetch(`/assignment/${id}/remaining`)
            .then(res => res.json())
            .then(data => {

                const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${name}</td>
                <td>${data.requirement}</td>
                <td>${data.remaining}</td>
                <td>
                    <input type="number"
                        name="builds[${id}]"
                        min="0"
                        max="${data.remaining}"
                        class="form-input build-input"
                        data-max="${data.remaining}">
                </td>
            </tr>
            `;

                assignmentTbody.insertAdjacentHTML('beforeend', row);

                attachBuildValidation();
            });
    });
}

function attachBuildValidation() {

    document.querySelectorAll('.build-input').forEach(input => {

        input.addEventListener('input', function() {

            let max = parseInt(this.dataset.max);
            let val = parseInt(this.value) || 0;

            if (val > max) {
                alert('Build cannot exceed remaining requirement');
                this.value = max;
                val = max;
            }

            const row = this.closest('tr');

            const requirement = parseInt(row.children[2].innerText);

            const newRemaining = max - val;

            row.children[3].innerText = newRemaining;

        });

    });

}



// Trigger table update when checkbox changes
document.querySelectorAll('.assignment-check').forEach(cb => {
    cb.addEventListener('change', updateAssignmentTable);
});
const closeBtn = document.getElementById('closeAssignments');

// STOP CLICK PROPAGATION INSIDE DROPDOWN
options.addEventListener('click', function(e) {
    e.stopPropagation();
});

// CLOSE BUTTON
closeBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    options.style.display = 'none';
});
    </script>




    @endsection