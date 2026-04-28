@extends('layouts.app')

@section('content')

<style>
.form-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    padding: 26px;
    width: 100%;
    max-width: 1100px;
}

/* GRID */
.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

.grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

@media(max-width:900px) {
    .grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width:600px) {

    .grid-2,
    .grid-4 {
        grid-template-columns: 1fr;
    }
}

/* FORM */

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #4338ca;
    font-size: 13px;
    margin-bottom: 5px;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 9px 11px;
    border-radius: 10px;
    border: 1px solid rgba(99, 102, 241, 0.35);
    font-size: 14px;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
}

/* BUTTONS */

.btn-primary {
    background: linear-gradient(135deg, #6366f1, #ec4899);
    color: #fff;
    font-weight: 600;
    border-radius: 12px;
    padding: 10px 26px;
    border: none;
    cursor: pointer;
}

.btn-back {
    background: #e5e7eb;
    padding: 8px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    color: #111827;
}

/* ALERT */

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 14px;
}

/* TABLE */

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f1f5f9;
}

thead th {
    padding: 12px;
    font-size: 13px;
    text-align: left;
}

tbody td {
    padding: 10px;
    font-size: 13px;
    border-bottom: 1px solid #e5e7eb;
}

tbody tr:hover {
    background: #f9fafb;
}

.grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

@media(max-width:768px) {

    .grid-3,
    .grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<div style="padding:12px; width:100%; display:flex; flex-direction:column; align-items:center;">

    <div style="width:100%; max-width:1100px; display:flex; justify-content:flex-end; margin-bottom:12px;">
        <a href="{{ route('payments.index') }}" class="btn-back">← Back</a>
    </div>

    <form action="{{ route('payments.store') }}" method="POST" class="form-card">
        @csrf

        @if($errors->any())
        <div class="alert-error">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h2 style="text-align:center;margin-bottom:18px;
background:linear-gradient(135deg,#6366f1,#ec4899);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;">
            Create Payment
        </h2>

        <div class="grid-2">

            <div class="form-group">
                <label class="form-label">Payment Advisory Number *</label>
                <input type="text" name="payment_advisory_number" class="form-input"
                    value="{{ old('payment_advisory_number') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" class="form-input"
                    value="{{ old('payment_date',date('Y-m-d')) }}" required>
            </div>

        </div>




        {{-- TOTAL + ACCOUNT --}}
        <div class="grid-2" style="margin-top:12px;">

            <div class="form-group">
                <label class="form-label">Total Payment Amount <span style="color:#ef4444;">*</span></label>
                <input type="number" step="0.01" name="amount" id="totalAmount" class="form-input"
                    value="{{ old('amount') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Account <span style="color:#ef4444;">*</span></label>
                <select name="payment_account" class="form-select" required>
                    <option value="">-- Select Account --</option>

                    @foreach($accounts as $account)
                    <option value="{{ $account }}" {{ old('payment_account')==$account?'selected':'' }}>
                        {{ $account }}
                    </option>
                    @endforeach

                </select>
            </div>

        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="2" class="form-textarea">
                {{ old('description') }}
            </textarea>
        </div>


        <h3 style="margin-top:30px;margin-bottom:10px;">Pending Invoices</h3>

        <div style="overflow-x:auto;">

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Date</th>
                        <th>Batch</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Payment Type</th>
                        <th>Allocate</th>
                        <th>After Payment</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($invoices as $invoice)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td style="font-weight:600;">
                            {{ $invoice->invoice_number }}
                        </td>

                        <td>
                            {{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : '-' }}
                        </td>

                        <td>
                            {{ $invoice->batch->batch_code ?? '-' }}
                        </td>

                        <td>
                            ₹{{ number_format($invoice->total_amount ?? 0, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($invoice->paid_amount,2) }}
                        </td>

                        <td>
                            ₹{{ number_format($invoice->remaining_amount,2) }}
                        </td>

                        <td>
                            <select name="payment_type[{{ $invoice->id }}]" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="bill">Bill Amount</option>
                                <option value="gst">GST</option>
                                <option value="tds">TDS</option>
                            </select>
                        </td>

                        <td>
                            <input type="number" step="0.01" name="payments[{{ $invoice->id }}]"
                                class="form-input invoice-payment" data-remaining="{{ $invoice->remaining_amount }}"
                                value="0">
                        </td>

                        <td>
                            <input type="text" class="form-input invoice-after-remaining"
                                value="{{ number_format($invoice->remaining_amount,2) }}" readonly>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="10" style="text-align:center;">
                            No pending invoices
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

            <div style="margin-top:14px;font-weight:600;">
                Allocated Total: ₹<span id="allocatedTotal">0.00</span>
            </div>

        </div>


        <div style="text-align:center;margin-top:22px;">
            <button type="submit" class="btn-primary">
                Save Payment
            </button>
        </div>

    </form>

</div>



<script>
document.addEventListener("DOMContentLoaded", function() {

    const totalAmountInput = document.getElementById("totalAmount");

    function calculateTotal() {
        let total = 0;

        document.querySelectorAll(".invoice-payment").forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        return total;
    }

    function updateAllocatedDisplay() {
        document.getElementById("allocatedTotal").innerText =
            calculateTotal().toFixed(2);
    }

    document.querySelectorAll(".invoice-payment").forEach(input => {

        input.addEventListener("input", function() {

            const row = this.closest("tr");

            const max = parseFloat(this.dataset.remaining) || 0;
            let val = parseFloat(this.value) || 0;

            if (val > max) {
                alert("Amount exceeds invoice balance");
                val = max;
                this.value = max;
            }

            const afterInput = row.querySelector(".invoice-after-remaining");

            const afterRemaining = max - val;

            afterInput.value = afterRemaining.toFixed(2);

            updateAllocatedDisplay();
        });

    });

    updateAllocatedDisplay();

});
</script>

@endsection