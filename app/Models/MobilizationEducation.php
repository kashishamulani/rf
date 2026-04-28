<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilizationEducation extends Model
{
    protected $fillable = [
        'mobilization_id',

        'tenth_passing_year',
        'tenth_marksheet',

        'twelfth_passing_year',
        'twelfth_marksheet',

        'graduation_passing_year',
        'graduation_marksheet',

        'post_graduation_passing_year',
        'post_graduation_marksheet',
    ];

    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class);
    }
}