<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityAssignmentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_assignment_id',
        'status',
        'remark',
    ];

    public function activityAssignment()
    {
        return $this->belongsTo(ActivityAssignment::class, 'activity_assignment_id');
    }
}