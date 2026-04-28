<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FormResponse extends Model
{
    protected $fillable = ['form_id', 'mobilization_id', 'uuid', 'submitted_via_token'];

    protected $casts = [
        'data' => 'array',
    ];

    public function values()
    {
        return $this->hasMany(FormResponseValue::class, 'response_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class, 'mobilization_id');
    }
}