@extends('layouts.app')

@section('content')

<style>
.form-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    padding: 20px 24px;
    width: 100%;
    max-width: 1100px;
}

.form-group {
    margin-bottom: 12px;
}

.form-label {
    font-weight: 600;
    color: #4338ca;
    font-size: 14px;
    margin-bottom: 4px;
    display: block;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid rgba(99, 102, 241, 0.35);
    font-size: 14px;
}

.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    align-items: end;
}

.btn-primary {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    font-weight: 600;
    border-radius: 14px;
    padding: 10px 20px;
    border: none;
}

.btn-back {
    background: #e5e7eb;
    padding: 8px 16px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 10px 14px;
    border-radius: 12px;
    margin-bottom: 14px;
}

.po-table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 14px;
    overflow: hidden;
    margin-top: 16px;
    display: none;
    background: #ffffff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.po-table thead {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
}

.po-table th {
    padding: 10px;
    font-size: 13px;
    font-weight: 600;
}

.po-table td {
    padding: 9px;
    border-bottom: 1px solid #e5e7eb;
}

.table-wrapper {
    margin-top: 18px;
    width: 100%;
    overflow-x: auto;
}

.table-title {
    font-weight: 600;
    color: #4338ca;
    margin-bottom: 6px;
}
</style>

<div style="padding:12px; width:100%; display:flex; flex-direction:column; align-items:center;">

    <div style="width:100%; max-width:1100px; display:flex; justify-content:flex-end; margin-bottom:12px;">
        <a href="{{ route('invoices.index') }}" class="btn-back">← Back</a>
    </div>

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" class="form-card">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="alert-error">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h2 style="text-align:center; margin-bottom:16px;
            background:linear-gradient(135deg,#6366f1,#ec4899);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;">
            Edit Invoice
        </h2>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Invoice Number*</label>
                <input type="text" name="invoice_number" class="form-input"
                    value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Invoice Date*</label>
                <input type="date" name="invoice_date" class="form-input"
                    value="{{ old('invoice_date', $invoice->invoice_date) }}" required>
            </div>
        </div>

        {{-- Batch Info --}}
        <div class="grid-3" style="margin-top:10px;">

            <div class="form-group">
                <label class="form-label">Batch *</label>

                <select name="batch_id" id="batchSelect" class="form-select" required>

                    <option value="">-- Select Batch --</option>

                    @foreach($batches as $batch)

                    <option value="{{ $batch->id }}" data-size="{{ $batch->batch_size }}" data-po="{{ $batch->po_id }}"
                        {{ $invoice->batch_id == $batch->id ? 'selected' : '' }}>

                        {{ $batch->batch_code }}

                    </option>

                    @endforeach

                </select>

            </div>


            <div class="form-group">

                <label class="form-label">Batch Size</label>

                <input type="number" id="batchSize" class="form-input" readonly>

                <input type="hidden" name="batch_quantity" id="batchQuantityHidden"
                    value="{{ $invoice->batch_quantity }}">

            </div>


            <div class="form-group">

                <label class="form-label">Batch Value (Incl. 18% GST)</label>

                <input type="text" id="batchValue" class="form-input" readonly>

            </div>

        </div>

        <div class="form-group">
            <label class="form-label">Payment Detail</label>
            <textarea name="payment_detail" rows="2"
                class="form-textarea">{{ old('payment_detail', $invoice->payment_detail) }}</textarea>
        </div>



        <!-- ASSIGNMENT TABLE -->

        <div class="table-wrapper">

            <div class="table-title">Assignment Billing</div>

            <table id="invoiceAssignmentTable" class="po-table">

                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Assignment</th>
                        <th>Requirement</th>
                        <th>Remaining</th>
                        <th>In Batch</th>
                        <th>Billed Qty</th>
                    </tr>
                </thead>

                <tbody id="invoiceAssignmentBody"></tbody>

            </table>

        </div>


        <!-- PO ITEMS TABLE -->

        <div class="table-wrapper">

            <div class="table-title">PO Item Billing</div>

            <table id="invoicePoTable" class="po-table">

                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Item Name</th>
                        <th>PO Quantity</th>
                        <th>Remaining</th>
                        <th>Rate</th>
                        <th>Billed Qty</th>
                    </tr>
                </thead>

                <tbody id="invoicePoBody"></tbody>

            </table>

        </div>

        <div style="text-align:center; margin-top:20px;">
            <button type="submit" class="btn-primary">Update Invoice</button>
        </div>

    </form>
</div>
<script>
const billedAssignments = @json(
    $invoice->assignmentItems->pluck('quantity', 'assignment_id')
);

const billedPoItems = @json(
    $invoice->poItems->pluck('qty', 'po_item_id')
);
</script>
<script>
const batchSelect = document.getElementById('batchSelect');
const batchSizeInput = document.getElementById('batchSize');
const batchQuantityHidden = document.getElementById('batchQuantityHidden');
const valueField = document.getElementById('batchValue');

const assignmentTable = document.getElementById('invoiceAssignmentTable');
const assignmentBody = document.getElementById('invoiceAssignmentBody');

const poTable = document.getElementById('invoicePoTable');
const poBody = document.getElementById('invoicePoBody');


/* =========================
   CALCULATE BATCH VALUE
========================= */

function updateBatchValue() {

    let subtotal = 0;

    document.querySelectorAll('#invoicePoBody tr').forEach(row => {

        let qtyInput = row.querySelector('input');
        let rate = parseFloat(row.children[4].innerText) || 0;
        let qty = parseFloat(qtyInput.value) || 0;

        subtotal += qty * rate;

    });

    let gst = subtotal * 0.18;
    let total = subtotal + gst;

    valueField.value = total.toFixed(2);

}

/* =========================
   LOAD ASSIGNMENTS
========================= */

function loadAssignments(batchId) {

    assignmentBody.innerHTML = '';
    assignmentTable.style.display = 'none';

    fetch(`/batches/${batchId}/assignments`)
        .then(res => res.json())
        .then(data => {

            if (!data.length) return;

            assignmentTable.style.display = 'table';

            data.forEach((a, index) => {

                let billed = billedAssignments[a.id] ?? '';

                const row = `
<tr>
<td>${index + 1}</td>
<td>${a.assignment_name}</td>
<td>${a.requirement}</td>
<td>${a.remaining}</td>
<td>${a.build}</td>
<td>
<input type="number"
name="billed_assignments[${a.id}]"
value="${billed}"
min="0"
max="${a.build}"
class="form-input">
</td>
</tr>
`;

                assignmentBody.insertAdjacentHTML('beforeend', row);

            });

        })
        .catch(error => {
            console.error("Assignment load error:", error);
        });
}


/* =========================
   LOAD PO ITEMS
========================= */

function loadPoItems(poId) {

    poBody.innerHTML = '';
    poTable.style.display = 'none';

    fetch(`/po/${poId}/items`)
        .then(res => res.json())
        .then(items => {

            if (!items.length) return;

            poTable.style.display = 'table';

            items.forEach((item, index) => {

                let remaining = item.remaining_qty ?? (item.quantity - item.used_quantity);

                let billed = billedPoItems[item.id] ?? '';

                const row = `
<tr>
<td>${index+1}</td>
<td>${item.item}</td>
<td>${item.quantity}</td>
<td>${remaining}</td>
<td>${item.value}</td>
<td>
<input type="number"
name="billed_po_items[${item.id}]"
value="${billed}"
min="0"
max="${remaining}"
class="form-input billed-po"
oninput="updateBatchValue()">
</td>
</tr>
`;

                poBody.insertAdjacentHTML('beforeend', row);

            });

           setTimeout(updateBatchValue, 400);

        })
        .catch(error => {
            console.error("PO items load error:", error);
        });
}


/* =========================
   BATCH CHANGE EVENT
========================= */

batchSelect.addEventListener('change', () => {

    const selected = batchSelect.options[batchSelect.selectedIndex];

    if (!selected.value) {

        batchSizeInput.value = '';
        batchQuantityHidden.value = '';
        valueField.value = '';

        assignmentBody.innerHTML = '';
        assignmentTable.style.display = 'none';

        poBody.innerHTML = '';
        poTable.style.display = 'none';

        return;
    }

    const batchId = selected.value;
    const batchSize = parseFloat(selected.dataset.size) || 0;

    batchSizeInput.value = batchSize;

    fetch(`/invoices/batch-info/${batchId}`)
        .then(res => res.json())
        .then(data => {

            let batchSize = parseFloat(data.batch_size) || 0;
            let remaining = parseFloat(data.remaining_quantity) || 0;

            batchSizeInput.value = batchSize;
            if(!batchQuantityHidden.value){
    batchQuantityHidden.value = {{ $invoice->batch_quantity }};
}

        });


    loadAssignments(batchId);

    const poId = selected.dataset.po;

    if (poId) {
        loadPoItems(poId);
    }

});


/* =========================
   LOAD DATA ON PAGE LOAD
========================= */

window.addEventListener('load', () => {

    const selected = batchSelect.options[batchSelect.selectedIndex];

    if (!selected.value) return;

    const batchId = selected.value;
    const batchSize = parseFloat(selected.dataset.size) || 0;

    batchSizeInput.value = batchSize;

    fetch(`/invoices/batch-info/${batchId}`)
        .then(res => res.json())
        .then(data => {

            let batchSize = parseFloat(data.batch_size) || 0;
            let remaining = parseFloat(data.remaining_quantity) || 0;

            batchSizeInput.value = batchSize;
            batchQuantityHidden.value = {{ $invoice->batch_quantity }};

        });

    loadAssignments(batchId);

    const poId = selected.dataset.po;

    if (poId) {
        loadPoItems(poId);
    }

});
</script>

@endsection