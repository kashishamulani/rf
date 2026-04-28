<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAssignmentItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_assignment_items';

    protected $fillable = [
        'invoice_id',
        'assignment_id',
        'quantity'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Invoice relationship
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // Assignment relationship
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

}