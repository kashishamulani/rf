@extends('layouts.app')

@section('content')

<style>
.form-card {
    max-width: 1200px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
}

.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.field {
    display: flex;
    flex-direction: column;
}

.field-1 {
    display: flex;
    flex-direction: column;
    padding: 10px;
    border-radius: 8px;
}

.field label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}

.field input,
.field select {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.btn-save {
    margin-top: 20px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
}

.error-message {
    background: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.success-message {
    background: #ecfdf5;
    border: 1px solid #10b981;
    color: #065f46;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}
</style>

<div class="form-card">

    <h2 style="margin-bottom:20px;">
        Student Assignment Data — {{ $student->name }}
    </h2>

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

    <form method="POST"
        action="@if($data) {{ route('assignment.student.update', $data->id) }} @else {{ route('assignment.student.store') }} @endif"
        enctype="multipart/form-data">

        @csrf
        @if($data)
        @method('POST')
        @endif

        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
        <input type="hidden" name="mobilization_id" value="{{ $student->id }}">

         <div class="field-1">
                <label>Progress</label>
                <select name="progress_id">
                    <option value="">Select Progress</option>

                    @foreach($progressList as $p)
                    <option value="{{ $p->id }}" @if(isset($data) && $data->progress_id == $p->id) selected @endif>
                        {{ $p->name }}
                    </option>
                    @endforeach
                </select>
            </div>

        <div class="grid">

           

            <div class="field">
                <label>Samarth Done</label>
                <select name="samarth_done">
                    <option value="0" @if(!$data || !$data->samarth_done) selected @endif>No</option>
                    <option value="1" @if($data && $data->samarth_done) selected @endif>Yes</option>
                </select>
            </div>

            <div class="field">
                <label>Samarth ID</label>
                <input type="text" name="samarth_id" value="{{ $data->samarth_id ?? '' }}">
            </div>

            <div class="field">
                <label>Samarth Certificate</label>
                <input type="file" name="samarth_certificate">
            </div>

            <div class="field">
                <label>UAN Done</label>
                <select name="uan_done">
                    <option value="0" @if(!$data || !$data->uan_done) selected @endif>No</option>
                    <option value="1" @if($data && $data->uan_done) selected @endif>Yes</option>
                </select>
            </div>

            <div class="field">
                <label>UAN Number</label>
                <input type="text" name="uan_number" value="{{ $data->uan_number ?? '' }}">
            </div>

            <div class="field">
                <label>UAN Certificate</label>
                <input type="file" name="uan_certificate">
            </div>

           

            <div class="field">
                <label>Offer Letter Given</label>
                <select name="offer_letter_done">
                    <option value="0" @if(!$data || !$data->offer_letter_done) selected @endif>No</option>
                    <option value="1" @if($data && $data->offer_letter_done) selected @endif>Yes</option>
                </select>
            </div>
            
            <div class="field">
                <label>Offer Letter Date</label>
                <input type="date" name="offer_letter_date" value="{{ $data->offer_letter_date ?? '' }}">
            </div>

            <div class="field">
                <label>Upload Offer Letter</label>
                <input type="file" name="offer_letter_file">

                @if(!empty($data->offer_letter_file))
                <a href="{{ asset('uploads/offer_letters/'.$data->offer_letter_file) }}" target="_blank">
                    View File
                </a>
                @endif
            </div>

            <div class="field">
                <label>User ID</label>
                <input type="text" name="registration_id" value="{{ $data->registration_id ?? '' }}">
            </div>

            <div class="field">
                <label>Password</label>
                <input type="text" name="registration_password" value="{{ $data->registration_password ?? '' }}">
            </div>

            <div class="field">
                <label>Registration Number</label>
                <input type="text" name="registration_number" value="{{ $data->registration_number ?? '' }}">
            </div>

            <div class="field">
                <label>EC Number</label>
                <input type="text" name="ec_number" value="{{ $data->ec_number ?? '' }}">
            </div>
             <div class="field">
                <label>EC Date</label>
                <input type="date" name="ec_date" value="{{ $data->ec_date ?? '' }}">
            </div>


            <div class="field">
                <label>Date of Placement</label>
                <input type="date" name="date_of_placement" class="form-control"
                    value="{{ $data->date_of_placement ?? '' }}">
            </div>

            <div class="field">
                <label>Placement Company</label>
                <input type="text" name="placement_company" class="form-control"
                    value="{{ $data->placement_company ?? '' }}">
            </div>

            <div class="field">
                <label>Placement Offering</label>
                <input type="text" name="placement_offering" class="form-control"
                    value="{{ $data->placement_offering ?? '' }}">
            </div>


           
             <div class="field">
                <label>Documents Submitted</label>
                <select name="documents_done">
                    <option value="0" @if(!$data || !$data->documents_done) selected @endif>No</option>
                    <option value="1" @if($data && $data->documents_done) selected @endif>Yes</option>
                </select>
            </div>

        </div>

        <button type="submit" class="btn-save">
            @if($data) Update Data @else Save Data @endif
        </button>

        <a href="{{ route('assignment.students.view', [$assignment->id, $student->id]) }}" style="margin-top:20px; padding:12px 20px; background:#6b7280; color:white; 
           border-radius:10px; text-decoration:none; display:inline-block;">
            Back
        </a>

    </form>

</div>

@endsection