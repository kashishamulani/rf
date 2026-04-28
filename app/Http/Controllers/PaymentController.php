<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private function accounts()
{
    return [
        'SBI - 32873660852',
        'BOM - 60532000768',
        'TDS',
    ];
}

public function index(Request $request)
{
    $query = Payment::with('invoices');

    // 🔹 Date Filter
    if ($request->from_date) {
        $query->whereDate('payment_date', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('payment_date', '<=', $request->to_date);
    }

    // 🔹 Account Filter
    if ($request->account) {
        $query->where('payment_account', $request->account);
    }

    // 🔹 Payment Type Filter (from pivot table)
    if ($request->type) {
        $query->whereHas('invoices', function ($q) use ($request) {
            $q->where('invoice_payment.payment_type', $request->type);
        });
    }

    $payments = $query->orderBy('payment_date', 'desc')->get(); // 👈 keeps filters on pagination

    // 🔹 Get distinct accounts for dropdown
    $accounts = Payment::select('payment_account')
                ->distinct()
                ->pluck('payment_account');

    return view('payments.index', compact('payments', 'accounts'));
}
public function create()
{
    $accounts = $this->accounts();

    $invoices = Invoice::with(['batch','payments'])
    ->orderBy('invoice_date')
    ->get()
    ->map(function ($invoice) {
        // Use the appended attributes from Invoice model
        $invoice->batch_value = $invoice->total_amount;
        $invoice->paid_amount = $invoice->paid_amount;
        $invoice->remaining_amount = $invoice->remaining_amount;

        return $invoice;
    })
    ->filter(function ($invoice) {
        return $invoice->remaining_amount > 0;
    });

    return view('payments.create', compact('accounts', 'invoices'));
}


public function store(Request $request)
{
    $request->validate([
        'payment_advisory_number' => 'required|unique:payments,payment_advisory_number',
        'amount' => 'required|numeric|min:0.01',
        'payment_date' => 'required|date',
        'payment_account' => 'required',
    ]);

    DB::transaction(function () use ($request) {

        $mainAmount = (float) $request->amount;
        $allocatedTotal = 0;

        if ($request->filled('payments')) {
            foreach ($request->payments as $invoiceId => $paidAmount) {
                $allocatedTotal += (float) $paidAmount;
            }
        }

        // 🚨 Allocation must not exceed total payment
        if ($allocatedTotal > $mainAmount) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' => 'Allocated invoice payments exceed total payment amount.'
            ]);
        }

        // ✅ Create payment (Removed bill/gst/tds)
        $payment = Payment::create([
            'payment_advisory_number' => $request->payment_advisory_number,
            'amount' => $mainAmount,
            'payment_date' => $request->payment_date,
            'payment_account' => $request->payment_account,
            'description' => $request->description,
        ]);

        if ($request->filled('payments')) {

            foreach ($request->payments as $invoiceId => $paidAmount) {

                $paidAmount = (float) $paidAmount;

                if ($paidAmount > 0) {

                    $invoice = Invoice::findOrFail($invoiceId);

                    $paymentType = $request->payment_type[$invoiceId] ?? null;

                    // 🚨 Ensure type selected
                    if (!$paymentType) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'payments' => "Please select payment type for invoice {$invoice->invoice_number}"
                        ]);
                    }

                    // 🚨 Prevent overpaying invoice
                    if (round($paidAmount, 2) > round($invoice->total_amount - $invoice->paid_amount, 2)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'payments' => "Payment exceeds remaining amount for invoice {$invoice->invoice_number}"
                        ]);
                    }

                    // ✅ Attach with payment_type in pivot
                    $payment->invoices()->attach($invoiceId, [
                        'amount' => $paidAmount,
                        'payment_type' => $paymentType,
                    ]);

                    // 🔁 Recalculate invoice status
                    $totalPaid = $invoice->payments()
                        ->sum('invoice_payment.amount');

                    if ($totalPaid >= $invoice->batch_value) {
                        $invoice->status = 'paid';
                    } elseif ($totalPaid > 0) {
                        $invoice->status = 'partial_paid';
                    } else {
                        $invoice->status = 'pending';
                    }

                    $invoice->save();
                }
            }
        }
    });

    return redirect()
        ->route('payments.index')
        ->with('success', 'Payment added successfully!');
}
public function edit($id)
{
    $payment = Payment::with('invoices')->findOrFail($id);

    // get account list
    $accounts = $this->accounts();

    $invoices = Invoice::with(['batch','payments'])
        ->orderBy('invoice_date','desc')
        ->get()
        ->map(function ($invoice) {
            // Use the appended attributes from Invoice model
            $invoice->batch_value = $invoice->total_amount;
            $invoice->paid_amount = $invoice->paid_amount;
            $invoice->remaining_amount = $invoice->remaining_amount;

            return $invoice;
        });

    // existing allocations
    $existingPayments = $payment->invoices()
        ->pluck('invoice_payment.amount', 'invoice_id')
        ->toArray();

    $existingPaymentTypes = $payment->invoices()
        ->pluck('invoice_payment.payment_type', 'invoice_id')
        ->toArray();

    return view('payments.edit', compact(
        'payment',
        'invoices',
        'accounts',
        'existingPayments',
        'existingPaymentTypes'
    ));
}
public function update(Request $request, Payment $payment)
{
    $request->validate([
        'payment_advisory_number' =>
            'required|unique:payments,payment_advisory_number,' . $payment->id,
        'amount' => 'required|numeric|min:0.01',
        'payment_date' => 'required|date',
        'payment_account' => 'required',
    ]);

    try {

        DB::transaction(function () use ($request, $payment) {

            $mainAmount = (float) $request->amount;
            $allocatedTotal = 0;

            if ($request->filled('payments')) {
                foreach ($request->payments as $invoiceId => $paidAmount) {
                    $allocatedTotal += (float) $paidAmount;
                }
            }

            if ($allocatedTotal > $mainAmount) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'amount' => 'Allocated invoice payments exceed total payment amount.'
                ]);
            }

            // Update payment main details
            $payment->update([
                'payment_advisory_number' => $request->payment_advisory_number,
                'amount' => $mainAmount,
                'payment_date' => $request->payment_date,
                'payment_account' => $request->payment_account,
                'description' => $request->description,
            ]);

            // 🔹 Get previous allocations
            $oldAllocations = $payment->invoices()
                ->pluck('invoice_payment.amount', 'invoice_id')
                ->toArray();

            // 🔹 Detach old records
            $payment->invoices()->detach();

            if ($request->filled('payments')) {

                foreach ($request->payments as $invoiceId => $paidAmount) {

                    $paidAmount = (float) $paidAmount;

                    if ($paidAmount <= 0) {
                        continue;
                    }

                    $invoice = Invoice::findOrFail($invoiceId);

                    // 🔹 Total paid from OTHER payments
                    $currentPaid = $invoice->payments()
                        ->where('payments.id', '!=', $payment->id)
                        ->sum('invoice_payment.amount');

                    $totalAmount = $invoice->total_amount;

                    $actualRemaining = $totalAmount - $currentPaid;

                    if ($paidAmount > $actualRemaining) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'payments' => "Payment exceeds remaining amount for invoice {$invoice->invoice_number}"
                        ]);
                    }

                    $paymentType = $request->payment_type[$invoiceId] ?? null;

                    if (!$paymentType) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'payments' => "Please select payment type for invoice {$invoice->invoice_number}"
                        ]);
                    }

                    // 🔹 Attach invoice payment
                    $payment->invoices()->attach($invoiceId, [
                        'amount' => $paidAmount,
                        'payment_type' => $paymentType,
                    ]);

                    // 🔁 Refresh invoice payments
                    $invoice->load('payments');

                    $totalPaid = $invoice->paid_amount;

                    // 🔹 Update invoice status
                    if ($totalPaid >= $invoice->total_amount) {
                        $invoice->status = 'paid';
                    } elseif ($totalPaid > 0) {
                        $invoice->status = 'partial_paid';
                    } else {
                        $invoice->status = 'pending';
                    }

                    $invoice->save();
                }
            }

        });

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment updated successfully!');

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

public function show($id)
{
    $payment = Payment::with('invoices')->findOrFail($id);
    return view('payments.show', compact('payment'));
}

public function destroy(Payment $payment)
{
    DB::transaction(function () use ($payment) {

        $invoices = $payment->invoices;

        // remove pivot
        $payment->invoices()->detach();

        // delete payment
        $payment->delete();

        foreach ($invoices as $invoice) {

            $totalPaid = $invoice->paid_amount;

            $totalAmount = $invoice->total_amount;

            if ($totalPaid >= $totalAmount) {
                $invoice->status = 'paid';
            } elseif ($totalPaid > 0) {
                $invoice->status = 'partial_paid';
            } else {
                $invoice->status = 'pending';
            }

            $invoice->save();
        }
    });

    return redirect()
        ->route('payments.index')
        ->with('success', 'Payment deleted successfully!');
}
}