@extends('layouts.app')

@section('content')

<div
    style="padding:20px; max-width:700px; margin:auto; background:rgba(255,255,255,0.9); border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.05); backdrop-filter:blur(12px);">

    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:22px; font-weight:600; color:#6366f1;">
            <i class="fa-solid fa-user-tag"></i> Create Subrole
        </h2>

        <a href="{{ route('roles.index') }}"
            style="padding:8px 14px; background:#e5e7eb; color:#374151; border-radius:10px; font-weight:600; text-decoration:none; transition:0.3s;"
            onmouseover="this.style.background='#d1d5db'" onmouseout="this.style.background='#e5e7eb'">
            ← Back
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div style="padding:12px; background:#22c55e; color:white; border-radius:10px; margin-bottom:16px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
    <div style="padding:12px; background:#ef4444; color:white; border-radius:10px; margin-bottom:16px;">
        {{ session('error') }}
    </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div style="padding:12px; background:#f87171; color:white; border-radius:10px; margin-bottom:16px;">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('subroles.store') }}">
        @csrf

        {{-- Select Role --}}
        <div style="margin-bottom:16px;">
            <label style="font-weight:600; color:#6366f1;">Select Role <span style="color:#ef4444;">*</span></label>
            <select name="role_id" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3); background:#fff;">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    {{ (old('role_id', $selectedRole ?? '') == $role->id) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Subrole Name --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Subrole Name <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Subrole Name" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3); background:#fff;">
        </div>

        {{-- Submit Button --}}
        <button type="submit"
            style="padding:14px 24px; background:linear-gradient(135deg,#6366f1,#ec4899); color:#fff; font-weight:600; border-radius:12px; width:100%; max-width:250px; display:block; margin:auto; transition:0.3s;">
            Save Subrole
        </button>
    </form>
</div>

@endsection