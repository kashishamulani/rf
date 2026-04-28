<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssignmentStatus; 
use App\Models\AssignmentForm; 
use App\Models\ActivityAssignment; 

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'email',
        'mobile',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];


    public function teamMember()
{
    return $this->belongsTo(TeamMember::class, 'team_member_id');
}
public function assignmentLinks(){
    return $this->hasMany(ActivityAssignmentMember::class);
}

public function assignments(){
    return $this->hasManyThrough(
        ActivityAssignment::class,
        ActivityAssignmentMember::class,
        'team_member_id',          
        'id',                     
        'id',                      
        'activity_assignment_id' 
    );
}

public function activityAssignments()
{
    return $this->hasMany(ActivityAssignment::class);
}
}
