@extends('layouts.app')

@section('content')

<style>
.page-wrap {
    padding: 20px;
    display: flex;
    justify-content: center;
}

.assignment-card {
    width: 100%;
    max-width: 1150px;
    background: #ffffff;
    padding: 32px 40px;
    border-radius: 18px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.form-header h2 {
    font-size: 26px;
    font-weight: 700;
    color: #4f46e5;
    /* background:linear-gradient(135deg,#6366f1,#ec4899); */
}

.back-btn {
    padding: 10px 18px;
    background: #f1f5f9;
    color: #4f46e5;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

.form-group label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
    color: #4338ca;
}

.form-control {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.section {
    margin-top: 35px;
}

.hr-card {
    display: none;
    margin-top: 12px;
    padding: 14px;
    border-radius: 12px;
    background: #f0fdf4;
}

.submit-btn {
    margin-top: 40px;
    padding: 16px;
    width: 100%;
    font-size: 16px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    font-weight: 700;
    border-radius: 14px;
    border: none;
}

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
    background: #fff;
    width: 500px;
    max-height: 80vh;
    overflow: auto;
    border-radius: 14px;
    padding: 20px;
}
</style>

<div class="page-wrap">
    <form action="{{ route('assignments.store') }}" method="POST" class="assignment-card">

        @csrf

        @if ($errors->any())
        <div
            style="margin-bottom:24px; padding:16px; border-radius:14px; background:#fee2e2; border:1px solid #fecaca; color:#991b1b;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top:8px; padding-left:18px;">
                @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-header">
            <h2><i class="fa-solid fa-clipboard-list"></i> Add Assignment</h2>
            <a href="{{ route('assignments.index') }}" class="back-btn">← Back</a>
        </div>

        <div class="form-grid">

            <div class="form-group">
                <label>Assignment Name *</label>
                <input type="text" name="assignment_name" required class="form-control">
            </div>

            <div class="form-group">
                <label>Assignment Date *</label>
                <input type="date" name="date" required class="form-control">
            </div>

            <div class="form-group">
                <label>Deadline *</label>
                <input type="date" name="deadline" required class="form-control">
            </div>

            <div class="form-group">
                <label>Format *</label>
                <select name="format_id" required class="form-control">
                    <option value="">Select Format</option>
                    @foreach($formats as $format)
                    <option value="{{ $format->id }}">{{ $format->type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Requirement *</label>
                <input type="number" name="requirement" required class="form-control">
            </div>

          <div class="field">
    <label>State</label>

    <select name="state"
            id="filterState"
            class="form-select"
            style="padding:6px; border-radius:6px; border:1px solid #e5e7eb;">

        <option value="">Select State</option>

    </select>
</div>

<div class="field">
    <label>City/District</label>

    <select name="district"
            id="filterDistrict"
            class="form-select"
            style="padding:6px; border-radius:6px; border:1px solid #e5e7eb;">

        <option value="">Select City</option>

    </select>
</div>


            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" required class="form-control">
            </div>
            <div class="form-group">
                <label>Batch Type</label>
                <select name="batch_type" class="form-control">
                    <option value="">Select Batch Type</option>
                    <option value="NSO">NSO</option>
                    <option value="Attrition">Attrition</option>
                </select>
            </div>


            <div class="form-group">
                <label>Sourcing Model</label>
                <input type="text" name="sourcing_machine" class="form-control">
            </div>
            <div class="form-group">
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
            <div class="form-group">
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
            <div class="form-group">
                <label>Position Name</label>
                <input type="text" name="position_name" class="form-control">
            </div>




            <div class="form-group">
                <label>Monthly CTC</label>
                <input type="number" step="0.01" name="monthly_ctc" class="form-control">
            </div>
            <div class="form-group">
                <label>Level</label>
                <input type="text" name="level" class="form-control">
            </div>




            <div class="form-group">
                <label>FT/PT</label>
                <select name="ft_pt" class="form-control">
                    <option value="">Select FT/PT</option>
                    <option value="FT">Full-Time</option>
                    <option value="PT">Part-Time</option>
                </select>
            </div>

            <div class="form-group">
                <label>Min Education </label>
                <input type="text" name="minimum_education_qualification" class="form-control">
            </div>

            <div class="form-group">
                <label>Work Experience</label>
                <input type="text" name="work_experience" class="form-control">
            </div>

        </div>

        <div class="section">
            <h3 style="color:#16a34a;"><i class="fa-solid fa-user-tie"></i> HR Details</h3>

            <button type="button" onclick="openHrModal()" class="back-btn"
                style="background:#22c55e; color:#fff; margin-top:8px;">
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

        <div class="section">
            <h3 style="color:#2563eb;"><i class="fa-solid fa-store"></i> Store Manager Details</h3>

            <div class="form-grid">
                <input type="text" name="sm_name" placeholder="Name" class="form-control">
                <input type="text" name="sm_mobile" placeholder="Mobile" class="form-control">
                <input type="email" name="sm_email" placeholder="Email" class="form-control">
                <input type="text" name="store_code" placeholder="store_code" class="form-control">
            </div>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fa-solid fa-circle-plus"></i> Add Assignment
        </button>

    </form>
</div>

<div id="hrModal" class="modal-overlay">
    <div class="modal-box">
        <h3 style="margin-bottom:10px; color:#16a34a;">Select HR</h3>

        <input type="text" id="hrSearch" placeholder="Search HR..." class="form-control" style="margin-bottom:10px;">

        <div id="hrList"></div>

        <button onclick="closeHrModal()" class="back-btn"
            style="margin-top:10px; background:#ef4444; color:#fff;">Close</button>
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