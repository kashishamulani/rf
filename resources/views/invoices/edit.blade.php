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

        <!-- STUDENTS TABLE -->

        <div class="table-wrapper">

            <div class="table-title">Batch Students</div>

            <table id="studentTable" class="po-table">

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
                                class="form-input">
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