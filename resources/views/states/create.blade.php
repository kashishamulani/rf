@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">

    <div style="width:100%; max-width:700px; display:flex; justify-content:flex-end; margin-bottom:16px;">

        <a href="{{ route('states.index') }}"
            style="padding:10px 18px;
            background:#e5e7eb;
            color:#374151;
            font-weight:600;
            border-radius:10px;
            text-decoration:none;">

            ← Back
        </a>

    </div>

    <form action="{{ route('states.store') }}"
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

            Add State
        </h2>

        @if($errors->any())
        <div style="padding:12px;
            background:#ef4444;
            color:#fff;
            border-radius:10px;
            margin-bottom:16px;">

            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
        @endif

        <div style="margin-bottom:20px;">

            <label style="font-weight:600; color:#6366f1;">
                State Name *
            </label>

            <input type="text"
                name="name"
                value="{{ old('name') }}"
                required
                style="width:100%;
                padding:12px;
                border-radius:8px;
                border:1px solid rgba(99,102,241,0.3);">

        </div>

        <div style="margin-bottom:20px;">

            <label style="font-weight:600; color:#6366f1;">
                Latitude
            </label>

            <input type="text"
                name="lat"
                value="{{ old('lat') }}"
                style="width:100%;
                padding:12px;
                border-radius:8px;
                border:1px solid rgba(99,102,241,0.3);">

        </div>

        <div style="margin-bottom:20px;">

            <label style="font-weight:600; color:#6366f1;">
                Longitude
            </label>

            <input type="text"
                name="long"
                value="{{ old('long') }}"
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

            Add State
        </button>

    </form>

</div>

@endsection