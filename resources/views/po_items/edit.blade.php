@extends('layouts.app')

@section('content')

<div style="max-width:700px; margin:auto; padding:20px;">

    <h2 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:16px;">
        Edit PO Item
    </h2>

    {{-- ✅ SUCCESS MESSAGE --}}
    @if(session('success'))
        <div style="
            background:#dcfce7;
            color:#166534;
            padding:12px 14px;
            border-radius:8px;
            margin-bottom:14px;
            border:1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    {{-- ❌ VALIDATION ERRORS --}}
    @if($errors->any())
        <div style="
            background:#fee2e2;
            color:#991b1b;
            padding:12px 14px;
            border-radius:8px;
            margin-bottom:14px;
            border:1px solid #fecaca;">
            <strong>Please fix the following:</strong>
            <ul style="margin-top:6px; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('po.po_items.update', [$po->id, $item->id]) }}" method="POST"
          style="background:white; padding:20px; border-radius:12px; box-shadow:0 6px 16px rgba(0,0,0,0.06);">
        @csrf
        @method('PUT')

        <div style="margin-bottom:14px;">
            <label>Item</label>
            <input type="text" name="item" value="{{ old('item', $item->item) }}" required
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #e5e7eb;">
        </div>

        <div style="margin-bottom:14px;">
            <label>Rate</label>
            <input type="number" step="0.01" name="value" value="{{ old('value', $item->value) }}" required
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #e5e7eb;">
        </div>

        <div style="margin-bottom:14px;">
            <label>Quantity</label>
            <input type="number" name="quantity" value="{{ old('quantity', $item->quantity) }}" required
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #e5e7eb;">
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit"
                    style="padding:10px 20px; background:#22c55e; color:white; border-radius:8px; border:none;">
                Update
            </button>

            <a href="{{ route('po.show', $item->po_id) }}"
               style="padding:10px 20px; background:#e5e7eb; border-radius:8px; text-decoration:none;">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection