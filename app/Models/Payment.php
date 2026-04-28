<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_advisory_number',
           'bill_amount',
    'gst',
    'tds',
        'amount',
        'payment_date',
        'description',
        'payment_account',
    ];
public function invoices()
{
    return $this->belongsToMany(Invoice::class, 'invoice_payment')
                ->withPivot('amount','payment_type')
                ->withTimestamps();
}


}
