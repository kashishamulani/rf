@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">

    {{-- Back Button --}}
    <div style="width:100%; max-width:700px; display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('po.index') }}" style="
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

    <form action="{{ route('po.update', $po->id) }}" method="POST"
        style="width:100%; max-width:700px; background:rgba(255,255,255,0.85); padding:30px 40px; border-radius:16px; backdrop-filter:blur(14px); box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        @csrf
        @method('PUT')

        <h2 style="font-size:26px; font-weight:700; margin-bottom:20px; text-align:center; color:#4f46e5;">
            Edit PO / WO
        </h2>

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

        {{-- PO / WO Number --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">PO / WO Number</label>
            <input type="text" name="po_no" value="{{ old('po_no', $po->po_no) }}" required
                placeholder="Enter PO/WO Number..."
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3); background:#fff;">
        </div>

        {{-- Date --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Date</label>
            <input type="date" name="po_date" value="{{ old('po_date', $po->po_date) }}" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Period From --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Period From</label>
            <input type="date" name="period_from" value="{{ old('period_from', $po->period_from) }}" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Period To --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">Period To</label>
            <input type="date" name="period_to" value="{{ old('period_to', $po->period_to) }}" required
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- GST --}}
        <div style="margin-bottom:20px;">
            <label style="font-weight:600; color:#6366f1;">GST (%)</label>
            <input type="number" step="0.01" name="gst" value="{{ old('gst', $po->gst) }}" placeholder="Enter GST..."
                style="width:100%; padding:12px; border-radius:8px; border:1px solid rgba(99,102,241,0.3);">
        </div>

        {{-- Submit Button --}}
        <button type="submit"
            style="padding:14px 24px; background:linear-gradient(135deg,#6366f1,#ec4899); color:#fff; font-weight:600; border-radius:12px; transition:0.3s; width:100%; max-width:250px; display:block; margin:auto;">
            Update PO
        </button>

    </form>
</div>

@endsection
