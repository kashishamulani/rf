<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'permission_name',
    ];

    /**
     * ROLES
     */
    public function roles()
    {
        return $this->belongsToMany(
            UserRole::class,
            'role_permissions',
            'permission_id',
            'role_id'
        );
    }
}