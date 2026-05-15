<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchPhaseReport extends Model
{
    protected $fillable = [
        'batch_id',
        'phase_id',
        'status',
        'start_date',
        'expected_end_date',
        'end_date'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}