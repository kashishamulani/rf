    @extends('layouts.app')

    @section('content')

    <div style="padding:10px; width:100%;">

        {{-- Back Button --}}
        <div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
            <a href="{{ route('activities.index') }}" style="
                    padding:10px 18px;
                    background:#e5e7eb;
                    color:#374151;
                    font-weight:600;
                    border-radius:10px;
                    text-decoration:none;
                    transition:0.3s;
            " onmouseover="this.style.background='#d1d5db'" onmouseout="this.style.background='#e5e7eb'">
                ← Back
            </a>
        </div>

        <div style="display:flex; justify-content:center; width:100%;">
            <form action="{{ route('activities.update', $activity->id) }}" method="POST" style="width:100%; max-width:800px; background:rgba(255,255,255,0.85);
                        padding:30px 40px; border-radius:16px;
                        backdrop-filter:blur(14px);
                        box-shadow:0 10px 30px rgba(0,0,0,0.05);">
                @csrf
                @method('PUT')

                <h2 style="font-size:26px; font-weight:700; margin-bottom:20px;
                        text-align:center; color:#4f46e5;">
                    Edit Activity
                </h2>

                {{-- Errors --}}
                @if($errors->any())
                <div style="padding:12px 16px; background:#ef4444; color:#fff;
                                border-radius:10px; margin-bottom:16px;">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Activity Name --}}
                <div style="margin-bottom:20px;">
                    <label style="font-weight:600; color:#6366f1;">Activity Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $activity->name) }}" required style="width:100%; padding:12px; border-radius:8px;
                                border:1px solid rgba(99,102,241,0.3);">
                </div>

                {{-- Phase --}}
                <div style="margin-bottom:30px;">
                    <label style="font-weight:600; color:#6366f1;">Phase <span style="color:#ef4444;">*</span></label>
                    <select name="phase_id" required style="width:100%; padding:12px; border-radius:8px;
                                border:1px solid rgba(99,102,241,0.3);">
                        <option value="">— Select Phase —</option>
                        @foreach($phases as $phase)
                        <option value="{{ $phase->id }}" @selected($activity->phase_id == $phase->id)>
                            {{ $phase->phase_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div style="margin-bottom:24px;">
                    <label style="font-weight:600; color:#6366f1;">Description</label>

                    <textarea name="description" rows="4" placeholder="Enter activity description" style="width:100%; padding:12px; border-radius:8px;
                     border:1px solid rgba(99,102,241,0.3);
                     background:#fff;">{{ old('description', $activity->description) }}</textarea>
                </div>


                <button type="submit" style="padding:14px 24px;
                        background:linear-gradient(135deg,#6366f1,#ec4899);
                        color:#fff;
                        font-weight:600;
                        border-radius:12px;
                        width:100%;
                        max-width:260px;
                        display:block;
                        margin:auto;">
                    Update Activity
                </button>
            </form>
        </div>
    </div>

    @endsection