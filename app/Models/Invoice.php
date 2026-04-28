<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

 class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'batch_id',
          'batch_value',  
        'batch_quantity',
        'value',
        'gst',
        'status',
        'payment_detail',
    ];

    protected $appends = [
        'total_amount',
        'paid_amount',
        'remaining_amount'
    ];

    // ✅ Invoice belongs to batch
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    // ✅ Many payments per invoice
    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'invoice_payment')
                    ->withPivot('amount', 'payment_type')
                    ->withTimestamps();
    }
public function assignmentItems()
{
    return $this->hasMany(\App\Models\InvoiceAssignmentItem::class, 'invoice_id');
}
public function poItems()
{
    return $this->hasMany(\App\Models\InvoicePoItem::class, 'invoice_id');
}
    // total invoice amount
public function getTotalAmountAttribute()
{
    $subtotal = DB::table('invoice_po_items')
        ->join('po_items', 'po_items.id', '=', 'invoice_po_items.po_item_id')
        ->where('invoice_po_items.invoice_id', $this->id)
        ->sum(DB::raw('invoice_po_items.qty * po_items.value'));

    if (!$subtotal) {
        return 0;
    }

    // add 18% GST
    return $subtotal + ($subtotal * 0.18);
}
    // total paid
   public function getPaidAmountAttribute()
{
    return $this->payments()
        ->sum('invoice_payment.amount');
}

    // remaining amount
   public function getRemainingAmountAttribute()
{
    $totalPaid = $this->payments()->sum('invoice_payment.amount');

    return $this->total_amount - $totalPaid;
}

 public function getFormatBusinessAttribute()
{
    $assignment = $this->batch->assignments()->with('format')->first();
    
    if ($assignment && $assignment->format) {
        return $assignment->format->type ?? $assignment->business ?? 'N/A';
    }
    
    return $assignment->business ?? 'N/A';
}
}
