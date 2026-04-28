<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilizationBankDetail extends Model
{
    protected $fillable = [
        'mobilization_id',
        'bank_account_number',
        'ifsc_code',
    ];

    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class);
    }
}