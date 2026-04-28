@extends('layouts.app')

@section('content')

<div style="padding:10px; width:100%; display:flex; flex-direction:column; align-items:center;">


@if(session('success'))
    <div style="
        background:#16a34a;
        color:#fff;
        padding:12px 18px;
        border-radius:8px;
        margin:15px;
        font-weight:600;
    ">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="
        background:#ef4444;
        color:#fff;
        padding:12px 18px;
        border-radius:8px;
        margin:15px;
        font-weight:600;
    ">
        {{ session('error') }}
    </div>
@endif

    {{-- BACK BUTTON --}}
    <div style="width:100%; max-width:900px; display:flex; justify-content:flex-end; margin-bottom:16px;">
        <a href="{{ route('po.index') }}" style="
            padding:10px 18px;
            background:#e5e7eb;
            color:#374151;
            font-weight:600;
            border-radius:10px;
            text-decoration:none;">
            ← Back
        </a>
    </div>

    {{-- ADD ITEMS FORM --}}
    <form action="{{ route('po.po_items.store', $po->id) }}" method="POST"
          style="width:100%; max-width:900px; background:#fff; padding:20px 24px;
                 border-radius:14px; box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        @csrf

        <h2 style="font-size:24px; font-weight:700; margin-bottom:16px; text-align:center; color:#4f46e5;">
            Add Items to PO: {{ $po->po_no }}
        </h2>

        {{-- ERRORS --}}
        @if($errors->any())
        <div style="padding:12px 16px; background:#ef4444; color:#fff; border-radius:10px; margin-bottom:16px;">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ITEMS TABLE --}}
        <div style="overflow-x:auto;">
            <table id="itemsTable" style="width:100%; border-collapse:collapse; margin-bottom:16px;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th style="padding:10px; border:1px solid #e5e7eb;">Item</th>
                        <th style="padding:10px; border:1px solid #e5e7eb;">Rate</th>
                        <th style="padding:10px; border:1px solid #e5e7eb;">Qty</th>
                        <th style="padding:10px; border:1px solid #e5e7eb;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border:1px solid #e5e7eb;">
                            <input type="text" name="items[0][item]" required style="width:100%; padding:10px; border:none;">
                        </td>
                        <td style="border:1px solid #e5e7eb;">
                            <input type="number" step="0.01" name="items[0][value]" required style="width:100%; padding:10px; border:none;">
                        </td>
                        <td style="border:1px solid #e5e7eb;">
                            <input type="number" name="items[0][quantity]" required style="width:100%; padding:10px; border:none;">
                        </td>
                        <td style="border:1px solid #e5e7eb; text-align:center;">
                            <button type="button" onclick="removeRow(this)" style="color:#ef4444; border:none; background:none; font-weight:600;">
                                Remove
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display:flex; justify-content:space-between;">
            <button type="button" onclick="addRow()"
                style="padding:10px 18px; background:#22c55e; color:#fff; border-radius:10px; border:none; font-weight:600;">
                + Add Row
            </button>

            <button type="submit"
                style="padding:12px 22px; background:linear-gradient(135deg,#6366f1,#ec4899);
                       color:#fff; border-radius:12px; border:none; font-weight:600;">
                Save Items
            </button>
        </div>
    </form>

    {{-- EXISTING ITEMS --}}
    @if($existingItems->count())
    <div style="width:100%; max-width:900px; margin-top:20px;
                background:#fff; padding:18px 22px;
                border-radius:14px;
                box-shadow:0 10px 30px rgba(0,0,0,0.05);">

        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:#4f46e5;">
            Existing Items
        </h3>

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:8px; border:1px solid #e5e7eb;">#</th>
                    <th style="padding:8px; border:1px solid #e5e7eb;">Item</th>
                    <th style="padding:8px; border:1px solid #e5e7eb;">Rate</th>
                    <th style="padding:8px; border:1px solid #e5e7eb;">Qty</th>
                    <th style="padding:8px; border:1px solid #e5e7eb;">Used</th>
                    <th style="padding:8px; border:1px solid #e5e7eb;">Remaining</th>
                </tr>
            </thead>
            <tbody>
                @foreach($existingItems as $index => $item)
                @php
                    $remaining = $item->quantity - ($item->used_quantity ?? 0);
                @endphp
                <tr>
                    <td style="padding:8px; border:1px solid #e5e7eb; text-align:center;">
                        {{ $index + 1 }}
                    </td>
                    <td style="padding:8px; border:1px solid #e5e7eb;">
                        {{ $item->item }}
                    </td>
                    <td style="padding:8px; border:1px solid #e5e7eb; text-align:right;">
                        ₹{{ number_format($item->value,2) }}
                    </td>
                    <td style="padding:8px; border:1px solid #e5e7eb; text-align:right;">
                        {{ $item->quantity }}
                    </td>
                    <td style="padding:8px; border:1px solid #e5e7eb; text-align:right; color:#2563eb; font-weight:600;">
                        {{ $item->used_quantity ?? 0 }}
                    </td>
                    <td style="padding:8px; border:1px solid #e5e7eb; text-align:right; color:#16a34a; font-weight:700;">
                        {{ $remaining }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

<script>
let rowIndex = 1;

function addRow() {
    const table = document.querySelector('#itemsTable tbody');
    const row = document.createElement('tr');

    row.innerHTML = `
        <td style="border:1px solid #e5e7eb;">
            <input type="text" name="items[${rowIndex}][item]" required style="width:100%; padding:10px; border:none;">
        </td>
        <td style="border:1px solid #e5e7eb;">
            <input type="number" step="0.01" name="items[${rowIndex}][value]" required style="width:100%; padding:10px; border:none;">
        </td>
        <td style="border:1px solid #e5e7eb;">
            <input type="number" name="items[${rowIndex}][quantity]" required style="width:100%; padding:10px; border:none;">
        </td>
        <td style="border:1px solid #e5e7eb; text-align:center;">
            <button type="button" onclick="removeRow(this)" style="color:#ef4444; border:none; background:none; font-weight:600;">
                Remove
            </button>
        </td>
    `;
    table.appendChild(row);
    rowIndex++;
}

function removeRow(btn) {
    btn.closest('tr').remove();
}
</script>

@endsection