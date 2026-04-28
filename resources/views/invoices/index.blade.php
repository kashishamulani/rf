@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:22px; font-weight:600; margin:0; color:#111827;">
            <i class="fa-solid fa-file-invoice" style="background:linear-gradient(135deg,#6366f1,#ec4899);
                      -webkit-background-clip:text;
                      -webkit-text-fill-color:transparent;"></i>
            All Invoices
        </h2>

        <a href="{{ route('invoices.create') }}" style="display:flex; align-items:center; gap:8px;
                  padding:10px 18px;
                  background:linear-gradient(135deg,#6366f1,#ec4899);
                  color:#fff; border-radius:12px;
                  font-weight:600; text-decoration:none;
                  box-shadow:0 8px 20px rgba(99,102,241,0.35);">
            <i class="fa-solid fa-circle-plus"></i> Add Invoice
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);
                color:#065f46; padding:12px 16px;
                border-radius:12px; margin-bottom:18px;">
        {{ session('success') }}
    </div>
    @endif


    <form method="GET" action="{{ route('invoices.index') }}" style="margin-bottom:18px; display:flex; gap:10px;">

        <select name="assignment_id" style="padding:8px 12px;border-radius:10px;border:1px solid #e5e7eb;">

            <option value="">All Assignments</option>

            @php
            $totalAmount = 0;
            $totalPaid = 0;
            $totalRemaining = 0;
            @endphp

            @foreach($assignments as $assignment)
            <option value="{{ $assignment->id }}" {{ request('assignment_id') == $assignment->id ? 'selected' : '' }}>
                {{ $assignment->assignment_name }}
            </option>
            @endforeach

        </select>

        <button type="submit" style="padding:8px 16px;background:#6366f1;color:white;border:none;border-radius:10px;">
            Filter
        </button>

    </form>

    {{-- TABLE --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;
                      border-collapse:collapse;
                      background:rgba(255,255,255,0.8);
                      backdrop-filter:blur(16px);
                      border-radius:16px;
                      box-shadow:0 10px 30px rgba(0,0,0,0.08);">

            <thead>
                <tr style="background:linear-gradient(135deg,#eef2ff,#fdf2f8);
                           border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px;">#</th>
                    <th style="padding:14px;">Invoice No</th>
                    <th style="padding:14px;">Date</th>
                    <th style="padding:14px;">Batch</th>
                    <th style="padding:14px;">Assignment</th>
                    <th style="padding:14px;">Total Amount</th>
                    <th style="padding:14px;">Total Paid</th>
                    <th style="padding:14px;">Remaining</th>

                    <th style="padding:14px;">Status</th>
                    <th style="padding:12px;">Payment</th>
                    <th style="padding:14px; text-align:center;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($invoices as $invoice)


                @php
                $totalAmount += $invoice->batch_value;
                $totalPaid += $invoice->total_paid;
                $totalRemaining += $invoice->remaining_amount;
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9; text-align:center;">

                    {{-- SERIAL NUMBER --}}
                    <td style="padding:14px;">
                        {{ $loop->iteration }}
                    </td>

                    <td style="padding:14px; font-weight:600; color:#3730a3;">
                        {{ $invoice->invoice_number }}
                    </td>

                    <td style="padding:14px;">
                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                    </td>

                    <td style="padding:14px;">
                        {{ $invoice->batch->batch_code ?? '-' }}
                    </td>

                    <!-- <td style="padding:14px;">
{{ $invoice->assignmentItems->pluck('assignment.assignment_name')->join(', ') }}
</td> -->

                    <td style="padding:14px; text-align:center;">
                        <button class="view-assignments-btn"
                            data-assignments='@json($invoice->assignmentItems->pluck("assignment.assignment_name"))'
                            style="padding:6px 12px; font-size:13px; border-radius:8px; background:#6366f1; color:white; border:none; cursor:pointer;">
                            📦 View
                        </button>
                    </td>
                    <td style="padding:14px; font-weight:600; color:#3730a3;">
                        ₹{{ number_format($invoice->batch_value,2) }}
                    </td>

                    <td style="padding:14px; font-weight:600; color:#166534;">
                        ₹{{ number_format($invoice->total_paid,2) }}
                    </td>
                    <td
                        style="padding:14px; font-weight:600;color: {{ $invoice->remaining_amount==0 ? '#166534' : '#991b1b' }};">
                        ₹{{ number_format($invoice->remaining_amount,2) }}
                    </td>


                    {{-- STATUS --}}
                    <td style="padding:14px;">
                        <div style="display:flex; gap:8px; align-items:center; justify-content:center;">

                            <select class="invoice-status" data-id="{{ $invoice->id }}" style="padding:4px 10px; border-radius:999px; font-size:12px;
background:
    {{ $invoice->status=='paid' ? '#dcfce7' :
       ($invoice->status=='partial_paid' ? '#fef9c3' :
       ($invoice->status=='sent' ? '#dbeafe' :
       ($invoice->status=='cancelled' ? '#fee2e2' : '#e0e7ff'))) }};
color:
    {{ $invoice->status=='paid' ? '#166534' :
       ($invoice->status=='partial_paid' ? '#854d0e' :
       ($invoice->status=='sent' ? '#1e40af' :
       ($invoice->status=='cancelled' ? '#991b1b' : '#3730a3'))) }};
border:1px solid #e5e7eb;">

                                <option value="generated" {{ $invoice->status=='generated' ? 'selected' : '' }}>
                                    Generated</option>
                                <option value="sent" {{ $invoice->status=='sent' ? 'selected' : '' }}>Sent</option>
                                <option value="partial_paid" {{ $invoice->status=='partial_paid' ? 'selected' : '' }}>
                                    Partial Paid</option>
                                <option value="paid" {{ $invoice->status=='paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ $invoice->status=='cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>

                            <button type="button" class="update-status-btn" data-id="{{ $invoice->id }}" style="padding:4px 12px; font-size:12px; border-radius:8px;
                background:#6366f1; color:white; border:none; cursor:pointer;">
                                Update
                            </button>

                        </div>
                    </td>



                    {{-- PAYMENT + VIEW --}}
                    <td style="padding:14px; text-align:center;">

                        <a href="{{ route('invoices.payments', $invoice->id) }}"
                            style="padding:6px 12px;background:#6366f1;color:white;border-radius:8px;text-decoration:none;font-size:13px;margin-right:6px;display:inline-flex;align-items:center;gap:5px;">
                            💳
                        </a>

                        <a href="{{ route('invoices.show', $invoice->id) }}"
                            style="padding:6px 12px;background:#10b981;color:white;border-radius:8px;text-decoration:none;font-size:13px;display:inline-flex;align-items:center;gap:5px;">
                            👁
                        </a>

                    </td>

                    {{-- ACTIONS --}}
                    <td style="padding:14px;">
                        <div style="display:flex; gap:14px; justify-content:center; align-items:center;">

                            <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank" title="Print Invoice"
                                style="color:#6366f1;">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>

                            <a href="{{ route('invoices.edit', $invoice->id) }}" title="Edit" style="color:#f59e0b;">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this invoice?')"
                                    style="background:none; border:none; color:#ef4444; cursor:pointer;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>


                @empty
                <tr>
                    <td colspan="8" style="padding:16px; text-align:center; color:#9ca3af;">
                        No invoices found.
                    </td>
                </tr>
                @endforelse


                <tr style="background:#f9fafb; font-weight:700;">
    
    <td colspan="5" style="padding:14px; text-align:right;">
        TOTAL
    </td>

    <td style="padding:14px; color:#3730a3;">
        ₹{{ number_format($totalAmount,2) }}
    </td>

    <td style="padding:14px; color:#166534;">
        ₹{{ number_format($totalPaid,2) }}
    </td>

    <td style="padding:14px; color:#991b1b;">
        ₹{{ number_format($totalRemaining,2) }}
    </td>

    <td colspan="3"></td>

</tr>
            </tbody>
        </table>
    </div>

</div>

{{-- Assignment Modal --}}
<div id="assignmentsModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;">
    <div style="background:#fff; border-radius:12px; padding:24px; max-width:400px; width:90%; position:relative;">
        <h3 style="margin-top:0; margin-bottom:16px; font-size:18px; font-weight:600;">Assignments</h3>
        <ul id="assignmentsList" style="padding-left:20px; margin-bottom:16px; list-style:disc;">
            <!-- Assignments will be injected here -->
        </ul>
        <button id="closeAssignmentsModal"
            style="padding:8px 16px; background:#6366f1; color:white; border:none; border-radius:8px; cursor:pointer;">
            Close
        </button>
    </div>
</div>

{{-- STATUS UPDATE SCRIPT --}}
<script>
document.querySelectorAll('.update-status-btn').forEach(button => {
    button.addEventListener('click', function() {

        let invoiceId = this.dataset.id;
        let select = document.querySelector(`.invoice-status[data-id="${invoiceId}"]`);
        let status = select.value;

        fetch(`/invoices/${invoiceId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {

                    const styles = {
                        generated: {
                            bg: '#e0e7ff',
                            color: '#3730a3'
                        },
                        sent: {
                            bg: '#dbeafe',
                            color: '#1e40af'
                        },
                        partial_paid: {
                            bg: '#fef9c3',
                            color: '#854d0e'
                        },
                        paid: {
                            bg: '#dcfce7',
                            color: '#166534'
                        },
                        cancelled: {
                            bg: '#fee2e2',
                            color: '#991b1b'
                        }
                    };

                    let style = styles[status] || styles.generated;
                    select.style.background = style.bg;
                    select.style.color = style.color;

                    alert('Status updated successfully ✅');
                } else {
                    alert('Status update failed ❌');
                }
            })
            .catch(() => alert('Something went wrong ❌'));
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const modal = document.getElementById('assignmentsModal');
    const list = document.getElementById('assignmentsList');
    const closeBtn = document.getElementById('closeAssignmentsModal');

    // Open modal on button click
    document.querySelectorAll('.view-assignments-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const assignments = JSON.parse(this.dataset.assignments);

            // Clear previous list
            list.innerHTML = '';

            if (assignments.length > 0) {
                assignments.forEach(a => {
                    const li = document.createElement('li');
                    li.textContent = a;
                    list.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = 'No assignments.';
                list.appendChild(li);
            }

            modal.style.display = 'flex';
        });
    });

    // Close modal
    closeBtn.addEventListener('click', () => modal.style.display = 'none');

    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get the assignment dropdown
    const assignmentSelect = document.querySelector('select[name="assignment_id"]');
    if (assignmentSelect) {
        // Reset to the first option on page load
        assignmentSelect.selectedIndex = 0;
    }
});
</script>
@endsection