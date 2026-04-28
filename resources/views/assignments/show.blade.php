use Illuminate\Support\Str;

@extends('layouts.app')

@section('content')
<style>
/* ================= COMMON TABLE STYLE ================= */
.custom-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}

.custom-table thead {
    background: #f9fafb;
}

.custom-table th,
.custom-table td {
    padding: 14px 16px;
    font-size: 14px;
    border-bottom: 1px solid #f1f5f9;
}

.custom-table th {
    font-weight: 600;
    color: #374151;
    text-align: left;
}

.custom-table td {
    color: #4b5563;
    vertical-align: middle;
}

.custom-table tbody tr:hover {
    background: #f9fafb;
}

.custom-table tbody tr:last-child td {
    border-bottom: none;
}

/* Status Badge */
.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.badge-primary {
    background: #4f46e5;
    color: #fff;
}

.badge-info {
    background: #0ea5e9;
    color: #fff;
}

.badge-success {
    background: #10b981;
    color: #fff;
}

.badge-warning {
    background: #f59e0b;
    color: #fff;
}

/* Empty row */
.empty-row {
    text-align: center;
    padding: 20px;
    color: #9ca3af;
    font-weight: 500;
}

/* Header buttons */
.header-btn {
    padding: 8px 14px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}

.header-btn.back {
    background: #e5e7eb;
    color: #4f46e5;
    text-decoration: none;
}

.header-btn.status {
    background: #22c55e;
    color: #fff;
}

.header-btn.batch {
    background: #059669;
    color: #fff;
}

.header-btn.form {
    background: #0ea5e9;
    color: #fff;
}

/* Basic Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}

/* Modal common */
.modal-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .5);
    justify-content: center;
    align-items: center;
    z-index: 50;
}

.modal-box {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    width: 100%;
    max-width: 600px;
    max-height: 80vh;
    overflow: auto;
}

.modal-box h3 {
    margin-bottom: 14px;
    color: #4f46e5;
}

.modal-box button {
    cursor: pointer;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
}

.link-primary{
    color:#2563eb;
    font-weight:600;
    text-decoration:none;
}

.link-primary:hover{
    text-decoration:underline;
}
</style>

<div style="max-width:1200px; margin:auto; background:white; padding:28px; border-radius:18px;">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:22px;">
        <h2 style="font-size:26px; font-weight:700; color:#4f46e5;">
            Assignment Details
        </h2>

        <div style="display:flex; gap:10px;">
            <a href="{{ route('assignments.index') }}" class="header-btn back">← Back</a>
            <button onclick="openStatusModal()" class="header-btn status">🔄 Status</button>
            <!-- <button onclick="openBatchModal()" class="header-btn batch">+ Add Batch</button> -->
            <button onclick="openFormModal()" class="header-btn form">+ Add Form</button>
        </div>
    </div>

    {{-- ================= BASIC INFO ================= --}}
    <h3>Basic Information</h3>
    <div class="info-grid">
        <div><strong>ID</strong><br>{{ $assignment->id }}</div>
        <div><strong>Name</strong><br>{{ $assignment->assignment_name }}</div>
        <div><strong>Date</strong><br>{{ \Carbon\Carbon::parse($assignment->date)->format('d M Y') }}</div>
        <div><strong>Format</strong><br>{{ $assignment->format?->type ?? 'N/A' }}</div>
        <div><strong>Requirement</strong><br>{{ $assignment->requirement }}</div>
        <div><strong>Status</strong><br>
            <span class="badge badge-success">{{ $assignment->status ?? 'Pending' }}</span>
        </div>
        <div><strong>Batch Type</strong><br>{{ $assignment->batch_type ?? '-' }}</div>
        <div><strong>Sourcing Machine</strong><br>{{ $assignment->sourcing_machine ?? '-' }}</div>
        <div><strong>Business</strong><br>{{ $assignment->business ?? '-' }}</div>
        <div><strong>Region</strong><br>{{ $assignment->region ?? '-' }}</div>
        <div><strong>Position Name</strong><br>{{ $assignment->position_name ?? '-' }}</div>
        <div><strong>Monthly CTC</strong><br>{{ $assignment->monthly_ctc ?? '-' }}</div>
        <div><strong>Level</strong><br>{{ $assignment->level ?? '-' }}</div>
        <div><strong>FT/PT</strong><br>{{ $assignment->ft_pt ?? '-' }}</div>
        <div><strong>Min Education</strong><br>{{ $assignment->minimum_education_qualification ?? '-' }}</div>
        <div><strong>Work Experience</strong><br>{{ $assignment->work_experience ?? '-' }}</div>
    </div>

    {{-- ================= LOCATION ================= --}}
    <h3>Location</h3>
    <div class="info-grid">
        <div><strong>State</strong><br>{{ $assignment->state }}</div>
        <div><strong>District</strong><br>{{ $assignment->district }}</div>
        <div><strong>Location</strong><br>{{ $assignment->location }}</div>
    </div>

    {{-- ================= HR ================= --}}
    <h3>HR Details</h3>
    <div class="info-grid">
        <div><strong>Name</strong><br>{{ $assignment->hr?->name ?? 'N/A' }}</div>
        <div><strong>Mobile</strong><br>{{ $assignment->hr?->mobile ?? 'N/A' }}</div>
        <div><strong>Email</strong><br>{{ $assignment->hr?->email ?? 'N/A' }}</div>
        <div><strong>State</strong><br>{{ $assignment->hr?->state ?? '-' }}</div>
    </div>

    {{-- ================= STORE MANAGER ================= --}}
    <h3>Store Manager Details</h3>
    <div class="info-grid">
        <div><strong>Name</strong><br>{{ $assignment->sm_name ?? 'N/A' }}</div>
        <div><strong>Mobile</strong><br>{{ $assignment->sm_mobile ?? 'N/A' }}</div>
        <div><strong>Email</strong><br>{{ $assignment->sm_email ?? 'N/A' }}</div>
        <div><strong>Store Code</strong><br>{{ $assignment->store_code ?? '-' }}</div>
    </div>

    {{-- ================= STATUS HISTORY ================= --}}
    <h3>Status History</h3>
    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Status</th>
                <th>Status Date</th>
                <th>Remark</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignment->statusHistory as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><span class="badge badge-primary">{{ $row->status }}</span></td>
                <td>{{ $row->status_date ? \Carbon\Carbon::parse($row->status_date)->format('d M Y') : '-' }}</td>
                <td>{{ $row->remark ?? '-' }}</td>
                <td>{{ $row->created_at->format('d M Y h:i A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-row">No status changes yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>


    {{-- ================= BATCH DETAILS ================= --}}
    <h3>Batch Details</h3>
    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Batch Code</th>
                <th>District</th>
                <th>Address</th>
                <th>Batch Size</th>
                <th>In Batch</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($assignment->batches as $index => $batch)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td>
                    <a href="{{ route('batches.show', $batch->id) }}" class="batch-link"   target="_blank">
                        {{ $batch->batch_code }}
                    </a>
                </td>

                <td>{{ $batch->district ?? '-' }}</td>

                <td title="{{ $batch->address }}">
                    {{ \Illuminate\Support\Str::limit($batch->address,35) }}
                </td>

                <td>
                    <span class="badge badge-primary">
                        {{ $batch->batch_size ?? 0 }}
                    </span>
                </td>

                <td>
                    <span class="badge badge-primary">
                        {{ $batch->pivot->build ?? 0 }}
                    </span>
                </td>

                <td>
                    <span class="badge badge-info">
                        {{ $batch->status ?? 'Active' }}
                    </span>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-row">
                    No batches added yet
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= FORM DETAILS ================= --}}
    <h3>Forms</h3>
    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Form Name</th>
                <th>Location</th>
                <th>Validity</th>
                <th>Status</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignment->forms as $index => $form)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $form->form_name }}</td>
                <td>{{ $form->location }}</td>
                <td>{{ $form->valid_from }} – {{ $form->valid_to }}</td>
                <td><span class="badge badge-success">{{ $form->status }}</span></td>
                <td><a href="{{ $form->link }}" target="_blank" style="color:#2563eb; font-weight:600;">Open</a></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-row">No forms added yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>


    {{-- ================= INVOICE DETAILS ================= --}}
    <h3>Invoices (Linked via Batches)</h3>

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Batch Code</th>
                <th>Batch Quantity</th>
                <th>Billed Qty</th>
                <th>Payment Detail</th>
            </tr>
        </thead>

        <tbody>

            @php
            $allInvoices = $assignment->batches->pluck('invoice')->filter();
            @endphp

            @forelse($allInvoices as $index => $invoice)

            @php
            $billedQty = $invoice->assignmentItems
            ->where('assignment_id', $assignment->id)
            ->sum('quantity');
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>

                {{-- Invoice hyperlink --}}
                <td>
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="link-primary">
                        {{ $invoice->invoice_number }}
                    </a>
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                </td>

                {{-- Batch hyperlink --}}
                <td>
                    @if($invoice->batch)
                    <a href="{{ route('batches.show', $invoice->batch->id) }}" class="link-primary" target="_blank">
                        {{ $invoice->batch->batch_code }}
                    </a>
                    @else
                    -
                    @endif
                </td>

                <td>{{ $invoice->batch_quantity }}</td>

                <td>
                    <span class="badge badge-success">
                        {{ $billedQty }}
                    </span>
                </td>

                <td>{{ $invoice->payment_detail ?? '-' }}</td>
            </tr>

            @empty
            <tr>
                <td colspan="7" class="empty-row">
                    No invoices found for this assignment
                </td>
            </tr>
            @endforelse

        </tbody>
    </table>
</div>

{{-- ================= MODALS ================= --}}
{{-- ================= STATUS MODAL ================= --}}
<div id="statusModal"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:22px; border-radius:12px; width:100%; max-width:420px;">
        <h3 style="margin-bottom:14px;">Update Status</h3>
        <form method="POST" action="{{ route('assignments.status', $assignment->id) }}">
            @csrf
            <div style="margin-bottom:12px;">
                <label>Status</label>
                <select name="status" style="width:100%; padding:8px;">
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div style="margin-bottom:12px;">
                <label>Status Date</label>
                <input type="date" name="status_date" value="{{ now()->toDateString() }}"
                    style="width:100%; padding:8px;">
            </div>
            <div style="margin-bottom:14px;">
                <label>Remark</label>
                <textarea name="remark" rows="2" style="width:100%; padding:8px;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeStatusModal()"
                    style="padding:8px 14px; background:#e5e7eb; border:none; border-radius:6px;">Cancel</button>
                <button type="submit"
                    style="padding:8px 14px; background:#6366f1; color:white; border:none; border-radius:6px;">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= BATCH MODAL ================= --}}
<!-- <div id="batchModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5);">
    <div style="background:white; width:500px; margin:10% auto; padding:20px; border-radius:12px;">
        <h3 style="font-weight:600; margin-bottom:10px;">Select Batch</h3>
        <form method="POST" action="{{ route('assignments.attachBatch', $assignment->id) }}">
            @csrf
            <select name="batch_id" required style="width:100%; padding:10px;">
                <option value="">-- Select Batch --</option>
                @foreach($allBatches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->batch_code }} ({{ $batch->city }})</option>
                @endforeach
            </select>
            <div style="margin-top:15px; text-align:right;">
                <button type="button" onclick="closeBatchModal()">Cancel</button>
                <button style="background:#059669; color:white; padding:6px 14px; border-radius:6px;">Add</button>
            </div>
        </form>
    </div>
</div> -->

{{-- ================= FORM MODAL ================= --}}
<div id="formModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:50;">
    <div
        style="background:white; width:800px; margin:5% auto; padding:20px; border-radius:14px; max-height:80vh; overflow:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:18px; font-weight:600;">Select Forms</h3>
            <button onclick="closeFormModal()">✖</button>
        </div>
        <table style="width:100%; border-collapse:collapse; margin-top:12px;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th></th>
                    <th>Form Name</th>
                    <th>Location</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th>Status</th>
                    <th>Registrations</th>
                </tr>
            </thead>
            <tbody id="formTableBody">
                <tr>
                    <td colspan="7" style="text-align:center; padding:12px;">Loading forms...</td>
                </tr>
            </tbody>
        </table>
        <div style="text-align:right; margin-top:15px;">
            <button onclick="addSelectedForms()"
                style="background:#0ea5e9; color:white; padding:8px 16px; border-radius:8px;">Add Selected</button>
        </div>
    </div>
</div>

{{-- ================= JS ================= --}}
<script>
function openStatusModal() {
    document.getElementById('statusModal').style.display = 'flex';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
}

function openBatchModal() {
    document.getElementById('batchModal').style.display = 'block';
}

function closeBatchModal() {
    document.getElementById('batchModal').style.display = 'none';
}

let selectedForm = null;
let allForms = [];

function openFormModal() {
    document.getElementById('formModal').style.display = 'block';
    fetchForms();
}

function closeFormModal() {
    document.getElementById('formModal').style.display = 'none';
}

function fetchForms() {
    fetch('/api/forms')
        .then(res => res.json())
        .then(data => {
            allForms = data;
            let html = '';
            data.forEach(form => {
                html += `<tr style="border-bottom:1px solid #e5e7eb;">
                <td><input type="radio" name="selected_form" value="${form.id}"></td>
                <td>${form.form_name}</td>
                <td>${form.location}</td>
                <td>${form.valid_from}</td>
                <td>${form.valid_to}</td>
                <td>${form.status}</td>
                <td>${form.register_count}</td>
            </tr>`;
            });
            document.getElementById('formTableBody').innerHTML = html;
            document.querySelectorAll('input[name="selected_form"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedForm = allForms.find(f => f.id == this.value);
                });
            });
        }).catch(() => alert('Failed to load forms'));
}

function addSelectedForms() {
    if (!selectedForm) {
        alert('Please select one form');
        return;
    }
    fetch("{{ route('assignments.forms.store', $assignment->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                forms: [{
                    form_id: selectedForm.id,
                    form_name: selectedForm.form_name,
                    location: selectedForm.location,
                    status: selectedForm.status,
                    valid_from: selectedForm.valid_from,
                    valid_to: selectedForm.valid_to,
                    link: selectedForm.link
                }]
            })
        })
        .then(res => res.json())
        .then(() => {
            alert('Form added successfully');
            closeFormModal();
            location.reload();
        })
        .catch(() => alert('Failed to save form'));
}
</script>

@endsection