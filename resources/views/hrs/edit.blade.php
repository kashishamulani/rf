@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">

    {{-- Back Button --}}
    <div style="width:100%; max-width:700px; display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('hrs.index') }}" style="padding:10px 18px; background:#e5e7eb; color:#374151; font-weight:600; border-radius:10px; text-decoration:none;">
            ← Back
        </a>
    </div>

    <form action="{{ route('hrs.update', $hr->id) }}" method="POST"
        style="width:100%; max-width:700px; background:rgba(255,255,255,0.9);
               padding:30px 40px; border-radius:16px;
               box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        @csrf
        @method('PUT')

        <h2 style="font-size:26px; font-weight:700; margin-bottom:20px; text-align:center; color:#4f46e5;">
            Edit HR Details
        </h2>

        {{-- Errors --}}
        @if($errors->any())
        <div style="padding:12px; background:#ef4444; color:#fff; border-radius:10px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Name --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Name *</label>
            <input type="text" name="name"
                value="{{ old('name', $hr->name) }}"
                required pattern="[A-Za-z\s]+"
                oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')"
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Mobile --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Mobile *</label>
            <input type="tel" name="mobile"
                value="{{ old('mobile', $hr->mobile) }}"
                maxlength="10"
                pattern="[0-9]{10}"
                inputmode="numeric"
                required
                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Email --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Email *</label>
            <input type="email" name="email"
                value="{{ old('email', $hr->email) }}"
                required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- State --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">State *</label>
            <select id="stateDropdown" name="state" required
                style="width:100%; padding:12px; border-radius:8px;
                       border:1px solid rgba(99,102,241,0.3); background:#fff;">
                <option value="">Loading states...</option>
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit"
            style="padding:14px; background:linear-gradient(135deg,#6366f1,#ec4899);
                   color:#fff; font-weight:600; border-radius:12px; width:100%;">
            Update HR
        </button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const dropdown = document.getElementById("stateDropdown");

    // ISO code stored in DB (MP, MH...)
    const selectedState = "{{ old('state', $hr->state) }}";

    fetch("{{ url('/states') }}")
        .then(res => res.json())
        .then(states => {

            dropdown.innerHTML = '<option value="">Select State</option>';

            states.forEach(state => {
                const option = document.createElement("option");

                option.value = state.iso2;   // ✅ store ISO code
                option.textContent = state.name; // ✅ show full name

                if (selectedState === state.iso2) {
                    option.selected = true;
                }

                dropdown.appendChild(option);
            });

        })
        .catch(error => {
            dropdown.innerHTML = '<option value="">Unable to load states</option>';
            console.error("Error loading states:", error);
        });
});
</script>

@endsection