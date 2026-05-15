<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';

    protected $fillable = [
        'role_name',
    ];

    /**
     * USERS
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * PERMISSIONS
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }
}