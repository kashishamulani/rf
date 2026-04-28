<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchPoItem extends Model
{
    use HasFactory;

    // ✅ Make sure these match your migration columns
    protected $fillable = [
        'batch_id',
        'po_item_id',
        'qty',
    ];

    /**
     * Relationship to Batch
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Relationship to the original PO Item
     */
    public function poItem()
    {
        return $this->belongsTo(PoItem::class, 'po_item_id');
    }
}