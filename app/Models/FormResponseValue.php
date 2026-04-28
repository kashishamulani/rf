<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponseValue extends Model
{
    protected $fillable = [
        'response_id',
        'field_id',
        'value',
        'file_url',
        'file_type',
        'file_extension',
        'file_size',
    ];

    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }

    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'response_id');
    }
}
















