@php
use Illuminate\Support\Str;
@endphp


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
    /* color:#374151; */
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
    color: #374151;
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
    flex-shrink: 0;
}

.remove-exp:hover {
    background: #dc2626;
}


.experience-block {
    transition: 0.3s;
}

.experience-block:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(99, 102, 241, 0.20);
}

.exp-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.exp-count {
    background: #6366f1;
    color: #fff;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-left: 6px;
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
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.lang-tag span {
    cursor: pointer;
    font-weight: bold;
}

#language-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    width: 100%;
    margin-top: 4px;
}

.tag-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    border: 1px solid #e5e7eb;
    padding: 8px;
    border-radius: 12px;
    min-height: 46px;
}

#language-input {
    flex: 1;
    border: none;
    outline: none;
    min-width: 120px;
    padding: 6px;
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
    /* max-width: 1150px; */
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



<div class="form-top-bar">

    <a href="{{ route('mobilizations.index') }}" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i>
        Back
    </a>

</div>

<div style="padding:20px; display:flex; justify-content:center; width:100%;">

    <form method="POST" action="{{ route('mobilizations.update',$mobilization->id) }}" enctype="multipart/form-data"
        style="width:100%; max-width:1150px;
               background:rgba(255,255,255,0.85);
               padding:32px 40px;
               border-radius:18px;
               backdrop-filter:blur(14px);
               box-shadow:0 20px 40px rgba(0,0,0,0.08);">
        @method('PUT')

        @csrf

        {{-- ERRORS --}}
        @if ($errors->any())
        <div style="margin-bottom:24px;
            padding:16px 18px;
            border-radius:14px;
            background:#fee2e2;
            border:1px solid #fecaca;
            color:#991b1b;">
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

        {{-- STEP WRAPPER --}}
        <div class="step-wrapper">


            <!-- IDENTIFICATION REMARK SECTION -->
            <div class="step active" id="step-identification" style="margin-bottom:24px;">

                <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    Identification Remark
                </h2>

                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px;">

                    <div class="field" style="grid-column: 1 / -1;">

                        <input type="text" name="identification_remark"
                            value="{{ old('identification_remark', $mobilization->identification_remark) }}"
                            maxlength="255" placeholder="Enter identification remark"
                            style="width:100%; padding:12px; border-radius:12px; border:1px solid #e5e7eb;">

                    </div>

                </div>

            </div>

            {{-- STEP 1 --}}
            <div class="step active" id="step-1" style="margin-bottom:24px;">
                <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    Step 1: Personal Details
                </h2>

                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px;">

                    <div class="field">
                        <label>Full Name <span style="color:red">*</span></label>
                        <input type="text" name="name" value="{{ old('name',$mobilization->name) }}" required>
                    </div>

                    <div class="field">
                        <label>Father Name</label>
                        <input type="text" name="father_name"
                            value="{{ old('father_name',$mobilization->father_name) }}">
                    </div>

                    <div class="field">
                        <label>Mother Name</label>
                        <input type="text" name="mother_name"
                            value="{{ old('mother_name',$mobilization->mother_name) }}">
                    </div>

                    <div class="field">
                        <label>Email <span style="color:red">*</span></label>
                        <input type="email" name="email" value="{{ old('email',$mobilization->email) }}" required>
                    </div>

                    <div class="field">
                        <label>Mobile</label>
                        <input type="text" name="mobile" value="{{ old('mobile',$mobilization->mobile) }}">
                    </div>

                    <div class="field">
                        <label>WhatsApp Number</label>
                        <input type="text" name="whatsapp_number"
                            value="{{ old('whatsapp_number',$mobilization->whatsapp_number) }}">
                    </div>

                    <div class="field">
                        <label>Highest Qualification</label>
                        @php
                        $selectedQualification = old('highest_qualification', $mobilization->highest_qualification);
                        @endphp

                        <select name="highest_qualification">
                            <option value="">-- Select Qualification --</option>

                            <option value="10th" {{ $selectedQualification == '10th' ? 'selected' : '' }}>10th</option>

                            <option value="12th" {{ $selectedQualification == '12th' ? 'selected' : '' }}>12th</option>

                            <option value="graduation" {{ $selectedQualification == 'graduation' ? 'selected' : '' }}>
                                Graduation
                            </option>

                            <option value="pg" {{ $selectedQualification == 'pg' ? 'selected' : '' }}>
                                Post Graduation (PG)
                            </option>

                            <option value="diploma" {{ $selectedQualification == 'diploma' ? 'selected' : '' }}>
                                Diploma
                            </option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Job Role Category</label>
                        <select name="role_id" id="top-role"
                            style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id',$mobilization->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Job Sub Role</label>
                        <select name="sub_role_id" id="top-sub-role"
                            style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                            <option value="">Select Sub Role</option>
                            @foreach($subRoles as $subRole)
                            <option value="{{ $subRole->id }}"
                                {{ old('sub_role_id',$mobilization->sub_role_id) == $subRole->id ? 'selected' : '' }}>
                                {{ $subRole->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" id="dob"
                            value="{{ old('dob', optional($mobilization->dob)->format('Y-m-d')) }}">
                    </div>

                    <div class="field">
                        <label>Age</label>
                        <input type="text" id="age" value="{{ old('age',$mobilization->age) }}" readonly>
                    </div>

                    <div class="field">
                        <label>Gender</label>
                        <select name="gender" style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender',$mobilization->gender)=='Male'?'selected':'' }}>Male
                            </option>
                            <option value="Female" {{ old('gender',$mobilization->gender)=='Female'?'selected':'' }}>
                                Female</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Marital Status</label>
                        <select name="marital_status"
                            style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                            <option value="">Select Marital Status</option>
                            <option value="Single"
                                {{ old('marital_status',$mobilization->marital_status)=='Single'?'selected':'' }}>Single
                            </option>
                            <option value="Married"
                                {{ old('marital_status',$mobilization->marital_status)=='Married'?'selected':'' }}>
                                Married</option>
                        </select>
                    </div>


                    <div class="field">
                        <label>Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode',$mobilization->pincode) }}">
                    </div>

                    <div class="field">
                        <label>Category</label>
                        <select name="category" style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                            <option value="">Select</option>
                            <option value="SC" {{ old('category',$mobilization->category)=='SC'?'selected':'' }}>SC
                            </option>
                            <option value="ST" {{ old('category',$mobilization->category)=='ST'?'selected':'' }}>ST
                            </option>
                            <option value="OBC" {{ old('category',$mobilization->category)=='OBC'?'selected':'' }}>OBC
                            </option>
                            <option value="General"
                                {{ old('category',$mobilization->category)=='General'?'selected':'' }}>General</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Religion</label>
                        <input type="text" name="religion" value="{{ old('religion',$mobilization->religion) }}">
                    </div>

                    <div class="field">
                        <label>Family Members</label>
                        <input type="number" name="family_members"
                            value="{{ old('family_members',$mobilization->family_members) }}">
                    </div>

                    <div class="field">
                        <label>Dependents</label>
                        <input type="number" name="dependents"
                            value="{{ old('dependents',$mobilization->dependents) }}">
                    </div>

                    <div class="field">
                        <label>Do you have a Vehicle?</label>
                        <select name="has_vehicle" style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                            <option value="0" {{ old('has_vehicle',$mobilization->has_vehicle)=='0'?'selected':'' }}>No
                            </option>
                            <option value="1" {{ old('has_vehicle',$mobilization->has_vehicle)=='1'?'selected':'' }}>Yes
                            </option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Vehicle Details</label>
                        <input type="text" name="vehicle_details"
                            value="{{ old('vehicle_details',$mobilization->vehicle_details) }}">
                    </div>

                    <div class="field">
                        <label>Do you have Smartphone?</label>
                        <select name="has_smartphone"
                            style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">
                            <option value="0"
                                {{ old('has_smartphone',$mobilization->has_smartphone)=='0'?'selected':'' }}>No</option>
                            <option value="1"
                                {{ old('has_smartphone',$mobilization->has_smartphone)=='1'?'selected':'' }}>Yes
                            </option>
                        </select>
                    </div>

                    <div class="field">
                        <label>State</label>

                        <select name="state" id="state" data-old="{{ old('state', $mobilization->state) }}"
                            style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">

                            <option value="">Select State</option>

                        </select>
                    </div>

                    <div class="field">
                        <label>City/District</label>

                        <select name="city" id="city" data-old="{{ old('city', $mobilization->city) }}"
                            style="padding:12px; border-radius:12px; border:1px solid #e5e7eb;">

                            <option value="">Select City</option>

                        </select>
                    </div>


                    <!-- Address Field -->
                    <div class="field" style="grid-column: 1 / -1;">
                        <label>Address</label>
                        <textarea name="location" rows="3"
                            style="width:100%; padding:12px; border-radius:12px; border:1px solid #e5e7eb;">{{ old('location',$mobilization->location) }}</textarea>
                    </div>

                </div>
            </div>


            {{-- STEP 2 --}}
            <div class="step" id="step-2" style="margin-bottom:24px;">
                <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">Step 2: Experience
                    Details</h2>

                <div id="experience-container">
                    @foreach($mobilization->experiences as $i => $exp)
                    <div class="experience-block exp-box">
                        <div class="exp-title">
                            Experience <span class="exp-count">{{ $i+1 }}</span>
                            <button type="button" class="remove-exp" onclick="removeExperience(this)">Remove</button>
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label>Organization</label>
                                <input type="text" name="organization[]" value="{{ $exp->organization }}">
                            </div>

                            <div class="field">
                                <label>Designation</label>
                                <input type="text" name="designation[]" value="{{ $exp->designation }}">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label>Duration</label>
                                <input type="text" name="duration[]" value="{{ $exp->duration }}">
                            </div>

                            <div class="field">
                                <label>Job Role Category</label>
                                <select name="role_category[]" class="role-category">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $role->id==$exp->role_id?'selected':'' }}>
                                        {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label>Job Sub Role</label>
                            <select name="sub_role[]" class="sub-role" data-selected="{{ $exp->sub_role_id }}">
                                <option value="">Select Sub Role</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" onclick="addExperience()"
                    style="padding:10px 18px; background:#f1f5f9; border-radius:12px; border:none; cursor:pointer;">+
                    Add More Experience</button>

                <div class="grid-2" style="margin-top:20px;">

                    @php
                    $selectedRelocation = $mobilization->relocation;
                    if (is_array($selectedRelocation)) {
                    $selectedRelocation = reset($selectedRelocation);
                    }
                    @endphp
                    <div class="field">
                        <label>Relocation Preference</label>
                        <select name="relocation">
                            <option value="">Select Preference</option>
                            <option value="Same Location"
                                {{ $selectedRelocation == 'Same Location' ? 'selected' : '' }}>Same Location</option>
                            <option value="Same City" {{ $selectedRelocation == 'Same City' ? 'selected' : '' }}>Same
                                City</option>
                            <option value="Same State" {{ $selectedRelocation == 'Same State' ? 'selected' : '' }}>Same
                                State</option>
                            <option value="North" {{ $selectedRelocation == 'North' ? 'selected' : '' }}>North</option>
                            <option value="South" {{ $selectedRelocation == 'South' ? 'selected' : '' }}>South</option>
                            <option value="East" {{ $selectedRelocation == 'East' ? 'selected' : '' }}>East</option>
                            <option value="West" {{ $selectedRelocation == 'West' ? 'selected' : '' }}>West</option>
                            <option value="Anywhere in India"
                                {{ $selectedRelocation == 'Anywhere in India' ? 'selected' : '' }}>Anywhere in India
                            </option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Languages Known</label>
                        <div class="tag-wrapper">
                            <input type="text" id="language-input" placeholder="Type & Press Enter">
                            <div id="language-tags"></div>
                        </div>
                        <input type="hidden" name="languages" id="languages-hidden"
                            value="{{ old('languages', is_array($mobilization->languages) ? implode(',', $mobilization->languages) : $mobilization->languages) }}">
                    </div>

                    <div class="field">
                        <label>Current Salary</label>
                        <input type="number" name="current_salary"
                            value="{{ old('current_salary',$mobilization->current_salary) }}">
                    </div>

                    <div class="field">
                        <label>Preferred Salary</label>
                        <input type="number" name="preferred_salary"
                            value="{{ old('preferred_salary',$mobilization->preferred_salary) }}">
                    </div>

                </div>
            </div>
            {{-- STEP 3 --}}

            <div class="step" id="step-3">
                <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    Step 3: Document & Details Upload
                </h2>

                <!-- PAN -->
                <div class="doc-row">
                    <div class="doc-row">
                        <div class="grid">


                            <div>
                                <label>PAN Number</label>
                                <input type="text" name="pan_number" id="pan_number" placeholder="Enter PAN Number"
                                    maxlength="10" style="text-transform: uppercase;" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                    title="Enter valid PAN (e.g., ABCDE1234F)"
                                    value="{{ old('pan_number',$mobilization->documents->pan_number ?? '') }}">
                            </div>

                            <div>
                                <label>PAN Card Upload</label>
                                <input type="file" name="pan_card" accept=".jpg,.jpeg,.png,.pdf">

                                @if(!empty(optional($mobilization->documents)->pan_card))
                                <br><br>

                                {{-- IMAGE --}}
                                @if(Str::endsWith(optional($mobilization->documents)->pan_card, ['jpg','jpeg','png']))
                                <img src="{{ route('document.view', optional($mobilization->documents)->pan_card) }}"
                                    width="120" style="border-radius:8px;">
                                @endif

                                {{-- PDF --}}
                                @if(Str::endsWith(optional($mobilization->documents)->pan_card, ['pdf']))
                                <a href="{{ route('document.view', optional($mobilization->documents)->pan_card) }}"
                                    target="_blank" class="btn btn-sm btn-primary">
                                    View PAN PDF
                                </a>
                                @endif
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <!-- AADHAR -->
                <div class="doc-row">
                    <div class="grid">


                        <div>
                            <label>Aadhaar Number</label>
                            <input type="text" name="aadhar_number" id="aadhar_number"
                                placeholder="Enter Aadhaar Number" maxlength="12" pattern="[0-9]{12}"
                                inputmode="numeric" title="Enter valid 12-digit Aadhaar number"
                                value="{{ old('aadhar_number',$mobilization->documents->aadhar_number ?? '') }}">
                        </div>

                        <div>
                            <label>Aadhar Front</label>

                            @if(!empty($mobilization->documents->aadhar_front ?? ''))

                            @if(Str::endsWith($mobilization->documents->aadhar_front,['jpg','jpeg','png']))
                            <img src="{{ route('document.view',$mobilization->documents->aadhar_front) }}" width="120">
                            @else
                            <a href="{{ route('document.view',$mobilization->documents->aadhar_front) }}"
                                target="_blank">
                                View Aadhar Front
                            </a>
                            @endif

                            @endif

                            <input type="file" name="aadhar_front" accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div>
                            <label>Aadhar Back</label>

                            @if(!empty(optional($mobilization->documents)->aadhar_back))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->documents)->aadhar_back) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="aadhar_back" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>
                </div>

                <!-- PHOTO & SIGNATURE -->
                <div class="doc-row">
                    <div class="grid">
                        <div>
                            <label>Photo</label>

                            @if(!empty(optional($mobilization->documents)->photo))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->documents)->photo) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="photo" accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div>
                            <label>Signature</label>

                            @if(!empty(optional($mobilization->documents)->signature))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->documents)->signature) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="signature" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>
                </div>

                <!-- BANK DETAILS -->
                <div class="doc-row">
                    <div class="grid">
                        <div>
                            <label>Bank Account Number</label>
                            <input type="text" name="bank_account_number"
                                value="{{ old('bank_account_number',$mobilization->bank->bank_account_number ?? '') }}">
                        </div>

                        <div>
                            <label>IFSC Code</label>
                            <input type="text" name="ifsc_code"
                                value="{{ old('ifsc_code',$mobilization->bank->ifsc_code ?? '') }}">
                        </div>

                        <div>
                            <label>Passbook Photo</label>

                            @if(!empty(optional($mobilization->documents)->passbook_photo))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->documents)->passbook_photo) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="passbook_photo" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>
                </div>

                <!-- 10TH -->
                <div class="doc-row">
                    <div class="grid">
                        <div>
                            <label>10th Passing Year</label>
                            <input type="text" name="tenth_passing_year" maxlength="4" pattern="\d{4}"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,4)"
                                value="{{ old('tenth_passing_year', optional($mobilization->education)->tenth_passing_year) }}">
                        </div>

                        <div>
                            <label>10th Marksheet</label>

                            @if(!empty(optional($mobilization->education)->tenth_marksheet))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->education)->tenth_marksheet) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="tenth_marksheet">
                        </div>
                    </div>
                </div>

                <!-- 12TH -->
                <div class="doc-row">
                    <div class="grid">
                        <div>
                            <label>12th Passing Year</label>
                            <input type="text" name="twelfth_passing_year" maxlength="4" pattern="\d{4}"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,4)"
                                value="{{ old('twelfth_passing_year', optional($mobilization->education)->twelfth_passing_year) }}">
                        </div>

                        <div>
                            <label>12th Marksheet</label>

                            @if(!empty(optional($mobilization->education)->twelfth_marksheet))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->education)->twelfth_marksheet) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="twelfth_marksheet">
                        </div>
                    </div>
                </div>

                <!-- GRADUATION -->
                <div class="doc-row">
                    <div class="grid">
                        <div>
                            <label>Graduation Passing Year</label>
                            <input type="text" name="graduation_passing_year" maxlength="4" pattern="\d{4}"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,4)"
                                value="{{ old('graduation_passing_year', optional($mobilization->education)->graduation_passing_year) }}">
                        </div>

                        <div>
                            <label>Graduation Marksheet</label>

                            @if(!empty(optional($mobilization->education)->graduation_marksheet))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->education)->graduation_marksheet) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="graduation_marksheet">
                        </div>
                    </div>
                </div>

                <!-- POST GRADUATION -->
                <div class="doc-row">
                    <div class="grid">
                        <div>
                            <label>Post Graduation Passing Year</label>
                            <input type="text" name="post_graduation_passing_year" maxlength="4" pattern="\d{4}"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,4)"
                                value="{{ old('post_graduation_passing_year', optional($mobilization->education)->post_graduation_passing_year) }}">
                        </div>

                        <div>
                            <label>Post Graduation Marksheet</label>

                            @if(!empty(optional($mobilization->education)->post_graduation_marksheet))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->education)->post_graduation_marksheet) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="post_graduation_marksheet">
                        </div>
                    </div>
                </div>

                <!-- OTHER DOCUMENTS -->
                <div class="doc-row">
                    <div class="grid">
                        <div>
                            <label>Driving License</label>

                            @if(!empty(optional($mobilization->documents)->driving_license))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->documents)->driving_license) }}">View</a>
                            </p>
                            @endif

                            <input type="file" name="driving_license" accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div>
                            <label>Experience Letter</label>

                            @if(!empty(optional($mobilization->documents)->experience_letter))
                            <p class="text-xs mb-1">
                                <a target="_blank"
                                    href="{{ route('document.view', optional($mobilization->documents)->experience_letter) }}">View</a>
                            </p>
                            @endif

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

                    @forelse($mobilization->references as $index => $reference)
                    <div class="reference-block exp-box">
                        <div class="exp-title">
                            Reference <span class="ref-count">{{ $index + 1 }}</span>
                            <button type="button" class="remove-exp" onclick="removeReference(this)">Remove</button>
                        </div>

                        <div class="grid">
                            <input type="text" name="reference_person[]" value="{{ $reference->reference_person }}"
                                placeholder="Reference Person">
                            <input type="text" name="reference_mobile[]" value="{{ $reference->reference_mobile }}"
                                placeholder="Mobile">
                            <input type="email" name="reference_email[]" value="{{ $reference->reference_email }}"
                                placeholder="Email">
                            <input type="text" name="reference_designation[]"
                                value="{{ $reference->reference_designation }}" placeholder="Designation">
                            <input type="text" name="reference_organization[]"
                                value="{{ $reference->reference_organization }}" placeholder="Organization">
                            <input type="text" name="reference_detail[]" value="{{ $reference->reference_detail }}"
                                placeholder="Details">
                        </div>
                    </div>
                    @empty
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
                    @endforelse

                </div>

                <button type="button" onclick="addReference()"
                    style="margin-top:15px; padding:10px 16px; border-radius:10px;">
                    + Add More Reference
                </button>

                <div style="margin-top:25px; display:flex; justify-content:space-between;">
                    <button type="button" onclick="prevStep(2)" class="btn-back">← Back</button>
                    <button type="submit" class="btn-submit">Update Mobilization</button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function nextStep(step) {
    document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
    document.getElementById('step-' + step).classList.add('active');
}

function prevStep(step) {
    nextStep(step);
}

document.getElementById('dob').addEventListener('change', function() {
    const dob = new Date(this.value);
    const diff = Date.now() - dob.getTime();
    const ageDate = new Date(diff);
    document.getElementById('age').value = Math.abs(ageDate.getUTCFullYear() - 1970);
});


function addExperience() {

    let container = document.getElementById('experience-container');

    let firstBlock = document.querySelector('.experience-block');

    let newBlock = firstBlock.cloneNode(true);

    newBlock.querySelectorAll('input').forEach(input => {
        input.value = '';
    });

    newBlock.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
    });

    // 🔥 reset subrole selected old value
    newBlock.querySelector('.sub-role').innerHTML =
        '<option value="">Select Sub Role</option>';

    newBlock.querySelector('.sub-role').removeAttribute('data-selected');

    container.appendChild(newBlock);

    updateExperienceNumbers();
}


function removeExperience(btn) {

    let container =
        document.getElementById('experience-container');

    if (container.children.length > 1) {

        btn.closest('.experience-block').remove();
        updateExperienceNumbers();
    }
}

function updateExperienceNumbers() {

    let blocks =
        document.querySelectorAll('#experience-container .experience-block');

    blocks.forEach((block, index) => {

        block.querySelector('.exp-count').innerText = index + 1;

    });
}
</script>


<script>
    window.stateDropdownId = "state";
    window.cityDropdownId = "city";

    // old/edit values
    window.selectedState = "{{ old('state', $mobilization->state) }}";
    window.selectedDistrict = "{{ old('city', $mobilization->city) }}";
</script>

<script src="{{ asset('js/state.js') }}"></script>

<script>
document.addEventListener('change', function(e) {

    if (e.target.classList.contains('role-category')) {

        let roleId = e.target.value;

        let expBlock = e.target.closest('.experience-block');

        let subRoleDropdown = expBlock.querySelector('.sub-role');

        subRoleDropdown.innerHTML = '<option>Loading...</option>';

        if (roleId != '') {

            fetch('/get-subroles/' + roleId)
                .then(res => res.json())
                .then(data => {

                    subRoleDropdown.innerHTML = '<option value="">Select Sub Role</option>';

                    data.forEach(sub => {

                        subRoleDropdown.innerHTML +=
                            `<option value="${sub.id}">
                            ${sub.name}
                        </option>`;

                    });

                });

        } else {
            subRoleDropdown.innerHTML =
                '<option value="">Select Sub Role</option>';
        }
    }
});

const topRoleSelect = document.getElementById('top-role');
if (topRoleSelect) {
    topRoleSelect.addEventListener('change', function() {
        const roleId = this.value;
        const subRoleSelect = document.getElementById('top-sub-role');
        subRoleSelect.innerHTML = '<option>Loading...</option>';
        if (!roleId) {
            subRoleSelect.innerHTML = '<option value="">Select Sub Role</option>';
            return;
        }
        fetch('/get-subroles/' + roleId).then(res => res.json()).then(data => {
            subRoleSelect.innerHTML = '<option value="">Select Sub Role</option>';
            data.forEach(sub => {
                subRoleSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
            });
        });
    });
}
</script>

<script>
document.querySelectorAll('.role-category').forEach(function(role) {

    let roleId = role.value;

    if (roleId != '') {

        let sub =
            role.closest('.experience-block')
            .querySelector('.sub-role');

        let selected =
            sub.getAttribute('data-selected');

        fetch('/get-subroles/' + roleId)

            .then(res => res.json())

            .then(data => {

                sub.innerHTML =
                    '<option value="">Select Sub Role</option>';

                data.forEach(s => {

                    sub.innerHTML +=
                        `<option value="${s.id}"
                    ${selected==s.id?'selected':''}>
                    ${s.name}
                    </option>`;

                });

            });

    }

});
</script>

<script>
let input = document.getElementById('language-input');
let tagsBox = document.getElementById('language-tags');
let hidden = document.getElementById('languages-hidden');

let languages = [];

if (hidden.value != '') {
    languages = hidden.value.split(',');
}

renderTags();

input.addEventListener('keydown', function(e) {

    if (e.key === 'Enter') {

        e.preventDefault();

        let val = input.value.trim();

        if (val !== '' && !languages.includes(val)) {
            languages.push(val);
            input.value = '';
            renderTags();
        }

    }
});

function renderTags() {

    tagsBox.innerHTML = '';

    languages.forEach((lang, index) => {

        let tag = document.createElement('div');
        tag.className = 'lang-tag';

        tag.innerHTML = lang + ' <span onclick="removeLang(' + index + ')">×</span>';

        tagsBox.appendChild(tag);

    });

    hidden.value = languages.join(',');

}

function removeLang(index) {
    languages.splice(index, 1);
    renderTags();
}



function renderTags() {

    tagsBox.innerHTML = '';

    languages.forEach((lang, index) => {

        let tag = document.createElement('div');
        tag.className = 'lang-tag';

        tag.innerHTML =
            `${lang} <span onclick="removeLang(${index})">×</span>`;

        tagsBox.appendChild(tag);

    });

    hidden.value = languages.join(',');

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









<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll('.experience-block').forEach(function(block) {

        let roleDropdown = block.querySelector('.role-category');
        let subDropdown = block.querySelector('.sub-role');

        let selectedSub = subDropdown.dataset.selected;

        if (roleDropdown.value != '') {

            fetch('/get-subroles/' + roleDropdown.value)
                .then(res => res.json())
                .then(data => {

                    subDropdown.innerHTML = '<option>Select Sub Role</option>';

                    data.forEach(sub => {

                        subDropdown.innerHTML +=
                            `<option value="${sub.id}"
                        ${selectedSub==sub.id?'selected':''}>
                        ${sub.name}
                        </option>`;

                    });

                });
        }

    });

});
</script>


@endsection