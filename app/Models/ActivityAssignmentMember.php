<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityAssignmentMember extends Model
{
    protected $fillable=[
        'activity_assignment_id',
        'team_member_id',
        'status'
    ];

    public function activityAssignment(){
        return $this->belongsTo(ActivityAssignment::class);
    }

    public function teamMember(){
        return $this->belongsTo(TeamMember::class);
    }
}