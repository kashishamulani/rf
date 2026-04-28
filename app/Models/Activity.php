<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityAssignment; 

class Activity extends Model
{
    protected $fillable = [
        'name',
        'phase_id',
        'description', // ✅ added phase_id
    ];

    // ✅ Activity belongs to a Phase
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    // ✅ Activities assigned to team members
    public function assignments()
    {
        return $this->hasMany(ActivityAssignment::class);
    }

}
