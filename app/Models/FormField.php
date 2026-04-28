<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FormField extends Model
{
    protected $fillable = ['form_id','label','type','options','is_required'];

    protected $casts = [
        'options' => 'array'
    ];
}