<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoItem extends Model
{
    use HasFactory;

   protected $fillable = [
    'po_id',
    'item',
    'value',
    'quantity',
    'used_quantity',
];


    // Relation to PO
    public function po()
    {
        return $this->belongsTo(Po::class);
    }
}
