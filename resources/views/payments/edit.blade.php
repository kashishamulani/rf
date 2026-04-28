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

.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

@media(max-width:768px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
}

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
</style>

<div style="padding:12px;width:100%;display:flex;flex-direction:column;align-items:center;">

    <div style="width:100%;max-width:1100px;display:flex;justify-content:flex-end;margin-bottom:12px;">
        <a href="{{ route('payments.index') }}" class="btn-back">← Back</a>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:10px;margin-bottom:14px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:10px;margin-bottom:14px;">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:10px;margin-bottom:14px;">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('payments.update',$payment->id) }}" method="POST" class="form-card">
        @csrf
        @method('PUT')

        <h2 style="text-align:center;margin-bottom:18px;
background:linear-gradient(135deg,#6366f1,#ec4899);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;">
            Edit Payment
        </h2>

        <div class="grid-2">

            <div class="form-group">
                <label class="form-label">Payment Advisory Number *</label>
                <input type="text" name="payment_advisory_number" class="form-input"
                    value="{{ old('payment_advisory_number',$payment->payment_advisory_number) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" class="form-input"
                    value="{{ old('payment_date',$payment->payment_date) }}" required>
            </div>

        </div>


        <div class="grid-2" style="margin-top:12px;">

            <div class="form-group">
                <label class="form-label">Total Payment Amount *</label>
                <input type="number" step="0.01" name="amount" id="totalAmount" class="form-input"
                    value="{{ old('amount',$payment->amount) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Account *</label>
                <select name="payment_account" class="form-select" required>

                    <option value="">-- Select Account --</option>

                    @foreach($accounts as $account)
                    <option value="{{ $account }}"
                        {{ old('payment_account',$payment->payment_account)==$account?'selected':'' }}>
                        {{ $account }}
                    </option>
                    @endforeach

                </select>
            </div>

        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="2"
                class="form-textarea">{{ old('description',$payment->description) }}</textarea>
        </div>


        <h3 style="margin-top:30px;margin-bottom:10px;">Invoices</h3>

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

                    @foreach($invoices as $invoice)

                    @php
                    $thisPayment = $existingPayments[$invoice->id] ?? 0;
                    $remainingBeforeEdit = $invoice->remaining_amount + $thisPayment;
                    @endphp

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
                            ₹{{ number_format($invoice->total_amount ?? 0,2) }}
                        </td>

                        <td>
                            ₹{{ number_format($invoice->paid_amount,2) }}
                        </td>

                        <td>
                            ₹{{ number_format($remainingBeforeEdit,2) }}
                        </td>

                        <td>
                            @php
                            $thisPaymentType = $existingPaymentTypes[$invoice->id] ?? null;
                            @endphp

                            <select name="payment_type[{{ $invoice->id }}]" class="form-select">

                                <option value="">-- Select --</option>

                                <option value="bill"
                                    {{ old('payment_type.'.$invoice->id, $thisPaymentType) == 'bill' ? 'selected' : '' }}>
                                    Bill Amount
                                </option>

                                <option value="gst"
                                    {{ old('payment_type.'.$invoice->id, $thisPaymentType) == 'gst' ? 'selected' : '' }}>
                                    GST
                                </option>

                                <option value="tds"
                                    {{ old('payment_type.'.$invoice->id, $thisPaymentType) == 'tds' ? 'selected' : '' }}>
                                    TDS
                                </option>

                            </select>
                        </td>

                        <td>

                            <input type="number" step="0.01" name="payments[{{ $invoice->id }}]"
                                class="form-input invoice-payment" data-remaining="{{ $remainingBeforeEdit }}"
                                value="{{ old('payments.'.$invoice->id, $thisPayment) }}">

                        </td>

                        <td>

                           @php
$oldPayment = old('payments.'.$invoice->id, $thisPayment);
@endphp

<input type="text"
       class="form-input invoice-after-remaining"
       value="{{ number_format($remainingBeforeEdit - $oldPayment,2) }}"
       readonly>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <div style="margin-top:14px;font-weight:600;">
                Allocated Total: ₹<span id="allocatedTotal">0.00</span>
            </div>

        </div>

        <div style="text-align:center;margin-top:22px;">
            <button type="submit" class="btn-primary">
                Update Payment
            </button>
        </div>

    </form>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {

    const inputs = document.querySelectorAll(".invoice-payment");
    const allocatedTotal = document.getElementById("allocatedTotal");

    function calculateTotal() {
        let total = 0;
        inputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        return total;
    }

    function updateAllocated() {
        allocatedTotal.innerText = calculateTotal().toFixed(2);
    }

    function updateRow(input) {

        const row = input.closest("tr");
        const max = parseFloat(input.dataset.remaining) || 0;
        let value = parseFloat(input.value) || 0;

        if (value > max) {
            value = max;
            input.value = max;
        }

        if (value < 0) {
            value = 0;
            input.value = 0;
        }

        const afterField = row.querySelector(".invoice-after-remaining");
        const remaining = max - value;

        if (afterField) {
            afterField.value = remaining.toFixed(2);
        }
    }

    inputs.forEach(input => {

        input.addEventListener("input", function() {
            updateRow(this);
            updateAllocated();
        });

        // IMPORTANT: run once on load
        updateRow(input);
    });

    updateAllocated();

});
</script>
@endsection