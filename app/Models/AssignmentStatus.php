<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentStatus extends Model
{
    protected $fillable = [
        'assignment_id',
        'status',
        'status_date',
        'remark',
    ];
}
