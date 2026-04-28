@extends('layouts.app')

@section('content')
<div style="max-width:1200px;margin:auto;">

<h2 style="margin-bottom:20px;">
    Add Candidates – Batch {{ $batch->batch_code }}
</h2>

<form method="POST" action="{{ route('batches.candidates.store', $batch->id) }}" id="candidateForm">
@csrf

{{-- Assignment --}}
<div style="margin-bottom:15px;">
    <label><strong>Assignment</strong></label>
    <select id="assignmentSelect" name="assignment_id" required style="width:100%;padding:10px;">
        <option value="">-- Select Assignment --</option>
        @foreach($assignments as $a)
            <option value="{{ $a->id }}">{{ $a->assignment_name }}</option>
        @endforeach
    </select>
</div>

{{-- Form --}}
<div style="margin-bottom:15px;">
    <label><strong>Form</strong></label>
    <select id="formSelect" style="width:100%;padding:10px;">
        <option value="">-- Select Form --</option>
    </select>
</div>

<button type="button" id="openModal" disabled
    style="padding:10px 18px;background:#22c55e;color:white;border-radius:8px;border:none;opacity:.5;cursor:pointer;">
    Select Students
</button>

{{-- Selected Students Summary --}}
<div id="selectedStudentsSummary" style="margin-top:20px;display:none;">
    <h4>Selected Students: <span id="selectedCount">0</span></h4>
    <div id="selectedList" style="max-height:200px;overflow-y:auto;border:1px solid #ddd;padding:10px;border-radius:5px;margin-top:10px;">
        <!-- Selected students will be listed here -->
    </div>
</div>

{{-- Hidden selected students --}}
<div id="selectedStudents"></div>

<button type="submit" id="submitBtn" disabled
    style="margin-top:20px;padding:10px 22px;background:#6366f1;color:#fff;border-radius:8px;border:none;cursor:pointer;opacity:0.5;">
    Save Candidates
</button>

</form>
</div>

{{-- STUDENT MODAL --}}
<div id="studentModal"
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);padding:40px;z-index:1050;overflow-y:auto;">
    <div style="background:#fff;padding:20px;border-radius:10px;max-width:1200px;margin:auto;max-height:90vh;overflow-y:auto;position:relative;z-index:1051;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
            <h3 style="margin:0;">Select Students</h3>
            <button onclick="closeModal()"
                style="background:#ef4444;color:white;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;z-index:1052;position:relative;">
                ✕ Close
            </button>
        </div>

        <div style="margin-bottom:15px;">
            <input type="text" id="studentSearch" placeholder="Search by name, email, city, etc..." 
                   style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
        </div>

        <div style="overflow-x:auto;">
            <table border="1" width="100%" id="studentTable" style="border-collapse:collapse;min-width:1000px;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th style="padding:10px;text-align:left;">Select</th>
                        <th style="padding:10px;text-align:left;">ID</th>
                        <th style="padding:10px;text-align:left;">Name</th>
                        <th style="padding:10px;text-align:left;">Mobile</th>
                        <th style="padding:10px;text-align:left;">Email</th>
                        <th style="padding:10px;text-align:left;">City</th>
                        <th style="padding:10px;text-align:left;">DOB</th>
                        <th style="padding:10px;text-align:left;">Aadhar</th>
                        <th style="padding:10px;text-align:left;">Created At</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div style="margin-top:15px;text-align:right;">
            <button onclick="closeModal()"
                style="padding:8px 20px;background:#6366f1;color:white;border-radius:6px;border:none;cursor:pointer;">
                Done
            </button>
        </div>
    </div>
</div>

<script>
const assignmentSelect = document.getElementById('assignmentSelect');
const formSelect = document.getElementById('formSelect');
const modal = document.getElementById('studentModal');
const tableBody = document.querySelector('#studentTable tbody');
const selectedStudentsDiv = document.getElementById('selectedStudents');
const selectedStudentsSummary = document.getElementById('selectedStudentsSummary');
const selectedList = document.getElementById('selectedList');
const selectedCount = document.getElementById('selectedCount');
const submitBtn = document.getElementById('submitBtn');
const openModalBtn = document.getElementById('openModal');
const studentSearch = document.getElementById('studentSearch');
let currentStudents = [];
let selectedStudentData = {}; // Store student data for display

// Ensure modal is on top when opened
function ensureModalOnTop() {
    modal.style.zIndex = '1050';
    modal.querySelector('div').style.zIndex = '1051';
}

// Close modal when clicking outside
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display === 'block') {
        closeModal();
    }
});

/**
 * LOAD FORMS WHEN ASSIGNMENT CHANGES
 */
assignmentSelect.addEventListener('change', async () => {
    formSelect.innerHTML = `<option value="">Loading forms...</option>`;
    tableBody.innerHTML = '';
    selectedStudentsDiv.innerHTML = '';
    selectedList.innerHTML = '';
    selectedStudentsSummary.style.display = 'none';
    selectedCount.textContent = '0';
    submitBtn.disabled = true;
    submitBtn.style.opacity = '0.5';
    currentStudents = [];
    selectedStudentData = {};

    openModalBtn.disabled = true;
    openModalBtn.style.opacity = '.5';

    const assignmentId = assignmentSelect.value;
    if (!assignmentId) return;

    try {
        console.log('Fetching forms for assignment:', assignmentId);
        const response = await fetch(`/assignment/forms/${assignmentId}`);
        if (!response.ok) throw new Error('Failed to fetch forms');
        const forms = await response.json();
        
        console.log('Forms received:', forms);

        formSelect.innerHTML = '<option value="">-- Select Form --</option>';

        if (!forms.length) {
            formSelect.innerHTML = `<option value="">No forms found</option>`;
            return;
        }

        forms.forEach((f, index) => {
            // Use the actual form_id as the value
            formSelect.innerHTML += `
                <option value="${f.id}">
                    ${f.form_name} (ID: ${f.id})
                </option>
            `;
        });
    } catch (error) {
        console.error('Error loading forms:', error);
        formSelect.innerHTML = '<option value="">Error loading forms</option>';
    }
});

/**
 * ENABLE MODAL BUTTON WHEN FORM IS SELECTED
 */
formSelect.addEventListener('change', function() {
    const assignmentId = assignmentSelect.value;
    const formId = formSelect.value;
    
    openModalBtn.disabled = !(assignmentId && formId);
    openModalBtn.style.opacity = (assignmentId && formId) ? '1' : '.5';
    
    console.log('Selected form ID:', formId);
});

/**
 * OPEN MODAL + LOAD STUDENTS
 */
openModalBtn.addEventListener('click', async function() {
    const formId = formSelect.value;
    const assignmentId = assignmentSelect.value;

    if (!assignmentId || !formId) {
        alert('Please select assignment and form');
        return;
    }

    tableBody.innerHTML = `<tr><td colspan="9" style="padding:20px;text-align:center;">Loading students...</td></tr>`;

    try {
        // console.log(`Fetching from: /form/${formId}/students`);
        const response = await fetch(`/form/students/${formId}`);
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
        }
        
        const students = await response.json();
        console.log('Students received:', students);
        
        currentStudents = students || [];

        if (!currentStudents.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="9" style="padding:20px;text-align:center;">
                        No students found for this form.
                    </td>
                </tr>`;
        } else {
            renderStudents(currentStudents);
        }
        
        // Show the modal with high z-index
        modal.style.display = 'block';
        ensureModalOnTop();
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = '15px'; // Prevent layout shift
        
    } catch (error) {
        console.error('Error loading students:', error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="9" style="padding:20px;text-align:center;color:red;">
                    Error: ${error.message}<br>
                    <small>Check console for details</small>
                </td>
            </tr>`;
        
        // Still show modal with error
        modal.style.display = 'block';
        ensureModalOnTop();
        document.body.style.overflow = 'hidden';
    }
});

/**
 * RENDER STUDENTS IN TABLE
 */
function renderStudents(students) {
    tableBody.innerHTML = '';
    
    students.forEach(s => {
        const row = document.createElement('tr');
        const studentId = s.id || s.sr;
        const key = `student_${assignmentSelect.value}_${studentId}`;
        const isSelected = document.getElementById(key) !== null;
        
        // Format date if exists
        const createdDate = s.created_at ? s.created_at : 'N/A';
        const dob = s.dob ? s.dob : 'N/A';
        
        row.innerHTML = `
            <td style="padding:10px;vertical-align:top;">
                <input type="checkbox"
                    onchange="toggleStudent(this, '${studentId}', '${s.name}', '${s.number}')"
                    data-assignment="${assignmentSelect.value}"
                    data-student="${studentId}"
                    ${isSelected ? 'checked' : ''}>
            </td>
            <td style="padding:10px;vertical-align:top;">${studentId || 'N/A'}</td>
            <td style="padding:10px;vertical-align:top;">
                <strong>${s.name || 'N/A'}</strong><br>
                <small style="color:#666;">PAN: ${s.pannumber || 'N/A'}</small>
            </td>
            <td style="padding:10px;vertical-align:top;">${s.number || s.wnumber || 'N/A'}</td>
            <td style="padding:10px;vertical-align:top;">${s.email || 'N/A'}</td>
            <td style="padding:10px;vertical-align:top;">
                ${s.city || 'N/A'}<br>
                <small style="color:#666;">${s.address || ''}</small>
            </td>
            <td style="padding:10px;vertical-align:top;">${dob}</td>
            <td style="padding:10px;vertical-align:top;">${s.aadhar || 'N/A'}</td>
            <td style="padding:10px;vertical-align:top;">
                <small>${createdDate}</small><br>
                <small style="color:#666;">Parents: ${s.parents || 'N/A'}</small>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

/**
 * SEARCH STUDENTS
 */
studentSearch.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    
    if (!searchTerm.trim()) {
        renderStudents(currentStudents);
        return;
    }
    
    const filtered = currentStudents.filter(s => 
        (s.name && s.name.toLowerCase().includes(searchTerm)) ||
        (s.email && s.email.toLowerCase().includes(searchTerm)) ||
        (s.city && s.city.toLowerCase().includes(searchTerm)) ||
        (s.number && s.number.toLowerCase().includes(searchTerm)) ||
        (s.wnumber && s.wnumber.toLowerCase().includes(searchTerm)) ||
        (s.aadhar && s.aadhar.toLowerCase().includes(searchTerm)) ||
        (s.pannumber && s.pannumber.toLowerCase().includes(searchTerm)) ||
        (s.address && s.address.toLowerCase().includes(searchTerm))
    );
    
    renderStudents(filtered);
});

/**
 * ADD / REMOVE STUDENT
 */
function toggleStudent(el, studentId, studentName, studentMobile) {
    const assignmentId = el.dataset.assignment;
    const key = `student_${assignmentId}_${studentId}`;
    const hiddenInput = document.getElementById(key);

    if (el.checked) {
        if (!hiddenInput) {
            // Create hidden input for form submission
            const input = document.createElement('input');
            input.type = 'hidden';
            input.id = key;
            input.name = 'students[]';
            input.value = JSON.stringify({
                assignment_id: assignmentId,
                student_id: studentId
            });
            selectedStudentsDiv.appendChild(input);
            
            // Store student data for display
            selectedStudentData[studentId] = {
                id: studentId,
                name: studentName,
                mobile: studentMobile
            };
            
            // Update summary display
            updateSelectedSummary();
        }
    } else {
        if (hiddenInput) {
            hiddenInput.remove();
            // Remove from display data
            delete selectedStudentData[studentId];
            // Update summary display
            updateSelectedSummary();
        }
    }
}

/**
 * UPDATE SELECTED STUDENTS SUMMARY
 */
function updateSelectedSummary() {
    const selectedIds = Object.keys(selectedStudentData);
    const count = selectedIds.length;
    
    selectedCount.textContent = count;
    
    if (count > 0) {
        // Show summary section
        selectedStudentsSummary.style.display = 'block';
        
        // Update list
        selectedList.innerHTML = '';
        selectedIds.forEach(studentId => {
            const student = selectedStudentData[studentId];
            const div = document.createElement('div');
            div.style.padding = '5px';
            div.style.borderBottom = '1px solid #eee';
            div.innerHTML = `<strong>${student.name}</strong> (ID: ${student.id}, Mobile: ${student.mobile})`;
            selectedList.appendChild(div);
        });
        
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    } else {
        // Hide summary section
        selectedStudentsSummary.style.display = 'none';
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
    }
}

/**
 * FORM VALIDATION BEFORE SUBMIT
 */
document.getElementById('candidateForm').addEventListener('submit', function(e) {
    const assignmentId = assignmentSelect.value;
    const selectedCountValue = parseInt(selectedCount.textContent);
    
    if (!assignmentId) {
        e.preventDefault();
        alert('Please select an assignment');
        return;
    }
    
    if (selectedCountValue === 0) {
        e.preventDefault();
        alert('Please select at least one student');
        return;
    }
    
    // Optional: Show confirmation
    if (!confirm(`Are you sure you want to add ${selectedCountValue} student(s) to this batch?`)) {
        e.preventDefault();
    }
});

/**
 * CLOSE MODAL
 */
function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.body.style.paddingRight = '';
}
</script>

<style>
/* Ensure modal appears above everything */
#studentModal {
    z-index: 1050 !important;
}

#studentModal > div {
    z-index: 1051 !important;
    position: relative;
}

/* Force modal to be on top of sidebar */
body.modal-open {
    overflow: hidden;
    padding-right: 15px; /* Compensate for scrollbar */
}

/* If sidebar has high z-index, override it */
.sidebar, .nav-sidebar, aside, [class*="sidebar"] {
    z-index: 1040 !important;
}
</style>
@endsection