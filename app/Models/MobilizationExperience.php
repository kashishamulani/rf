<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilizationExperience extends Model
{
    protected $fillable = [
        'mobilization_id','organization','designation','duration','role_id','sub_role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subRole()
    {
        return $this->belongsTo(SubRole::class);
    }

    public function mobilization()
    {
        return $this->belongsTo(Mobilization::class);
    }
}
