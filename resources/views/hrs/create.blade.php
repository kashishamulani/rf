@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">

    {{-- Back Button --}}
    <div style="width:100%; max-width:700px; display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('hrs.index') }}" style="
            padding:10px 18px;
            background:#e5e7eb;
            color:#374151;
            font-weight:600;
            border-radius:10px;
            text-decoration:none;
        ">
            ← Back
        </a>
    </div>

    <form action="{{ route('hrs.store') }}" method="POST"
        style="width:100%; max-width:700px; background:rgba(255,255,255,0.9); padding:30px 40px; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        @csrf

        <h2 style="font-size:26px; font-weight:700; margin-bottom:20px; text-align:center; color:#4f46e5;">
            Add HR Details
        </h2>

        {{-- Errors --}}
        @if($errors->any())
        <div style="padding:12px; background:#ef4444; color:#fff; border-radius:10px; margin-bottom:16px;">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Success --}}
        @if(session('success'))
        <div style="padding:12px; background:#22c55e; color:#fff; border-radius:10px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Name --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Name *</label>
            <input type="text" name="name"
                value="{{ old('name') }}"
                required
                pattern="[A-Za-z\s]+"
                oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')"
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Mobile --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Mobile *</label>
            <input type="tel" name="mobile"
                value="{{ old('mobile') }}"
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
                value="{{ old('email') }}"
                required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- State --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">State *</label>
            <select name="state" id="stateDropdown" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
                <option value="">Loading states...</option>
            </select>
        </div>

        <!-- {{-- District --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">District</label>
            <select name="district" id="districtDropdown"
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
                <option value="">Select District</option>
            </select>
        </div> -->

        {{-- Submit --}}
        <button type="submit"
            style="padding:14px; background:linear-gradient(135deg,#6366f1,#ec4899); color:#fff; font-weight:600; border-radius:12px; width:100%;">
            Add HR
        </button>

    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const stateDropdown = document.getElementById('stateDropdown');
    const districtDropdown = document.getElementById('districtDropdown');

    const oldState = "{{ old('state') }}";
    const oldDistrict = "{{ old('district') }}";

    // Load States
    fetch("{{ url('/states') }}")
        .then(res => res.json())
        .then(states => {

            stateDropdown.innerHTML = '<option value="">Select State</option>';

            states.forEach(state => {
                const option = document.createElement('option');
                option.value = state.iso2;
                option.textContent = state.name;

                if (oldState === state.iso2) {
                    option.selected = true;
                    loadDistricts(state.iso2);
                }

                stateDropdown.appendChild(option);
            });
        });

    // Load Districts
    function loadDistricts(stateCode) {

        districtDropdown.innerHTML = '<option>Loading...</option>';

        fetch(`/districts/${stateCode}`)
            .then(res => res.json())
            .then(districts => {

                districtDropdown.innerHTML = '<option value="">Select District</option>';

                districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.name;
                    option.textContent = district.name;

                    if (oldDistrict === district.name) {
                        option.selected = true;
                    }

                    districtDropdown.appendChild(option);
                });
            });
    }

    // State change
    stateDropdown.addEventListener('change', function() {
        if (this.value) {
            loadDistricts(this.value);
        } else {
            districtDropdown.innerHTML = '<option value="">Select District</option>';
        }
    });

});
</script>

@endsection