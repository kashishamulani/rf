<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberActivityStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_assignment_id',
        'status',
        'remark',
        'updated_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function activityAssignment()
    {
        return $this->belongsTo(ActivityAssignment::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}