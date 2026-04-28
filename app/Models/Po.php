<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Po extends Model
{
    use HasFactory;

    protected $table = 'pos';
    protected $fillable = [
        'po_no',
        'po_date',
        'period_from',
        'period_to',
        'gst',
    ];

    public function items()
{
    return $this->hasMany(PoItem::class);
}
public function batchItems()
{
    return $this->hasMany(BatchPoItem::class);
}
public function batches()
{
    return $this->hasMany(Batch::class);
}
}

