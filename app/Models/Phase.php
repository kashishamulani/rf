<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_name',
        'phase_order',
        'sequence'
    ];

    public function activities()
{
    return $this->hasMany(Activity::class);
}
public function activityAssignments()
{
    return $this->hasMany(ActivityAssignment::class);
}
}

