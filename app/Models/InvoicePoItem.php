<?php
// app/Models/InvoicePoItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'po_item_id',
        'quantity',
        'rate',
        'amount'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function poItem()
    {
        return $this->belongsTo(PoItem::class);
    }
}