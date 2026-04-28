<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Progress;


class AssignmentStudent extends Model
{
    protected $fillable = [
        'assignment_id',
        'mobilization_id',
        'samarth_done',
        'samarth_id',
        'samarth_certificate',
        'uan_done',
        'uan_number',
        'uan_certificate',
        'documents_done',
        'offer_letter_done',
        'registration_id',
        'registration_password',
        'registration_number',
        'ec_number',


          'offer_letter_date',
    'offer_letter_file',

    'registration_id',
    'registration_password',
    'registration_number',
    'ec_number',
    'ec_date',

    'date_of_placement',
    'placement_company',
    'placement_offering',

    'progress_id',

            // NEW PLACEMENT FIELDS
        'date_of_placement',
        'placement_company',
        'placement_offering'
    ];


    public function mobilization()
{
    return $this->belongsTo(\App\Models\Mobilization::class, 'mobilization_id');
}
public function progress()
{
    return $this->belongsTo(\App\Models\Progress::class);
}
}
