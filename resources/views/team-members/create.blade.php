@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%;">

    {{-- Back Button --}}
    <div style="max-width:800px; margin:0 auto 14px; display:flex; justify-content:flex-end;">
        <a href="{{ route('team-members.index') }}" style="padding:10px 18px;
                  background:#f1f5f9;
                  color:#4f46e5;
                  border-radius:10px;
                  font-weight:600;
                  text-decoration:none;">
            ← Back
        </a>
    </div>

    <div style="display:flex; justify-content:center; width:100%;">
        <form action="{{ route('team-members.store') }}" method="POST" style="width:100%; max-width:800px;
                   background:rgba(255,255,255,0.85);
                   padding:30px 40px;
                   border-radius:16px;
                   backdrop-filter:blur(14px);
                   box-shadow:0 10px 30px rgba(0,0,0,0.05);">
            @csrf

            <h2 style="font-size:26px; font-weight:700; margin-bottom:20px;
                       text-align:center; color:#4f46e5;">
                Create Team Member
            </h2>

            {{-- Errors --}}
            @if($errors->any())
            <div style="padding:12px 16px;
                            background:#fee2e2;
                            color:#991b1b;
                            border-radius:10px;
                            margin-bottom:16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Name --}}
            {{-- Name --}}
            <div style="margin-bottom:20px;">
                <label style="font-weight:600; color:#6366f1;">
                    Name <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter full name" required
                    minlength="2" maxlength="255" pattern="[A-Za-z\s\.]+"
                    title="Name can contain only letters and spaces"
                    oninput="this.value=this.value.replace(/[^A-Za-z\s\.]/g,'')" style="width:100%; padding:12px; border-radius:8px;
       border:1px solid rgba(99,102,241,0.3);">
            </div>

            {{-- Designation --}}
            <div style="margin-bottom:20px;">
                <label style="font-weight:600; color:#6366f1;">
                    Designation <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="designation" value="{{ old('designation') }}" placeholder="Enter designation"
                    required minlength="2" maxlength="255" style="width:100%; padding:12px; border-radius:8px;
                  border:1px solid rgba(99,102,241,0.3);">
            </div>

            {{-- Email --}}
            <div style="margin-bottom:20px;">
                <label style="font-weight:600; color:#6366f1;">
                    Email <span style="color:#ef4444;">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required
                    maxlength="255" style="width:100%; padding:12px; border-radius:8px;
                  border:1px solid rgba(99,102,241,0.3);">
            </div>

            {{-- Mobile --}}
            <div style="margin-bottom:20px;">
                <label style="font-weight:600; color:#6366f1;">
                    Mobile <span style="color:#ef4444;">*</span>
                </label>
                <input type="tel" name="mobile" value="{{ old('mobile') }}" placeholder="Enter 10-digit mobile number"
                    required maxlength="10" pattern="[0-9]{10}" inputmode="numeric"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)" style="width:100%; padding:12px; border-radius:8px;
                  border:1px solid rgba(99,102,241,0.3);">
                <small style="color:#6b7280;">Enter valid 10-digit mobile number</small>
            </div>

            {{-- Status --}}
            <div style="margin-bottom:30px;">
                <label style="font-weight:600; color:#6366f1;">
                    Status <span style="color:#ef4444;">*</span>
                </label>
                <select name="status" required style="width:100%; padding:12px; border-radius:8px;
                   border:1px solid rgba(99,102,241,0.3);">
                    <option value="1" {{ old('status',1)==1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status')==='0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            {{-- Submit --}}
            <button type="submit" style="padding:14px 24px;
                       background:linear-gradient(135deg,#6366f1,#ec4899);
                       color:#fff;
                       font-weight:600;
                       border-radius:12px;
                       width:100%;
                       max-width:280px;
                       display:block;
                       margin:auto;">
                Create Team Member
            </button>
        </form>
    </div>
</div>

@endsection