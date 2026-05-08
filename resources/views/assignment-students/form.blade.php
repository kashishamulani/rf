@extends('layouts.app')

@section('content')

<style>
/* CARD */
.form-card {
    max-width: 1100px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
}

/* TITLE */
.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 16px;
}

/* INPUT */
.f-input {
    width: 100%;
    height: 34px;
    padding: 6px 10px;
    font-size: 13px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: #fff;
    color: #111827;
}

/* FIX SELECT */
select,
option {
    color: #111827 !important;
    background: #fff !important;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    /* 👈 fixed 3 per row */
    gap: 12px;
}

/* FIELD */
.field {
    display: flex;
    flex-direction: column;
}

.field label {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 4px;
    color: #374151;
}

/* BUTTONS */
.btn-primary {
    padding: 8px 18px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
}

.btn-secondary {
    padding: 8px 18px;
    background: #6b7280;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
}

/* ALERTS */
.error-message {
    background: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 12px;
    font-size: 13px;
}

.success-message {
    background: #ecfdf5;
    border: 1px solid #10b981;
    color: #065f46;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 12px;
    font-size: 13px;
}

/* FILE LINK */
.file-link {
    font-size: 12px;
    margin-top: 4px;
    color: #2563eb;
    text-decoration: none;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .form-card {
        padding: 14px;
    }

    .f-input {
        height: 30px;
        font-size: 12px;
    }
}

@media (max-width: 992px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="form-card">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
    
    <div class="form-title">
        Student Assignment Data — {{ $student->name }}
    </div>

    <a href="{{ route('assignment.students.view', [$assignment->id, $student->id]) }}" class="btn-secondary">
        ← Back
    </a>

</div>

    @if($errors->any())
    <div class="error-message">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    @if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
        action="{{ $data ? route('assignment.student.update', $data->id) : route('assignment.student.store') }}">

        @csrf
        @if($data) @method('PUT') @endif

        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
        <input type="hidden" name="mobilization_id" value="{{ $student->id }}">

        {{-- Progress --}}
        {{-- TOP ROW --}}
        <div class="grid" style="margin-bottom:12px; grid-template-columns: repeat(2, 1fr);">

            <div class="field">
                <label>Progress</label>
                <select name="progress_id" class="f-input">
                    <option value="">Select Progress</option>
                    @foreach($progressList as $p)
                    <option value="{{ $p->id }}"
                        {{ old('progress_id', $data->progress_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Remark</label>
                <textarea name="remark" class="f-input" maxlength="50" style="height:34px;"
                    oninput="updateCharCount(this)">
</textarea>

                <small id="remarkCount" style="font-size:11px; color:#6b7280;">
                    0 / 50
                </small>
            </div>

        </div>

        <div class="grid">
            <div class="field">
                <label>Samarth Status</label>
                <select name="samarth_done" class="f-input">
                    <option value="">Select</option>
                    <option value="pending"
                        {{ old('samarth_done', $data->samarth_done ?? '') == 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="inprocess"
                        {{ old('samarth_done', $data->samarth_done ?? '') == 'inprocess' ? 'selected' : '' }}>In Process
                    </option>
                    <option value="done"
                        {{ old('samarth_done', $data->samarth_done ?? '') == 'done' ? 'selected' : '' }}>
                        Done</option>
                    <option value="not_done"
                        {{ old('samarth_done', $data->samarth_done ?? '') == 'not_done' ? 'selected' : '' }}>Not Done
                    </option>
                    <option value="failed"
                        {{ old('samarth_done', $data->samarth_done ?? '') == 'failed' ? 'selected' : '' }}>Failed
                    </option>
                </select>
            </div>

            <div class="field">
                <label>Samarth Certificate</label>
                <input type="file" name="samarth_certificate" class="f-input">
            </div>

            <div class="field">
                <label>UAN Done</label>
                <select name="uan_done" class="f-input">
                    <option value="0" {{ old('uan_done', $data->uan_done ?? 0) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('uan_done', $data->uan_done ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <div class="field">
                <label>UAN Number</label>
                <input type="text" name="uan_number" class="f-input"
                    value="{{ old('uan_number', $data->uan_number ?? '') }}">
            </div>

            <div class="field">
                <label>UAN Certificate</label>
                <input type="file" name="uan_certificate" class="f-input">
            </div>

            <div class="field">
                <label>Offer Letter Given</label>
                <select name="offer_letter_done" class="f-input">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="field">
                <label>Offer Letter Date</label>
                <input type="date" name="offer_letter_date" class="f-input"
                    value="{{ old('offer_letter_date', $data->offer_letter_date ?? '') }}">
            </div>

            <div class="field">
                <label>Upload Offer Letter</label>
                <input type="file" name="offer_letter_file" class="f-input">

                @if(!empty($data->offer_letter_file))
                <a href="{{ asset('uploads/offer_letters/'.$data->offer_letter_file) }}" target="_blank"
                    class="file-link">
                    View File
                </a>
                @endif
            </div>

            <div class="field">
                <label>User ID</label>
                <input type="text" name="registration_id" class="f-input" value="{{ $data->registration_id ?? '' }}">
            </div>

            <div class="field">
                <label>Password</label>
                <input type="text" name="registration_password" class="f-input"
                    value="{{ $data->registration_password ?? '' }}">
            </div>

            <div class="field">
                <label>Registration Number</label>
                <input type="text" name="registration_number" class="f-input"
                    value="{{ old('registration_id', $data->registration_id ?? '') }}">
            </div>

            <div class="field">
                <label>EC Number</label>
                <input type="text" name="ec_number" class="f-input"
                    value="{{  old('ec_number', $data->ec_number ?? '') }}">
            </div>

            <div class="field">
                <label>EC Date</label>
                <input type="date" name="ec_date" class="f-input" value="{{  old('ec_date', $data->ec_date ?? '') }}">
            </div>

            <div class="field">
                <label>Date of Placement</label>
                <input type="date" name="date_of_placement" class="f-input"
                    value="{{ old('date_of_placement', $data->date_of_placement ?? '') }}">
            </div>

            <div class="field">
                <label>Placement Company</label>
                <input type="text" name="placement_company" class="f-input"
                    value="{{ old('placement_company', $data->placement_company ?? '') }}">
            </div>

            <div class="field">
                <label>Placement Offering</label>
                <input type="text" name="placement_offering" class="f-input"
                    value="{{ old('placement_offering', $data->placement_offering ?? '') }}">
            </div>

            <div class="field">
                <label>Documents Submitted</label>
                <select name="documents_done" class="f-input">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

        </div>

</div>

{{-- ACTIONS --}}
<div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap;">
    <button type="submit" class="btn-primary">
        {{ $data ? 'Update Data' : 'Save Data' }}
    </button>

 
</div>

</form>

</div>



<script>
function updateCharCount(el) {
    let count = el.value.length;
    document.getElementById('remarkCount').innerText = count + " / 50";
}

// Initialize on load (for edit case)
document.addEventListener("DOMContentLoaded", function() {
    let remark = document.querySelector('textarea[name="remark"]');
    if (remark) {
        updateCharCount(remark);
    }
});
</script>

@endsection