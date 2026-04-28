@extends('layouts.app')

@section('content')

<div style="padding:10px; display:flex; justify-content:center; flex-direction:column; align-items:center;">

    {{-- BACK BUTTON --}}
    <div style="width:100%; max-width:700px; margin-bottom:10px;">
        <a href="{{ route('link-assignments.index') }}" 
           style="display:inline-flex; align-items:center; gap:6px; 
           padding:8px 14px; background:#e5e7eb; 
           border-radius:8px; text-decoration:none; color:#111;">
           
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('link-assignments.update', $link->id) }}" method="POST"
        style="width:100%; max-width:700px; background:#fff; padding:30px; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        
        @csrf
        @method('PUT')

        @error('assignment_id')
    <div style="color:#ef4444; margin-top:5px;">
        {{ $message }}
    </div>
@enderror

        <h2 style="text-align:center; color:#6366f1;">Edit Linked Assignment</h2>

        {{-- Assignment --}}
        <div style="margin-top:20px;">
            <label>Assignment</label>
            <select name="assignment_id" required style="width:100%; padding:10px; border-radius:8px;">
                @foreach($assignments as $a)
                <option value="{{ $a->id }}" 
                    {{ $link->assignment_id == $a->id ? 'selected' : '' }}>
                    {{ $a->assignment_name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Form --}}
        <div style="margin-top:20px;">
            <label>Form</label>
            <select name="form_id" required style="width:100%; padding:10px; border-radius:8px;">
                @foreach($forms as $f)
                <option value="{{ $f->id }}" 
                    {{ $link->form_id == $f->id ? 'selected' : '' }}>
                    {{ $f->title }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            style="margin-top:20px; width:100%; padding:12px;
            background:linear-gradient(135deg,#6366f1,#ec4899);
            color:#fff; border-radius:10px;">
            Update Link
        </button>

    </form>

</div>

@endsection