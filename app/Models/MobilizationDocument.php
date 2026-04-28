<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilizationDocument extends Model
{
    protected $fillable = [
        'mobilization_id',

        'photo',
        'signature',

        'aadhar_number',
        'aadhar_front',
        'aadhar_back',

        'pan_number',
        'pan_card',

        'driving_license',
        'experience_letter',

        'passbook_photo',
    ];

    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class);
    }
}