@extends('layouts.app')

@section('content')

<style>
.field {
    display: flex;
    flex-direction: column;
}

.field label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #4338ca;
}

.field input,
.field select {
    padding: 6px;
    border-radius: 4px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
}

/* BACK BUTTON */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s;
}

.back-btn:hover {
    transform: translateY(-1px);
}

/* FILE UPLOAD INFO STYLE (reused) */
.info-box {
    margin-bottom: 20px;
    padding: 14px 18px;
    border-radius: 12px;
    background: #dbeafe;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    font-size: 13px;
}

/* SECTION HEADINGS */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 8px;
    margin-top: 24px;
}

/* GRID SYSTEM */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 6px;
    margin-bottom: 8px;
}

/* MODAL OVERLAY */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(14px);
    width: 500px;
    max-height: 80vh;
    overflow: auto;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    border: 1px solid #e0e7ff;
}

.modal-box h3 {
    font-size: 18px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 16px;
}

.modal-box .back-btn {
    background: #ef4444;
    margin-top: 16px;
}

/* HR CARD */
.hr-card {
    display: none;
    margin-top: 12px;
    padding: 16px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #e0e7ff;
    border-left: 6px solid #6366f1;
    backdrop-filter: blur(10px);
    font-size: 13px;
}

.hr-card strong {
    color: #4f46e5;
}

/* BUTTON STYLES */
.btn-select-hr {
    padding: 10px 18px;
    font-size: 12px;
    background: #f1f5f9;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    color: #4338ca;
    margin-top: 8px;
}

.submit-btn {
    margin-top: 30px;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    border: none;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

/* TYPOGRAPHY */
h2, h3 {
    font-weight: 700;
}

/* INHERITED MOBILIZATION STYLES */
.exp-box {
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #e0e7ff;
    border-left: 6px solid #6366f1;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 22px;
    position: relative;
    box-shadow: 1px -1px 20px rgba(99, 102, 241, 0.15);
    backdrop-filter: blur(10px);
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 11px;
}

input[type="text"],
input[type="file"],
input[type="number"],
input[type="date"],
input[type="email"],
select {
    padding: 6px 8px;
    font-size: 12px;
    border-radius: 6px;
    border: 1px solid #ddd;
    width: 100%;
    height: 42px;
    box-sizing: border-box;
}
</style>

<div style="display:flex; justify-content:center; width:100%;">

    <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data"
        style="width:100%; background:rgba(255,255,255,0.85); padding:12px; border-radius:18px; backdrop-filter:blur(14px); box-shadow:0 20px 40px rgba(0,0,0,0.08);">

        @csrf

        {{-- ERRORS --}}
        @if ($errors->any())
        <div
            style="margin-bottom:24px; padding:16px 18px; border-radius:14px; background:#fee2e2; border:1px solid #fecaca; color:#991b1b;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top:8px; padding-left:18px;">
                @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; gap:12px; ">

            {{-- HEADER BAR --}}
            <div style="width:100%;">
                <div class="form-top-bar" style="display: flex; justify-content: flex-end;">
                    <a href="{{ route('assignments.index') }}" class="back-btn">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <h2 style="font-size:18px; font-weight:700; color:#4f46e5; margin-bottom:8px;">
                <i class="fa-solid fa-clipboard-list"></i> Add Assignment
            </h2>
        </div>

        {{-- FILE UPLOAD INFO BOX (optional, for consistency) --}}
        <div class="info-box">
            <i class="fa-solid fa-info-circle"></i>
            <strong>Guidelines:</strong> Fill all required fields marked with <span style="color:red">*</span>.
        </div>

        {{-- ASSIGNMENT DETAILS SECTION --}}
        <div class="step active" style="margin-bottom:24px;">
            <h2 class="section-title">Assignment Details</h2>
            <div class="form-grid">
                <div class="field">
                    <label>Assignment Name <span style="color:red">*</span></label>
                    <input type="text" name="assignment_name" required class="form-control">
                </div>

                <div class="field">
                    <label>Assignment Date <span style="color:red">*</span></label>
                    <input type="date" name="date" required class="form-control">
                </div>

                <div class="field">
                    <label>Deadline <span style="color:red">*</span></label>
                    <input type="date" name="deadline" required class="form-control">
                </div>

                <div class="field">
                    <label>Format <span style="color:red">*</span></label>
                    <select name="format_id" required class="form-control">
                        <option value="">Select Format</option>
                        @foreach($formats as $format)
                        <option value="{{ $format->id }}">{{ $format->type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Requirement <span style="color:red">*</span></label>
                    <input type="number" name="requirement" required class="form-control">
                </div>

                <div class="field">
                    <label>State</label>
                    <select name="state" id="filterState" class="form-select"
                        style="padding:6px; border-radius:6px; border:1px solid #e5e7eb;">
                        <option value="">Select State</option>
                    </select>
                </div>

                <div class="field">
                    <label>City/District</label>
                    <select name="district" id="filterDistrict" class="form-select"
                        style="padding:6px; border-radius:6px; border:1px solid #e5e7eb;">
                        <option value="">Select City</option>
                    </select>
                </div>

                <div class="field">
                    <label>Location <span style="color:red">*</span></label>
                    <input type="text" name="location" required class="form-control">
                </div>

                <div class="field">
                    <label>Batch Type</label>
                    <select name="batch_type" class="form-control">
                        <option value="">Select Batch Type</option>
                        <option value="NSO">NSO</option>
                        <option value="Attrition">Attrition</option>
                    </select>
                </div>

                <div class="field">
                    <label>Sourcing Model</label>
                    <input type="text" name="sourcing_machine" class="form-control">
                </div>

                <div class="field">
                    <label>Business</label>
                    <select name="business" class="form-control">
                        <option value="">Select Business</option>
                        <option value="SMART">SMART</option>
                        <option value="SP">SP</option>
                        <option value="FRESH">FRESH</option>
                        <option value="TST">TST</option>
                        <option value="FOOTPRINT">FOOTPRINT</option>
                    </select>
                </div>

                <div class="field">
                    <label>Region</label>
                    <select name="region" class="form-control">
                        <option value="">Select Region</option>
                        <option value="North">North</option>
                        <option value="South">South</option>
                        <option value="East">East</option>
                        <option value="West">West</option>
                        <option value="Central">Central</option>
                    </select>
                </div>

                <div class="field">
                    <label>Position Name</label>
                    <input type="text" name="position_name" class="form-control">
                </div>

                <div class="field">
                    <label>Monthly CTC</label>
                    <input type="number" step="0.01" name="monthly_ctc" class="form-control">
                </div>

                <div class="field">
                    <label>Level</label>
                    <input type="text" name="level" class="form-control">
                </div>

                <div class="field">
                    <label>FT/PT</label>
                    <select name="ft_pt" class="form-control">
                        <option value="">Select FT/PT</option>
                        <option value="FT">Full-Time</option>
                        <option value="PT">Part-Time</option>
                    </select>
                </div>

                <div class="field">
                    <label>Min Education</label>
                    <input type="text" name="minimum_education_qualification" class="form-control">
                </div>

                <div class="field">
                    <label>Work Experience</label>
                    <input type="text" name="work_experience" class="form-control">
                </div>
            </div>
        </div>

        {{-- HR DETAILS SECTION --}}
        <div class="step active" style="margin-bottom:24px;">
            <h2 class="section-title"><i class="fa-solid fa-user-tie"></i> HR Details</h2>

            <button type="button" onclick="openHrModal()" class="btn-select-hr">
                ➕ Select HR
            </button>

            <input type="hidden" name="hr_id" id="selectedHrId">

            <div id="hrCard" class="hr-card">
                <strong id="hrName"></strong><br>
                <span id="hrMobile"></span> ·
                <span id="hrEmail"></span> ·
                <span id="hrState"></span>
            </div>
        </div>

        {{-- STORE MANAGER DETAILS SECTION --}}
        <div class="step active" style="margin-bottom:24px;">
            <h2 class="section-title"><i class="fa-solid fa-store"></i> Store Manager Details</h2>
            <div class="form-grid">
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="sm_name" placeholder="Name" class="form-control">
                </div>
                <div class="field">
                    <label>Mobile</label>
                    <input type="text" name="sm_mobile" placeholder="Mobile" class="form-control">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="sm_email" placeholder="Email" class="form-control">
                </div>
                <div class="field">
                    <label>Store Code</label>
                    <input type="text" name="store_code" placeholder="store_code" class="form-control">
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fa-solid fa-circle-plus"></i> Add Assignment
        </button>

    </form>
</div>

{{-- HR MODAL (Styled consistently) --}}
<div id="hrModal" class="modal-overlay">
    <div class="modal-box">
        <h3><i class="fa-solid fa-user-tie"></i> Select HR</h3>

        <div class="field">
            <label>Search HR</label>
            <input type="text" id="hrSearch" placeholder="Search HR..." class="form-control">
        </div>

        <div id="hrList" style="margin-top: 15px;"></div>

        <button onclick="closeHrModal()" class="back-btn" style="background:#ef4444; margin-top:16px;">
            Close
        </button>
    </div>
</div>

<script>


function openHrModal() {
    document.getElementById('hrModal').style.display = 'flex';

    fetch('/api/hrs')
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(hr => {
                html += `
<div onclick="selectHr(${hr.id}, '${hr.name}', '${hr.mobile}', '${hr.email}', '${hr.state}')"
style="padding:10px; border-bottom:1px solid #eee; cursor:pointer;">
<strong>${hr.name}</strong><br>
<small>${hr.mobile} | ${hr.email} | ${hr.state}</small>
</div>`;
            });
            document.getElementById('hrList').innerHTML = html;
        });
}

function closeHrModal() {
    document.getElementById('hrModal').style.display = 'none';
}

function selectHr(id, name, mobile, email, state) {
    document.getElementById('selectedHrId').value = id;
    document.getElementById('hrName').innerText = name;
    document.getElementById('hrMobile').innerText = mobile;
    document.getElementById('hrEmail').innerText = email;
    document.getElementById('hrState').innerText = state;
    document.getElementById('hrCard').style.display = 'block';
    closeHrModal();
}
</script>

<script>
    window.selectedState = "{{ old('state', request('state')) }}";
    window.selectedDistrict = "{{ old('district', request('district')) }}";
</script>
<script src="{{ asset('js/state.js') }}"></script>
@endsection