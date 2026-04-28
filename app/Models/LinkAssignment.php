<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkAssignment extends Model
{
    protected $fillable = ['form_id', 'assignment_id'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}