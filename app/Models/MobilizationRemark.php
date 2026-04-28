<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilizationRemark extends Model
{
    protected $table = 'mobilization_remarks';

    protected $fillable = [
        'mobilization_id',
        'calling_date',
        'calling_time',
        'calling_mode',
        'call_action',
        'call_response',
        'next_followup_date',
        'tag',
        'notes',
        'status'
    ];

    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class);
    }
}