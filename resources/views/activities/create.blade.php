@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%;">

    {{-- Back Button --}}
    <div style="display:flex; justify-content:center; width:100%;">
        <div style="width:100%; max-width:800px;">

            {{-- Back Button --}}
            <div style="margin-bottom:14px;">
                <a href="{{ route('activities.index') }}" style="padding:8px 16px;
                      background:#e5e7eb;
                      color:#374151;
                      font-weight:600;
                      border-radius:8px;
                      text-decoration:none;
                      display:inline-block;">
                    ← Back
                </a>
            </div>

            <form action="{{ route('activities.store') }}" method="POST" style="width:100%; background:rgba(255,255,255,0.85);
                     padding:30px 40px;
                     border-radius:16px;
                     backdrop-filter:blur(14px);
                     box-shadow:0 10px 30px rgba(0,0,0,0.05);">

                @csrf

                <h2 style="font-size:26px; font-weight:700; margin-bottom:20px;
                       text-align:center; color:#4f46e5;">
                    Create Activity
                </h2>

                {{-- Global Errors --}}
                @if($errors->any())
                <div style="padding:12px 16px; background:#ef4444; color:#fff;
                            border-radius:10px; margin-bottom:16px;">
                    Please fix the errors below.
                </div>
                @endif

                {{-- Activity Name --}}
                <div style="margin-bottom:20px;">
                    <label style="font-weight:600; color:#6366f1;">Activity Name <span
                            style="color:#ef4444;">*</span></label>

                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter activity name" style="width:100%; padding:12px; border-radius:8px;
                              border:1px solid {{ $errors->has('name') ? '#ef4444' : 'rgba(99,102,241,0.3)' }};
                              background:#fff;">

                    @error('name')
                    <div style="color:#ef4444; font-size:13px; margin-top:6px;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Phase Selection --}}
                <div style="margin-bottom:30px;">
                    <label style="font-weight:600; color:#6366f1;">
                        Phase <span style="color:#ef4444;">*</span>
                    </label>


                    <select name="phase_id" required style="width:100%; padding:12px; border-radius:8px;
                               border:1px solid {{ $errors->has('phase_id') ? '#ef4444' : 'rgba(99,102,241,0.3)' }};
                               background:#fff;">
                        <option value="">-- Select Phase --</option>

                        @foreach($phases as $phase)
                        <option value="{{ $phase->id }}" {{ old('phase_id') == $phase->id ? 'selected' : '' }}>
                            {{ $phase->phase_name }} (Order: {{ $phase->phase_order }})
                        </option>
                        @endforeach
                    </select>

                    @error('phase_id')
                    <div style="color:#ef4444; font-size:13px; margin-top:6px;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Description --}}
                <div style="margin-bottom:24px;">
                    <label style="font-weight:600; color:#6366f1;">Description</label>

                    <textarea name="description" rows="4" placeholder="Enter activity description" style="width:100%; padding:12px; border-radius:8px;
                     border:1px solid {{ $errors->has('description') ? '#ef4444' : 'rgba(99,102,241,0.3)' }};
                     background:#fff;">{{ old('description') }}</textarea>

                    @error('description')
                    <div style="color:#ef4444; font-size:13px; margin-top:6px;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- Submit --}}
                <button type="submit" style="padding:14px 24px;
                       background:linear-gradient(135deg,#6366f1,#ec4899);
                       color:#fff;
                       font-weight:600;
                       border-radius:12px;
                       width:100%;
                       max-width:260px;
                       display:block;
                       margin:auto;">
                    Create Activity
                </button>
            </form>
        </div>
    </div>

    @endsection