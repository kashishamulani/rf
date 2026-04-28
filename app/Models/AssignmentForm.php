<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentForm extends Model
{
    use HasFactory;

    protected $table = 'assignment_forms'; // ✅ IMPORTANT (even if same)

    protected $fillable = [
        'assignment_id',
        'form_id',
        'form_name',
        'location',
        'status',
        'valid_from',
        'valid_to',
        'link',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
     public function teamMember()
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function status()
    {
        return $this->hasOne(ActivityAssignmentStatus::class, 'activity_assignment_id');
    }
}
