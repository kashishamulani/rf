@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">

    <div style="width:100%; max-width:700px; display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('districts.index') }}"
            style="padding:10px 18px;
            background:#e5e7eb;
            color:#374151;
            font-weight:600;
            border-radius:10px;
            text-decoration:none;">

            ← Back
        </a>
    </div>

    <form action="{{ route('districts.store') }}"
        method="POST"
        style="width:100%; max-width:700px;
        background:rgba(255,255,255,0.9);
        padding:30px 40px;
        border-radius:16px;
        box-shadow:0 10px 30px rgba(0,0,0,0.05);">

        @csrf

        <h2 style="font-size:26px;
            font-weight:700;
            margin-bottom:20px;
            text-align:center;
            color:#4f46e5;">

            Add District
        </h2>

        <div style="margin-bottom:20px;">

            <label style="font-weight:600; color:#6366f1;">
                Select State *
            </label>

            <select name="state_id"
                required
                style="width:100%;
                padding:12px;
                border-radius:8px;
                border:1px solid rgba(99,102,241,0.3);">

                <option value="">Select State</option>

                @foreach($states as $state)

                <option value="{{ $state->id }}">
                    {{ $state->name }}
                </option>

                @endforeach

            </select>

        </div>

        <div style="margin-bottom:20px;">

            <label style="font-weight:600; color:#6366f1;">
                District Name *
            </label>

            <input type="text"
                name="name"
                required
                style="width:100%;
                padding:12px;
                border-radius:8px;
                border:1px solid rgba(99,102,241,0.3);">

        </div>

        <button type="submit"
            style="padding:14px;
            background:linear-gradient(135deg,#6366f1,#ec4899);
            color:#fff;
            font-weight:600;
            border-radius:12px;
            width:100%;">

            Add District
        </button>

    </form>

</div>

@endsection