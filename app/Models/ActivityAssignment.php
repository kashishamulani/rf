<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityAssignmentStatus;

class ActivityAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'phase_id',
        'activity_id',
        'team_member_id',
        'start_date',
        'days',
        'target_date',
        'type',
          'status',
    'remark'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'target_date' => 'date', 
         'assigned_at' => 'datetime',
    'target_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }
public function members()
{
    return $this->belongsToMany(
        TeamMember::class,
        'activity_assignment_members',
        'activity_assignment_id',
        'team_member_id'   // ✅ correct column name
    );
}
    // ✅ STATUS RELATION (REQUIRED)
    public function status()
    {
        return $this->hasOne(ActivityAssignmentStatus::class, 'activity_assignment_id');
    }
}