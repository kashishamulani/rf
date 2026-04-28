@extends('layouts.app')

@section('content')

<div style="padding:20px; display:flex; flex-direction:column; align-items:center;">

    {{-- Back Button --}}
    <div style="width:100%; max-width:700px; margin-bottom:15px;">
        <a href="{{ route('link-assignments.index') }}" 
           style="display:inline-flex; align-items:center; gap:6px; 
           padding:8px 14px; background:#e5e7eb; 
           border-radius:8px; text-decoration:none; color:#111; font-weight:500;">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('link-assignments.store') }}" method="POST"
        style="width:100%; max-width:700px; background:#fff; padding:30px; 
        border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.05);">

        @csrf

        <h2 style="text-align:center; color:#6366f1; margin-bottom:20px;">
            Link Assignment & Form
        </h2>

        {{-- Global Error --}}
        @if(session('error'))
            <div style="background:#ef4444; color:#fff; padding:10px; border-radius:8px; margin-bottom:15px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:15px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li style="font-size:14px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Assignment --}}
        <div style="margin-top:15px;">
            <label style="font-weight:600;">Assignment</label>
            <select name="assignment_id" required 
                style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                
                <option value="">Select Assignment</option>
                
                @foreach($assignments as $a)
                    <option value="{{ $a->id }}" 
                        {{ old('assignment_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->assignment_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Form --}}
        <div style="margin-top:20px;">
            <label style="font-weight:600;">Form</label>
            <select name="form_id" required 
                style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                
                <option value="">Select Form</option>
                
                @foreach($forms as $f)
                    <option value="{{ $f->id }}" 
                        {{ old('form_id') == $f->id ? 'selected' : '' }}>
                        {{ $f->title }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit" 
            style="margin-top:25px; width:100%; padding:12px;
            background:linear-gradient(135deg,#6366f1,#ec4899);
            color:#fff; border:none; border-radius:10px; font-weight:600; cursor:pointer;">
            Link Now
        </button>

    </form>

</div>

@endsection 