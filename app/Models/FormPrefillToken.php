<?php
// app/Models/FormPrefillToken.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPrefillToken extends Model
{
    protected $fillable = [
        'token', 'mobilization_id', 'form_id', 
        'expires_at', 'used_at', 'prefill_data'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'prefill_data' => 'array'
    ];
    
    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class);
    }
    
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    
    public function isValid()
    {
        return !$this->used_at && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }
}