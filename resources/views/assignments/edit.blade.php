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
    background: #fff;
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
    inset: 0;
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

.hr-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

.hr-item:hover {
    background: #f3f4f6;
}
</style>

<div class="page-wrap">
    <form action="{{ route('assignments.update',$assignment->id) }}" method="POST" class="assignment-card">
        @csrf
        @method('PUT')

        <div class="form-header">
            <h2>✏️ Edit Assignment</h2>
            <a href="{{ route('assignments.index') }}" class="back-btn">← Back</a>
        </div>

        <div class="form-grid">

            <div class="form-group">
                <label>Assignment Name *</label>
                <input type="text" name="assignment_name" class="form-control" required
                    value="{{ old('assignment_name',$assignment->assignment_name) }}">
            </div>

            <div class="form-group">
                <label>Assignment Date *</label>
                <input type="date" name="date" class="form-control" required
                    value="{{ old('date',$assignment->date?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label>Deadline *</label>
                <input type="date" name="deadline" class="form-control" required
                    value="{{ old('deadline',$assignment->deadline_date?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label>Format *</label>
                <select name="format_id" class="form-control" required>
                    <option value="">Select Format</option>
                    @foreach($formats as $format)
                    <option value="{{ $format->id }}" {{ $assignment->format_id==$format->id?'selected':'' }}>
                        {{ $format->type }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Requirement *</label>
                <input type="number" name="requirement" class="form-control" required
                    value="{{ old('requirement',$assignment->requirement) }}">
            </div>

            <div class="form-group">
                <label>State</label>

                <select name="state" id="state" class="form-control" data-old="{{ old('state', $assignment->state) }}">
                    <option value="">Select State</option>
                </select>
            </div>

            <div class="form-group">
                <label>City/District</label>

                <select name="district" id="city" class="form-control"
                    data-old="{{ old('district', $assignment->district) }}">
                    <option value="">Select District</option>
                </select>
            </div>

            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" class="form-control" required
                    value="{{ old('location',$assignment->location) }}">
            </div>

            <div class="form-group">
                <label>Batch Type</label>
                <select name="batch_type" class="form-control">
                    <option value="">Select Batch Type</option>
                    <option value="NSO" {{ $assignment->batch_type=='NSO'?'selected':'' }}>NSO</option>
                    <option value="Attrition" {{ $assignment->batch_type=='Attrition'?'selected':'' }}>Attrition
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label>Sourcing Model</label>
                <input type="text" name="sourcing_machine" class="form-control"
                    value="{{ old('sourcing_machine',$assignment->sourcing_machine) }}">
            </div>

            <div class="form-group">
                <label>Business</label>
                <select name="business" class="form-control">
                    <option value="">Select Business</option>
                    @foreach(['SMART','SP','FRESH','TST','FOOTPRINT'] as $b)
                    <option value="{{ $b }}" {{ $assignment->business==$b?'selected':'' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Region</label>
                <select name="region" class="form-control">
                    <option value="">Select Region</option>
                    @foreach(['North','South','East','West','Central'] as $r)
                    <option value="{{ $r }}" {{ $assignment->region==$r?'selected':'' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Position Name</label>
                <input type="text" name="position_name" class="form-control"
                    value="{{ old('position_name',$assignment->position_name) }}">
            </div>

            <div class="form-group">
                <label>Monthly CTC</label>
                <input type="number" step="0.01" name="monthly_ctc" class="form-control"
                    value="{{ old('monthly_ctc',$assignment->monthly_ctc) }}">
            </div>

            <div class="form-group">
                <label>Level</label>
                <input type="text" name="level" class="form-control" value="{{ old('level',$assignment->level) }}">
            </div>

            <div class="form-group">
                <label>FT/PT</label>
                <select name="ft_pt" class="form-control">
                    <option value="">Select</option>
                    <option value="FT" {{ $assignment->ft_pt=='FT'?'selected':'' }}>Full-Time</option>
                    <option value="PT" {{ $assignment->ft_pt=='PT'?'selected':'' }}>Part-Time</option>
                </select>
            </div>

            <div class="form-group">
                <label>Min Education</label>
                <input type="text" name="minimum_education_qualification" class="form-control"
                    value="{{ old('minimum_education_qualification',$assignment->minimum_education_qualification) }}">
            </div>

            <div class="form-group">
                <label>Work Experience</label>
                <input type="text" name="work_experience" class="form-control"
                    value="{{ old('work_experience',$assignment->work_experience) }}">
            </div>

        </div>

        {{-- HR SECTION --}}
        <div class="section">
            <h3 style="color:#16a34a;">HR Details</h3>
            <button type="button" onclick="openHrModal()" class="back-btn" style="background:#22c55e;color:#fff;">➕
                Select HR</button>

            <input type="hidden" name="hr_id" id="selectedHrId" value="{{ $assignment->hr_id }}">

            <div id="hrCard" class="hr-card">
                <strong id="hrName">{{ optional($assignment->hr)->name }}</strong><br>
                <span id="hrMobile">{{ optional($assignment->hr)->mobile }}</span> ·
                <span id="hrEmail">{{ optional($assignment->hr)->email }}</span> ·
                <span id="hrState">{{ optional($assignment->hr)->state }}</span>
            </div>
        </div>

        {{-- STORE MANAGER --}}
        <div class="section">
            <h3 style="color:#2563eb;">Store Manager</h3>
            <div class="form-grid">
                <input type="text" name="sm_name" class="form-control" placeholder="Name"
                    value="{{ $assignment->sm_name }}">
                <input type="text" name="sm_mobile" class="form-control" placeholder="Mobile"
                    value="{{ $assignment->sm_mobile }}">
                <input type="email" name="sm_email" class="form-control" placeholder="Email"
                    value="{{ $assignment->sm_email }}">
                <input type="text" name="store_code" class="form-control" placeholder="Store Code"
                    value="{{ $assignment->store_code }}">
            </div>
        </div>

        <button class="submit-btn">Update Assignment</button>

    </form>
</div>

{{-- HR MODAL --}}
<div id="hrModal" class="modal-overlay">
    <div class="modal-box">
        <h3>Select HR</h3>
        <input type="text" id="hrSearch" placeholder="Search..." class="form-control" style="margin-bottom:10px;">
        <div id="hrList"></div>
        <button onclick="closeHrModal()" class="back-btn"
            style="background:#ef4444;color:#fff;margin-top:10px;">Close</button>
    </div>
</div>




<script>
window.stateDropdownId = "state";
window.cityDropdownId = "city";

// old/edit values
window.selectedState = "{{ old('state', $assignment->state) }}";
window.selectedDistrict = "{{ old('district', $assignment->district) }}";
</script>

<script src="{{ asset('js/state.js') }}"></script>

<script>
window.openHrModal = function() {
    document.getElementById('hrModal').style.display = 'flex';
    fetch('/api/hrs').then(r => r.json()).then(data => {
        let html = '';
        data.forEach(hr => {
            html += `<div class="hr-item" onclick="selectHr(${hr.id},'${hr.name}','${hr.mobile}','${hr.email}','${hr.state}')">
<strong>${hr.name}</strong><br><small>${hr.mobile} | ${hr.email} | ${hr.state}</small></div>`;
        });
        document.getElementById('hrList').innerHTML = html;
    });
}

window.closeHrModal = function() {
    document.getElementById('hrModal').style.display = 'none';
}

window.selectHr = function(id, name, mobile, email, state) {
document.getElementById('selectedHrId').value = id;
document.getElementById('hrName').innerText = name;
document.getElementById('hrMobile').innerText = mobile;
document.getElementById('hrEmail').innerText = email;
document.getElementById('hrState').innerText = state;
}

});
</script>

@endsection