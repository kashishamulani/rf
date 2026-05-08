@extends('layouts.app')

@section('content')

<style>
/* CARD */
.page-card {
    max-width: 1100px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
}

/* HEADER */
.page-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:16px;
}

/* BUTTONS */
.btn-light {
    padding:6px 14px;
    background:#e5e7eb;
    color:#111;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
}

/* TABS */
.tabs {
    display:flex;
    gap:10px;
    margin-bottom:16px;
}

.tab-btn {
    padding:8px 16px;
    border:none;
    background:#e5e7eb;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
}

.tab-btn.active {
    background:#6366f1;
    color:#fff;
}

/* CONTENT */
.tab-content {
    display:none;
}

.tab-content.active {
    display:block;
}

/* GRID */
.grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:12px;
}

/* FIELD */
.field label {
    font-size:12px;
    font-weight:600;
    color:#6b7280;
}

.field div {
    background:#f9fafb;
    padding:8px;
    border-radius:6px;
    font-size:13px;
    color:#111827;
}
</style>

<div class="page-card">

    {{-- HEADER --}}
    <div class="page-header">
        <h2 style="font-size:20px; font-weight:700;">
            {{ $student->name }} — Full Details
        </h2>

        {{-- ✅ BACK BUTTON --}}
       <a href="{{ url()->previous() }}" class="btn-light">
    ← Back
</a>
    </div>

    {{-- TABS --}}
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab(event,'basic')">Basic</button>
        <button class="tab-btn" onclick="switchTab(event,'assignment')">Assignment</button>
        <button class="tab-btn" onclick="switchTab(event,'status')">Status</button>
    </div>

    {{-- BASIC --}}
   {{-- BASIC --}}
<div id="basic" class="tab-content active">
    <div class="grid">

        <div class="field">
            <label>Full Name</label>
            <div>{{ $student->name }}</div>
        </div>

        <div class="field">
            <label>Father Name</label>
            <div>{{ $student->father_name ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Mother Name</label>
            <div>{{ $student->mother_name ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Email</label>
            <div>{{ $student->email ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Mobile</label>
            <div>{{ $student->mobile }}</div>
        </div>

        <div class="field">
            <label>WhatsApp</label>
            <div>{{ $student->whatsapp_number ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Highest Qualification</label>
            <div>{{ $student->highest_qualification ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Date of Birth</label>
            <div>{{ $student->dob ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Gender</label>
            <div>{{ $student->gender ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Marital Status</label>
            <div>{{ $student->marital_status ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Category</label>
            <div>{{ $student->category ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Religion</label>
            <div>{{ $student->religion ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Family Members</label>
            <div>{{ $student->family_members ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Dependents</label>
            <div>{{ $student->dependents ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Has Vehicle</label>
            <div>{{ $student->has_vehicle ? 'Yes' : 'No' }}</div>
        </div>

        <div class="field">
            <label>Vehicle Details</label>
            <div>{{ $student->vehicle_details ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Has Smartphone</label>
            <div>{{ $student->has_smartphone ? 'Yes' : 'No' }}</div>
        </div>

        <div class="field">
            <label>Pincode</label>
            <div>{{ $student->pincode ?? '-' }}</div>
        </div>

        <div class="field">
            <label>State</label>
            <div>{{ $student->state ?? '-' }}</div>
        </div>

        <div class="field">
            <label>City</label>
            <div>{{ $student->city ?? '-' }}</div>
        </div>

        <div class="field" style="grid-column: span 2;">
            <label>Address</label>
            <div>{{ $student->location ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Languages</label>
            <div>
                @if($student->languages)
                    {{ is_array($student->languages) 
                        ? implode(', ', $student->languages) 
                        : $student->languages }}
                @else
                    -
                @endif
            </div>
        </div>

        <div class="field" style="grid-column: span 2;">
            <label>Identification Remark</label>
            <div>{{ $student->identification_remark ?? '-' }}</div>
        </div>

    </div>
</div>
    {{-- ASSIGNMENT --}}
    <div id="assignment" class="tab-content">
        <div class="grid">
            <div class="field">
                <label>Assignment Name</label>
                <div>{{ $assignment->assignment_name }}</div>
            </div>

            <div class="field">
                <label>Location</label>
                <div>{{ $assignment->location }}</div>
            </div>

            <div class="field">
                <label>Deadline</label>
                <div>{{ $assignment->deadline_date }}</div>
            </div>

            <div class="field">
                <label>Position</label>
                <div>{{ $assignment->position_name }}</div>
            </div>
        </div>
    </div>

    {{-- STATUS --}}
   {{-- STATUS --}}
<div id="status" class="tab-content">
    <div class="grid">

        <div class="field">
            <label>Progress</label>
            <div>{{ $data->progress->name ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Remark</label>
            <div>{{ $data->remark ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Samarth Status</label>
            <div>{{ ucfirst($data->samarth_done ?? '-') }}</div>
        </div>

        <div class="field">
            <label>Samarth Certificate</label>
            <div>
                @if($data->samarth_certificate)
                    <a href="{{ asset('uploads/samarth/'.$data->samarth_certificate) }}" target="_blank">View File</a>
                @else
                    -
                @endif
            </div>
        </div>

        <div class="field">
            <label>UAN Done</label>
            <div>{{ $data->uan_done ? 'Yes' : 'No' }}</div>
        </div>

        <div class="field">
            <label>UAN Number</label>
            <div>{{ $data->uan_number ?? '-' }}</div>
        </div>

        <div class="field">
            <label>UAN Certificate</label>
            <div>
                @if($data->uan_certificate)
                    <a href="{{ asset('uploads/uan/'.$data->uan_certificate) }}" target="_blank">View File</a>
                @else
                    -
                @endif
            </div>
        </div>

        <div class="field">
            <label>Offer Letter Given</label>
            <div>{{ $data->offer_letter_done ? 'Yes' : 'No' }}</div>
        </div>

        <div class="field">
            <label>Offer Letter Date</label>
            <div>{{ $data->offer_letter_date ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Offer Letter File</label>
            <div>
                @if($data->offer_letter_file)
                    <a href="{{ asset('uploads/offer_letters/'.$data->offer_letter_file) }}" target="_blank">View File</a>
                @else
                    -
                @endif
            </div>
        </div>

        <div class="field">
            <label>User ID</label>
            <div>{{ $data->registration_id ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Password</label>
            <div>{{ $data->registration_password ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Registration Number</label>
            <div>{{ $data->registration_number ?? '-' }}</div>
        </div>

        <div class="field">
            <label>EC Number</label>
            <div>{{ $data->ec_number ?? '-' }}</div>
        </div>

        <div class="field">
            <label>EC Date</label>
            <div>{{ $data->ec_date ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Date of Placement</label>
            <div>{{ $data->date_of_placement ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Placement Company</label>
            <div>{{ $data->placement_company ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Placement Offering</label>
            <div>{{ $data->placement_offering ?? '-' }}</div>
        </div>

        <div class="field">
            <label>Documents Submitted</label>
            <div>{{ $data->documents_done ? 'Yes' : 'No' }}</div>
        </div>

    </div>
</div>

</div>

<script>
function switchTab(event, tabId) {

    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>

@endsection