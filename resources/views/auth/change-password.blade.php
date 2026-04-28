@extends('layouts.app')

@section('title','Change Password')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">

    {{-- Back Button --}}
    <div style="width:100%; max-width:520px; display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('dashboard') }}" style="
            padding:10px 18px;
            background:#e5e7eb;
            color:#374151;
            font-weight:600;
            border-radius:10px;
            text-decoration:none;
            transition:0.3s;
        "
        onmouseover="this.style.background='#d1d5db'"
        onmouseout="this.style.background='#e5e7eb'">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('change.password') }}"
        style="width:100%; max-width:520px; background:rgba(255,255,255,0.85); padding:30px 40px; border-radius:16px; backdrop-filter:blur(14px); box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        @csrf

        <h2 style="font-size:24px; font-weight:700; margin-bottom:20px; text-align:center; color:#4f46e5;">
            Change Password
        </h2>

        {{-- Success Message --}}
        @if(session('success'))
        <div style="padding:12px 16px; background:#10b981; color:#fff; border-radius:10px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
        <div style="padding:12px 16px; background:#ef4444; color:#fff; border-radius:10px; margin-bottom:16px;">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Old Password --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Old Password <span style="color:#ef4444;">*</span></label>
            <input type="password" name="old_password" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3); background:#fff;">
        </div>

        {{-- New Password --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">New Password <span style="color:#ef4444;">*</span></label>
            <input type="password" name="password" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Confirm Password --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Confirm Password <span style="color:#ef4444;">*</span></label>
            <input type="password" name="password_confirmation" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Submit --}}
        <button type="submit"
            style="padding:14px 24px; background:linear-gradient(135deg,#6366f1,#ec4899); color:#fff; font-weight:600; border-radius:12px; transition:0.3s; width:100%; max-width:250px; display:block; margin:auto;">
            Update Password
        </button>

    </form>

</div>

@endsection