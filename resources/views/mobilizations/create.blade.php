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
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
}

.upload-label {
    font-size: 13px;
    font-weight: 600;
    color: #4338ca;
    margin-bottom: 6px;
    display: block;
}

.exp-box {
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #e0e7ff;
    border-left: 6px solid #6366f1;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 22px;
    position: relative;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.15);
    backdrop-filter: blur(10px);
}

.exp-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 15px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 14px;
}

.remove-exp {
    background: #ef4444;
    color: #fff;
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: 0.3s;
    white-space: nowrap;
}

.remove-exp:hover {
    background: #dc2626;
}

.tag-wrapper {
    border: 1px solid #e5e7eb;
    padding: 8px;
    border-radius: 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    min-height: 50px;
}

#language-input {
    border: none;
    outline: none;
    flex: 1;
    padding: 6px;
    min-width: 120px;
}

.lang-tag {
    background: #6366f1;
    color: #fff;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}


/* TOP BAR CONTAINER */
.form-top-bar {
    width: 100%;
    max-width: 1150px;
    margin: 0 auto 20px auto;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

/* BACK BUTTON */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s;
}

.back-btn:hover {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    transform: translateY(-1px);
}

.doc-row {
    background: #ffffff;
    padding: 18px;
    border-radius: 14px;
    margin-bottom: 18px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    font-size: 14px;
}

input[type="text"],
input[type="file"] {
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ddd;
    width: 100%;
}

.btn-back {
    padding: 12px 20px;
    border-radius: 10px;
    background: #e5e7eb;
    border: none;
    cursor: pointer;
}

.btn-submit {
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    border: none;
    cursor: pointer;
}
</style>


<!-- HEADER BAR -->
<div class="form-top-bar">

    <a href="{{ route('mobilizations.index') }}" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i>
        Back
    </a>

</div>

<div style="padding:20px; display:flex; justify-content:center; width:100%;">




    <form method="POST" action="{{ route('mobilizations.store') }}" enctype="multipart/form-data"
        style="width:100%; max-width:1150px; background:rgba(255,255,255,0.85); padding:32px 40px; border-radius:18px; backdrop-filter:blur(14px); box-shadow:0 20px 40px rgba(0,0,0,0.08);">

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

        {{-- FILE UPLOAD INFO --}}
        <div
            style="margin-bottom:20px; padding:14px 18px; border-radius:12px; background:#dbeafe; border:1px solid #bfdbfe; color:#1e40af; font-size:13px;">
            <i class="fa-solid fa-info-circle"></i>
            <strong>File Upload Guidelines:</strong> Only JPG, PNG, and PDF files are accepted. Maximum file size: 5MB
            per file.
        </div>



        <!-- IDENTIFICATION REMARK SECTION -->
        <div class="step active" id="step-identification" style="margin-bottom:24px;">

            <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                Identification Remark
            </h2>

            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px;">

                <div class="field" style="grid-column: 1 / -1;">

                    <input type="text" name="identification_remark" maxlength="255"
                        placeholder="Enter identification remark"
                        style="width:100%; padding:12px; border-radius:12px; border:1px solid #e5e7eb;">

                </div>

            </div>

        </div>


        {{-- STEP 1: PERSONAL DETAILS --}}
        <div class="step active" id="step-1" style="margin-bottom:24px;">
            <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                Step 1: Personal Details
            </h2>

            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px;">

                <div class="field">
                    <label>Full Name <span style="color:red">*</span></label>
                    <input type="text" name="name" required>
                </div>

                <div class="field">
                    <label>Father Name</label>
                    <input type="text" name="father_name">
                </div>

                <div class="field">
                    <label>Mother Name</label>
                    <input type="text" name="mother_name">
                </div>

                <div class="field">
                    <label>Email <span style="color:red">*</span></label>
                    <input type="email" name="email" required>
                </div>

                <div class="field">
                    <label>Mobile</label>
                    <input type="text" name="mobile">
                </div>

                <div class="field">
                    <label>WhatsApp Number</label>
                    <input type="text" name="whatsapp_number">
                </div>

                <div class="field">
                    <label>Highest Qualification</label>
                    <select name="highest_qualification">
                        <option value="">-- Select Qualification --</option>
                        <option value="10th">10th</option>
                        <option value="12th">12th</option>
                        <option value="graduation">Graduation</option>
                        <option value="pg">Post Graduation (PG)</option>
                        <option value="diploma">Diploma</option>
                    </select>
                </div>

                <div class="field">
                    <label>Job Role Category</label>
                    <select name="role_id" id="top-role" style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Job Sub Role</label>
                    <select name="sub_role_id" id="top-sub-role" style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                        <option value="">Select Sub Role</option>
                    </select>
                    <input type="hidden" id="top-sub-role-selected" value="{{ old('sub_role_id') }}">
                </div>

                <div class="field">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" id="dob" max="">
                    <script>
                    document.getElementById('dob').max = new Date().toISOString().split("T")[0];
                    </script>
                </div>

                <div class="field">
                    <label>Age</label>
                    <input type="text" id="age" readonly>
                </div>

                <div class="field">
                    <label>Gender</label>
                    <select name="gender" style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>

                <div class="field">
                    <label>Marital Status</label>
                    <select name="marital_status" style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                        <option value="">Select Marital Status</option>
                        <option>Single</option>
                        <option>Married</option>
                    </select>
                </div>



                <div class="field">
                    <label>Pincode</label>
                    <input type="text" name="pincode">
                </div>

                <div class="field">
                    <label>Category</label>
                    <select name="category">
                        <option value="">Select</option>
                        <option>SC</option>
                        <option>ST</option>
                        <option>OBC</option>
                        <option>General</option>
                    </select>
                </div>

                <div class="field">
                    <label>Religion</label>
                    <input type="text" name="religion">
                </div>

                <div class="field">
                    <label>Family Members</label>
                    <input type="number" name="family_members">
                </div>

                <div class="field">
                    <label>Dependents</label>
                    <input type="number" name="dependents">
                </div>

                <div class="field">
                    <label>Do you have a Vehicle?</label>
                    <select name="has_vehicle">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="field">
                    <label>Vehicle Details</label>
                    <input type="text" name="vehicle_details">
                </div>

                <div class="field">
                    <label>Do you have Smartphone?</label>
                    <select name="has_smartphone">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="field">
                    <label>State</label>
                    <select name="state" id="state" class="form-select"
                        style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                        <option value="">Select State</option>
                    </select>
                </div>

                <div class="field">
                    <label>City/District</label>
                    <select name="city" id="city" class="form-select"
                        style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                        <option value="">Select City</option>
                    </select>
                </div>

                <!-- Address Field (Full Width) -->
                <div class="field" style="grid-column: 1 / -1;">
                    <label>Address</label>
                    <textarea name="location" rows="3"
                        style="width:100%; padding:12px; border-radius:12px; border:1px solid #e5e7eb;"></textarea>
                </div>

            </div>



        </div>

        {{-- STEP 2: EXPERIENCE DETAILS --}}
        <div class="step" id="step-2" style="margin-bottom:24px;">
            <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">Step 2: Experience Details
            </h2>

            <div id="experience-container">
                <div class="experience-block exp-box">
                    <div class="exp-title">Experience <span class="exp-count">1</span>
                        <button type="button" class="remove-exp" onclick="removeExperience(this)">Remove</button>
                    </div>

                    <div class="grid-2">
                        <div class="field"><label>Organization</label><input type="text" name="organization[]"></div>
                        <div class="field"><label>Designation</label><input type="text" name="designation[]"></div>
                    </div>

                    <div class="grid-2">
                        <div class="field"><label>Duration</label><input type="text" name="duration[]"></div>
                        <div class="field"><label>Job Role Category</label>
                            <select name="role_category[]" class="role-category">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label>Job Sub Role</label>
                        <select name="sub_role[]" class="sub-role">
                            <option value="">Select Sub Role</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addExperience()"
                style="padding:10px 18px; background:#f1f5f9; border-radius:12px; border:none; cursor:pointer;">+ Add
                More Experience</button>

            <div class="grid-2" style="margin-top:20px;">
                <div class="field">
                    <label>Relocation Preference</label>
                    <select name="relocation">
                        <option value="">Select Preference</option>
                        <option>Same Location</option>
                        <option>Same City</option>
                        <option>Same State</option>
                        <option>North</option>
                        <option>South</option>
                        <option>East</option>
                        <option>West</option>
                        <option>Anywhere in India</option>
                    </select>
                </div>

                <div class="field">
                    <label>Languages Known</label>
                    <div class="tag-wrapper">
                        <input type="text" id="language-input" placeholder="Type & Press Enter">
                        <div id="language-tags"></div>
                        <input type="hidden" name="languages" id="languages-hidden" value="{{ old('languages') }}">
                    </div>
                </div>

                <div class="field"><label>Current Salary</label><input type="number" name="current_salary"></div>
                <div class="field"><label>Preferred Salary</label><input type="number" name="preferred_salary"></div>
            </div>
        </div>






        {{-- STEP 3: DOCUMENT UPLOAD --}}
        <div class="step" id="step-3">
            <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                Step 3: Document & Details Upload
            </h2>

            <!-- PAN -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>PAN Number</label>
                        <input type="text" name="pan_number" id="pan_number" placeholder="Enter PAN Number"
                            maxlength="10" style="text-transform: uppercase;" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                            title="Enter valid PAN (e.g., ABCDE1234F)">
                    </div>
                    <div>
                        <label>PAN Card Upload</label>
                        <input type="file" name="pan_card" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- AADHAR -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>Aadhaar Number</label>
                        <input type="text" name="aadhar_number" id="aadhar_number" placeholder="Enter Aadhaar Number"
                            maxlength="12" pattern="[0-9]{12}" inputmode="numeric"
                            title="Enter valid 12-digit Aadhaar number">
                    </div>
                    <div>
                        <label>Aadhar Front</label>
                        <input type="file" name="aadhar_front" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div>
                        <label>Aadhar Back</label>
                        <input type="file" name="aadhar_back" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- PHOTO & SIGNATURE -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>Photo Upload</label>
                        <input type="file" name="photo" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div>
                        <label>Signature Upload</label>
                        <input type="file" name="signature" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- BANK DETAILS -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>Bank Account Number</label>
                        <input type="text" name="bank_account_number" placeholder="Enter Account Number">
                    </div>
                    <div>
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" placeholder="Enter IFSC Code">
                    </div>
                    <div>
                        <label>Passbook Upload</label>
                        <input type="file" name="passbook_photo" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- 10TH -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>10th Passing Year</label>
                        <input type="text" name="tenth_passing_year" value="{{ old('tenth_passing_year') }}"
                            placeholder="Enter Passing Year" maxlength="4" pattern="\d{4}" inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,4)">
                    </div>
                    <div>
                        <label>10th Marksheet</label>
                        <input type="file" name="tenth_marksheet" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- 12TH -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>12th Passing Year</label>
                        <input type="text" name="twelfth_passing_year" placeholder="Enter Passing Year">
                    </div>
                    <div>
                        <label>12th Marksheet</label>
                        <input type="file" name="twelfth_marksheet" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- GRADUATION -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>Graduation Passing Year</label>
                        <input type="text" name="graduation_passing_year" placeholder="Enter Passing Year">
                    </div>
                    <div>
                        <label>Graduation Marksheet</label>
                        <input type="file" name="graduation_marksheet" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- POST GRADUATION -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>Post Graduation Passing Year</label>
                        <input type="text" name="post_graduation_passing_year" placeholder="Enter Passing Year">
                    </div>
                    <div>
                        <label>Post Graduation Marksheet</label>
                        <input type="file" name="post_graduation_marksheet" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>

            <!-- OTHER DOCUMENTS -->
            <div class="doc-row">
                <div class="grid">
                    <div>
                        <label>Driving License</label>
                        <input type="file" name="driving_license" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div>
                        <label>Experience Letter</label>
                        <input type="file" name="experience_letter" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>


        </div>



        {{-- STEP 4: REFERENCES --}}
        <div class="step" id="step-4" style="margin-top:30px;">
            <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                Step 4: References
            </h2>

            <div id="reference-container">

                <div class="reference-block exp-box">
                    <div class="exp-title">
                        Reference <span class="ref-count">1</span>
                        <button type="button" class="remove-exp" onclick="removeReference(this)">Remove</button>
                    </div>

                    <div class="grid">
                        <input type="text" name="reference_person[]" placeholder="Reference Person">
                        <input type="text" name="reference_mobile[]" placeholder="Mobile">
                        <input type="email" name="reference_email[]" placeholder="Email">
                        <input type="text" name="reference_designation[]" placeholder="Designation">
                        <input type="text" name="reference_organization[]" placeholder="Organization">
                        <input type="text" name="reference_detail[]" placeholder="Details">
                    </div>
                </div>

            </div>

            <button type="button" onclick="addReference()"
                style="margin-top:15px; padding:10px 16px; border-radius:10px;">
                + Add More Reference
            </button>



            <div style="margin-top:25px; display:flex; justify-content:space-between;">
                <button type="button" onclick="prevStep(2)" class="btn-back">← Back</button>
                <button type="submit" class="btn-submit">Submit</button>
            </div>
        </div>

    </form>
</div>

{{-- JS --}}
<script>
function nextStep(step) {
    document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
    document.getElementById('step-' + step).classList.add('active');
}

function prevStep(step) {
    nextStep(step);
}


function calculateAge() {
    const dobField = document.getElementById('dob');
    const ageField = document.getElementById('age');

    if (!dobField.value) return;

    const dob = new Date(dobField.value);
    const diff = Date.now() - dob.getTime();
    const ageDate = new Date(diff);
    ageField.value = Math.abs(ageDate.getUTCFullYear() - 1970);
}

// run when DOB changes
document.getElementById('dob').addEventListener('change', calculateAge);

// run on page load (IMPORTANT for edit page)
document.addEventListener('DOMContentLoaded', calculateAge);

function addExperience() {
    let container = document.getElementById('experience-container');
    let block = document.querySelector('.experience-block').cloneNode(true);
    block.querySelectorAll('input').forEach(i => i.value = '');
    block.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    block.querySelector('.sub-role').innerHTML = '<option value="">Select Sub Role</option>';
    container.appendChild(block);
    updateExperienceNumbers();
}

function removeExperience(btn) {
    let container = document.getElementById('experience-container');
    if (container.children.length > 1) {
        btn.closest('.experience-block').remove();
        updateExperienceNumbers();
    }
}

function updateExperienceNumbers() {
    document.querySelectorAll('#experience-container .experience-block').forEach((block, index) => {
        block.querySelector('.exp-count').innerText = index + 1;
    });
}

// State/City load
document.addEventListener("DOMContentLoaded", function() {
    const stateSelect = document.getElementById("state");
    const citySelect = document.getElementById("city");
    fetch("/states").then(res => res.json()).then(states => {
        stateSelect.innerHTML = '<option value="">Select State</option>';
        states.forEach(state => stateSelect.innerHTML +=
            `<option value="${state.iso2}">${state.name}</option>`);
    });
    stateSelect.addEventListener("change", function() {
        const stateCode = this.value;
        citySelect.innerHTML = '<option>Loading...</option>';
        if (!stateCode) {
            citySelect.innerHTML = '<option value="">Select City</option>';
            return;
        }
        fetch(`/districts/${stateCode}`).then(res => res.json()).then(cities => {
            citySelect.innerHTML = '<option value="">Select City</option>';
            cities.forEach(city => citySelect.innerHTML +=
                `<option value="${city.name}">${city.name}</option>`);
        });
    });
});

// Role → Subrole dynamic
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('role-category')) {
        let roleId = e.target.value;
        let subRoleDropdown = e.target.closest('.experience-block').querySelector('.sub-role');
        subRoleDropdown.innerHTML = '<option>Loading...</option>';
        fetch('/get-subroles/' + roleId).then(res => res.json()).then(data => {
            subRoleDropdown.innerHTML = '<option value="">Select Sub Role</option>';
            data.forEach(sub => subRoleDropdown.innerHTML +=
                `<option value="${sub.id}">${sub.name}</option>`);
        });
    }
});

const topRoleSelect = document.getElementById('top-role');
const topSubRoleSelected = document.getElementById('top-sub-role-selected');
if (topRoleSelect) {
    const loadSubRoles = (roleId, selectedValue = '') => {
        const subRoleSelect = document.getElementById('top-sub-role');
        subRoleSelect.innerHTML = '<option>Loading...</option>';

        if (!roleId) {
            subRoleSelect.innerHTML = '<option value="">Select Sub Role</option>';
            return;
        }

        fetch('/get-subroles/' + roleId).then(res => res.json()).then(data => {
            subRoleSelect.innerHTML = '<option value="">Select Sub Role</option>';
            data.forEach(sub => {
                subRoleSelect.innerHTML += `<option value="${sub.id}" ${selectedValue == sub.id ? 'selected' : ''}>${sub.name}</option>`;
            });
        });
    };

    topRoleSelect.addEventListener('change', function() {
        loadSubRoles(this.value);
    });

    if (topRoleSelect.value && topSubRoleSelected?.value) {
        loadSubRoles(topRoleSelect.value, topSubRoleSelected.value);
    }
}

// Language tags
let input = document.getElementById('language-input');
let tagsBox = document.getElementById('language-tags');
let hidden = document.getElementById('languages-hidden');
let languages = [];

if (hidden.value !== '') {
    try {
        const parsed = JSON.parse(hidden.value);
        if (Array.isArray(parsed)) {
            languages = parsed;
        } else {
            languages = hidden.value.split(',').map(lang => lang.trim()).filter(Boolean);
        }
    } catch (error) {
        languages = hidden.value.split(',').map(lang => lang.trim()).filter(Boolean);
    }
}

renderTags();
input.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        let val = input.value.trim();
        if (val && !languages.includes(val)) {
            languages.push(val);
            input.value = '';
            renderTags();
        }
    }
});

function renderTags() {
    tagsBox.innerHTML = '';
    languages.forEach((lang, i) => {
        let tag = document.createElement('div');
        tag.className = 'lang-tag';
        tag.innerHTML = `${lang} <span onclick="removeLang(${i})">×</span>`;
        tagsBox.appendChild(tag);
    });
    // hidden.value = languages.join(',');
    hidden.value = JSON.stringify(languages);
}

function removeLang(index) {
    languages.splice(index, 1);
    renderTags();
}




function addReference() {
    let container = document.getElementById('reference-container');
    let block = document.querySelector('.reference-block').cloneNode(true);

    block.querySelectorAll('input').forEach(i => i.value = '');

    container.appendChild(block);
    updateReferenceNumbers();
}

function removeReference(btn) {
    let container = document.getElementById('reference-container');
    if (container.children.length > 1) {
        btn.closest('.reference-block').remove();
        updateReferenceNumbers();
    }
}

function updateReferenceNumbers() {
    document.querySelectorAll('#reference-container .reference-block')
        .forEach((block, index) => {
            block.querySelector('.ref-count').innerText = index + 1;
        });
}
</script>
@endsection