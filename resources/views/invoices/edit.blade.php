@extends('layouts.app')

@section('content')

<style>
.field {
    display: flex;
    flex-direction: column;
}

.field label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #4338ca;
}

.field input,
.field select {
    padding: 6px;
    border-radius: 4px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
}

/* BACK BUTTON */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s;
}

.back-btn:hover {
    transform: translateY(-1px);
}

/* SECTION HEADINGS */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 8px;
    margin-top: 24px;
}

/* GRID SYSTEM */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 6px;
    margin-bottom: 8px;
}

/* ALERT ERROR */
.alert-error {
    margin-bottom: 24px;
    padding: 16px 18px;
    border-radius: 14px;
    background: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

/* TABLE STYLES */
.po-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 16px;
    overflow: hidden;
}

.po-table thead {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
}

.po-table th,
.po-table td {
    padding: 12px 10px;
    font-size: 13px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.po-table tbody tr:hover {
    background: rgba(99, 102, 241, 0.05);
}

.table-wrapper {
    margin-top: 18px;
    width: 100%;
    overflow-x: auto;
}

.table-title {
    font-weight: 600;
    color: #4f46e5;
    margin-bottom: 10px;
    font-size: 15px;
}

/* BUTTON STYLES */
.btn-primary {
    margin-top: 20px;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    border: none;
    cursor: pointer;
    font-size: 14px;
}

/* TYPOGRAPHY */
h2 {
    font-size: 18px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 8px;
    text-align: center;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 11px;
}

input[type="text"],
input[type="file"],
input[type="number"],
input[type="date"],
input[type="email"],
select,
textarea {
    padding: 6px 8px;
    font-size: 12px;
    border-radius: 6px;
    border: 1px solid #ddd;
    width: 100%;
    height: 42px;
    box-sizing: border-box;
}

textarea {
    height: auto;
    min-height: 60px;
}

/* INFO BOX */
.info-box {
    margin-bottom: 20px;
    padding: 14px 18px;
    border-radius: 12px;
    background: #dbeafe;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    font-size: 13px;
}
</style>

<div style="display:flex; justify-content:center; width:100%;">

    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}" enctype="multipart/form-data"
        style="width:100%; background:rgba(255,255,255,0.85); padding:12px; border-radius:18px; backdrop-filter:blur(14px); box-shadow:0 20px 40px rgba(0,0,0,0.08);">

        @csrf
        @method('PUT')

        {{-- ERRORS --}}
        @if ($errors->any())
        <div
            style="margin-bottom:24px; padding:16px 18px; border-radius:14px; background:#fee2e2; border:1px solid #fecaca; color:#991b1b;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top:8px; padding-left:18px;">
                @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; gap:12px; ">

            {{-- HEADER BAR --}}
            <div style="width:100%;">
                <div class="form-top-bar" style="display: flex; justify-content: flex-end;">
                    <a href="{{ route('invoices.index') }}" class="back-btn">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <h2 style="font-size:18px; font-weight:700; color:#4f46e5; margin-bottom:8px; text-align:center;">
                <i class="fa-solid fa-pen"></i> Edit Invoice
            </h2>
        </div>

        {{-- INFO BOX --}}
        <!-- <div class="info-box">
            <i class="fa-solid fa-info-circle"></i>
            <strong>Guidelines:</strong> Fill all required fields marked with <span style="color:red">*</span>.
        </div> -->

        {{-- INVOICE DETAILS SECTION --}}
        <div class="step active" style="margin-bottom:24px;">
      
            <div class="form-grid">
                <div class="field">
                    <label>Invoice Number <span style="color:red">*</span></label>
                    <input type="text" name="invoice_number" required class="form-control"
                        value="{{ old('invoice_number', $invoice->invoice_number) }}">
                </div>

                <div class="field">
                    <label>Invoice Date <span style="color:red">*</span></label>
                    <input type="date" name="invoice_date" required class="form-control"
                        value="{{ old('invoice_date', $invoice->invoice_date) }}">
                </div>
            </div>
        </div>

        {{-- BATCH DETAILS SECTION --}}
        <div class="step active" style="margin-bottom:24px;">
         
            <div class="form-grid">
                <div class="field">
                    <label>Batch <span style="color:red">*</span></label>
                    <select name="batch_id" id="batchSelect" class="form-select" required>
                        <option value="">-- Select Batch --</option>
                        @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" 
                            data-size="{{ $batch->batch_size }}" 
                            data-po="{{ $batch->po_id }}"
                            {{ $invoice->batch_id == $batch->id ? 'selected' : '' }}>
                            {{ $batch->batch_code }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Batch Size</label>
                    <input type="number" id="batchSize" class="form-input" readonly>
                    <input type="hidden" name="batch_quantity" id="batchQuantityHidden" value="{{ $invoice->batch_quantity }}">
                </div>

                <div class="field">
                    <label>Batch Value (Incl. 18% GST)</label>
                    <input type="text" id="batchValue" class="form-input" readonly>
                </div>
            </div>
        </div>

        <div class="field" style="margin-bottom:16px;">
            <label>Payment Detail</label>
            <textarea name="payment_detail" rows="2" class="form-textarea" style="height: auto;">{{ old('payment_detail', $invoice->payment_detail) }}</textarea>
        </div>

        {{-- BATCH STUDENTS TABLE --}}
        <div class="table-wrapper">
            <div class="table-title">Batch Students</div>
            <table id="studentTable" class="po-table" style="display: none;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Name</th>
                        <th>Assignment</th>
                        <th>Mobile</th>
                        <th>State</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody id="studentBody"></tbody>
            </table>
        </div>

        {{-- ASSIGNMENT BILLING TABLE --}}
        <div class="table-wrapper">
            <div class="table-title">Assignment Billing</div>
            <table id="invoiceAssignmentTable" class="po-table" style="display: none;">
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

        {{-- PO ITEM BILLING TABLE --}}
        <div class="table-wrapper">
            <div class="table-title">PO Item Billing</div>
            <table id="invoicePoTable" class="po-table" style="display: none;">
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

const studentTable = document.getElementById('studentTable');
const studentBody = document.getElementById('studentBody');


/*
|--------------------------------------------------------------------------
| UPDATE BATCH VALUE
|--------------------------------------------------------------------------
*/

function updateBatchValue() {

    let subtotal = 0;

    document.querySelectorAll('#invoicePoBody tr').forEach(row => {

        let qtyInput = row.querySelector('input');

        if (!qtyInput) return;

        let rate = parseFloat(row.children[4].innerText) || 0;
        let qty = parseFloat(qtyInput.value) || 0;

        subtotal += qty * rate;

    });

    let gst = subtotal * 0.18;
    let total = subtotal + gst;

    valueField.value = total.toFixed(2);

}


/*
|--------------------------------------------------------------------------
| LOAD ASSIGNMENTS
|--------------------------------------------------------------------------
*/

function loadAssignments(batchId) {

    assignmentBody.innerHTML = '';
    assignmentTable.style.display = 'none';

    fetch(`/invoices/batch-assignments/${batchId}`)
        .then(res => res.json())
        .then(data => {

            assignmentBody.innerHTML = '';

            if (!data.length) {

                assignmentBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:12px;">
                            No assignment billing rows available.
                        </td>
                    </tr>
                `;

                assignmentTable.style.display = 'table';

                return;
            }

            assignmentTable.style.display = 'table';

            data.forEach((a, index) => {

                let billed = billedAssignments[a.id] ?? '';

                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${a.assignment_name ?? '-'}</td>
                        <td>${a.requirement ?? 0}</td>
                        <td>${a.remaining ?? 0}</td>
                        <td>${a.build ?? 0}</td>
                        <td>
                            <input type="number"
                                name="billed_assignments[${a.id}]"
                                value="${billed}"
                                min="0"
                                max="${a.build ?? 0}"
                                class="form-input"
                                style="width:100px;">
                        </td>
                    </tr>
                `;

                assignmentBody.insertAdjacentHTML('beforeend', row);

            });

        })
        .catch(error => {

            console.error('Assignment load error:', error);

            assignmentBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center;color:red;padding:12px;">
                        Failed to load assignments.
                    </td>
                </tr>
            `;

            assignmentTable.style.display = 'table';

        });

}


/*
|--------------------------------------------------------------------------
| LOAD PO ITEMS
|--------------------------------------------------------------------------
*/

function loadPoItems(poId) {

    poBody.innerHTML = '';
    poTable.style.display = 'none';

    if (!poId) return;

    fetch(`/po/${poId}/items`)
        .then(res => res.json())
        .then(items => {

            poBody.innerHTML = '';

            if (!items.length) {

                poBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:12px;">
                            No PO items found.
                        </td>
                    </tr>
                `;

                poTable.style.display = 'table';

                return;
            }

            poTable.style.display = 'table';

            items.forEach((item, index) => {

                let remaining = item.remaining_qty ??
                    ((item.quantity || 0) - (item.used_quantity || 0));

                let billed = billedPoItems[item.id] ?? '';

                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.item || item.item_name || 'N/A'}</td>
                        <td>${item.quantity || 0}</td>
                        <td>${remaining}</td>
                        <td>${item.value || 0}</td>
                        <td>
                            <input type="number"
                                name="billed_po_items[${item.id}]"
                                value="${billed}"
                                min="0"
                                max="${remaining}"
                                class="form-input billed-po"
                                style="width:100px;"
                                oninput="updateBatchValue()">
                        </td>
                    </tr>
                `;

                poBody.insertAdjacentHTML('beforeend', row);

            });

            setTimeout(updateBatchValue, 300);

        })
        .catch(error => {

            console.error('PO item load error:', error);

            poBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center;color:red;padding:12px;">
                        Failed to load PO items.
                    </td>
                </tr>
            `;

            poTable.style.display = 'table';

        });

}


/*
|--------------------------------------------------------------------------
| LOAD STUDENTS
|--------------------------------------------------------------------------
*/

function loadStudents(batchId) {

    studentBody.innerHTML = '';
    studentTable.style.display = 'none';

    fetch(`/invoices/batch-students/${batchId}`)
        .then(res => res.json())
        .then(data => {

            studentBody.innerHTML = '';

            if (!data.length) {

                studentBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:12px;">
                            No students found in this batch.
                        </td>
                    </tr>
                `;

                studentTable.style.display = 'table';

                return;
            }

            studentTable.style.display = 'table';

            data.forEach((student, index) => {

                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${student.name ?? '-'}</td>
                        <td>${student.assignment_name ?? '-'}</td>
                        <td>${student.mobile ?? '-'}</td>
                        <td>${student.state ?? '-'}</td>
                        <td>${student.city ?? '-'}</td>
                    </tr>
                `;

                studentBody.insertAdjacentHTML('beforeend', row);

            });

        })
        .catch(error => {

            console.error('Student load error:', error);

            studentBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center;color:red;padding:12px;">
                        Failed to load students.
                    </td>
                </tr>
            `;

            studentTable.style.display = 'table';

        });

}


/*
|--------------------------------------------------------------------------
| RESET TABLES
|--------------------------------------------------------------------------
*/

function resetTables() {

    assignmentBody.innerHTML = '';
    poBody.innerHTML = '';
    studentBody.innerHTML = '';

    assignmentTable.style.display = 'none';
    poTable.style.display = 'none';
    studentTable.style.display = 'none';

}


/*
|--------------------------------------------------------------------------
| LOAD BATCH DATA
|--------------------------------------------------------------------------
*/

function loadBatchData(batchId, poId = null) {

    fetch(`/invoices/batch-info/${batchId}`)
        .then(res => res.json())
        .then(data => {

            let batchSize = parseFloat(data.batch_size) || 0;

            batchSizeInput.value = batchSize;

            if (!batchQuantityHidden.value) {
                batchQuantityHidden.value = {{ $invoice->batch_quantity }};
            }

            loadAssignments(batchId);

            loadStudents(batchId);

            const finalPoId = poId || data.po_id;

            if (finalPoId) {
                loadPoItems(finalPoId);
            } else {
                poTable.style.display = 'none';
            }

        })
        .catch(error => {

            console.error('Batch info error:', error);

            resetTables();

        });

}


/*
|--------------------------------------------------------------------------
| BATCH CHANGE EVENT
|--------------------------------------------------------------------------
*/

batchSelect.addEventListener('change', () => {

    const selected = batchSelect.options[batchSelect.selectedIndex];

    if (!selected.value) {

        batchSizeInput.value = '';
        batchQuantityHidden.value = '';
        valueField.value = '';

        resetTables();

        return;
    }

    const batchId = selected.value;
    const poId = selected.dataset.po;

    loadBatchData(batchId, poId);

});


/*
|--------------------------------------------------------------------------
| PAGE LOAD
|--------------------------------------------------------------------------
*/

window.addEventListener('load', () => {

    const selected = batchSelect.options[batchSelect.selectedIndex];

    if (!selected.value) return;

    const batchId = selected.value;
    const poId = selected.dataset.po;

    batchQuantityHidden.value = {{ $invoice->batch_quantity }};

    loadBatchData(batchId, poId);

});
</script>

@endsection