<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilizationReference extends Model
{
    protected $fillable = [
        'mobilization_id',

        'reference_person',
        'reference_mobile',
        'reference_email',
        'reference_detail',
        'reference_designation',
        'reference_organization',
    ];

    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class);
    }
}