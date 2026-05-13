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
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #4338ca;
    font-size: 14px;
    margin-bottom: 4px;
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
    margin-top: 16px;
    display: none;
}

.po-table thead {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
}

.po-table th,
.po-table td {
    padding: 9px;
    font-size: 13px;
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

<div style="padding:12px;width:100%;display:flex;flex-direction:column;align-items:center;">

    <div style="width:100%;max-width:1100px;display:flex;justify-content:flex-end;margin-bottom:12px;">
        <a href="{{ route('invoices.index') }}" class="btn-back">← Back</a>
    </div>

    <form action="{{ route('invoices.store') }}" method="POST" class="form-card">
        @csrf

        @if($errors->any())
        <div class="alert-error">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h2 style="text-align:center;margin-bottom:16px;
background:linear-gradient(135deg,#6366f1,#ec4899);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;">
            Create Invoice
        </h2>

        <div class="grid-2">

            <div class="form-group">
                <label class="form-label">Invoice Number *</label>
                <input type="text" name="invoice_number" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Invoice Date *</label>
                <input type="date" name="invoice_date" value="{{ old('payment_date',date('Y-m-d')) }}"
                    class="form-input" required>
            </div>

        </div>

        <div class="grid-3" style="margin-top:10px;">

            <div class="form-group">
                <label class="form-label">Batch *</label>
                <select name="batch_id" id="batchSelect" class="form-select" required>
                    <option value="">-- Select Batch --</option>

                    @foreach($batches as $batch)
                    <option value="{{ $batch->id }}" data-size="{{ $batch->batch_size }}" data-po="{{ $batch->po_id }}">
                        {{ $batch->batch_code }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Total Batch Size</label>
                <input type="number" id="batchSize" class="form-input" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Batch Value (Incl GST)</label>
                <input type="text" id="batchValue" class="form-input" readonly>
            </div>

        </div>

        <input type="hidden" id="batchQuantityHidden" name="batch_quantity">

        <div class="form-group">
            <label class="form-label">Payment Detail</label>
            <textarea name="payment_detail" rows="2" class="form-textarea"></textarea>
        </div>

        <!-- Assignment Table -->

        <div class="table-wrapper">

            <div class="table-title"></div>Assignment Billing
        </div>

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

<!-- PO Table -->

<div class="table-wrapper">

    <div class="table-title">PO Item Billing</div>

    <table id="invoicePoTable" class="po-table">

        <thead>
            <tr>
                <th>S.No</th>
                <th>Item</th>
                <th>PO Qty</th>
                <th>Remaining</th>
                <th>Rate</th>
                <th>Billed Qty</th>
            </tr>
        </thead>

        <tbody id="invoicePoBody"></tbody>

    </table>

</div>


<!-- Students Table -->

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

<div style="text-align:center;margin-top:20px;">
    <button type="submit" class="btn-primary">Save Invoice</button>
</div>

</form>
</div>

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
            assignmentTable.style.display = 'table';

            if (!data.length) {

                assignmentBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:12px;">
                            No assignment billing rows available for this batch.
                        </td>
                    </tr>
                `;

                return;
            }

            data.forEach((a, i) => {

                let row = `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${a.assignment_name ?? '-'}</td>
                        <td>${a.requirement ?? 0}</td>
                        <td>${a.remaining ?? 0}</td>
                        <td>${a.build ?? 0}</td>
                        <td>
                            <input type="number"
                                name="billed_assignments[${a.id}]"
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

            console.error('Assignment Error:', error);

            assignmentBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center;padding:12px;color:red;">
                        Unable to load assignment billing data.
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

    if (!poId) {
        console.log('No PO ID provided');
        return;
    }

    fetch(`/po/${poId}/items`)
        .then(res => {

            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }

            return res.json();

        })
        .then(items => {

            poBody.innerHTML = '';

            if (!items.length) {

                poBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:12px;">
                            No PO items found for this batch.
                        </td>
                    </tr>
                `;

                poTable.style.display = 'table';
                return;
            }

            poTable.style.display = 'table';

            items.forEach((item, i) => {

                let remaining = item.remaining_qty ?? 
                    ((item.quantity || 0) - (item.used_quantity || 0));

                let row = `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${item.item || item.item_name || 'N/A'}</td>
                        <td>${item.quantity || 0}</td>
                        <td>${remaining}</td>
                        <td>${item.value || 0}</td>
                        <td>
                            <input type="number"
                                name="billed_po_items[${item.id}]"
                                min="0"
                                max="${remaining}"
                                class="form-input billed-po"
                                oninput="updateBatchValue()">
                        </td>
                    </tr>
                `;

                poBody.insertAdjacentHTML('beforeend', row);

            });

        })
        .catch(error => {

            console.error('PO Item Error:', error);

            poBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center;padding:12px;color:red;">
                        Error loading PO items.
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
            studentTable.style.display = 'table';

            if (!data.length) {

                studentBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:12px;">
                            No students found in this batch.
                        </td>
                    </tr>
                `;

                return;
            }

            data.forEach((student, i) => {

                let row = `
                    <tr>
                        <td>${i + 1}</td>
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

            console.error('Student Error:', error);

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
| BATCH CHANGE
|--------------------------------------------------------------------------
*/

batchSelect.addEventListener('change', () => {

    let selected = batchSelect.options[batchSelect.selectedIndex];

    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    if (!selected.value) {

        batchSizeInput.value = '';
        batchQuantityHidden.value = '';
        valueField.value = '';

        assignmentBody.innerHTML = '';
        poBody.innerHTML = '';
        studentBody.innerHTML = '';

        assignmentTable.style.display = 'none';
        poTable.style.display = 'none';
        studentTable.style.display = 'none';

        return;
    }

    let batchId = selected.value;
    let poId = selected.getAttribute('data-po');

    fetch(`/invoices/batch-info/${batchId}`)
        .then(res => res.json())
        .then(data => {

            let batchSize = parseFloat(data.batch_size) || 0;
            let remaining = parseFloat(data.remaining_quantity) || 0;

            batchSizeInput.value = batchSize;
            batchQuantityHidden.value = remaining;

            /*
            |--------------------------------------------------------------------------
            | LOAD TABLES
            |--------------------------------------------------------------------------
            */

            loadAssignments(batchId);

            loadStudents(batchId);

            const finalPoId = poId || data.po_id;

            if (finalPoId) {

                loadPoItems(finalPoId);

            } else {

                console.error('No PO ID found for this batch');

                poTable.style.display = 'none';

            }

        })
        .catch(error => {

            console.error('Batch Info Error:', error);

            assignmentBody.innerHTML = '';
            poBody.innerHTML = '';
            studentBody.innerHTML = '';

            assignmentTable.style.display = 'none';
            poTable.style.display = 'none';
            studentTable.style.display = 'none';

        });

});
</script>

@endsection