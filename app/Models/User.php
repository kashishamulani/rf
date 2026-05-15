<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ROLE RELATION
     */
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    /**
     * CHECK ROLE
     */
    public function hasRole($role)
    {
        return optional($this->role)->role_name === $role;
    }

    /**
     * CHECK PERMISSION
     */
    public function hasPermission($permission)
    {
        // ADMIN HAS ALL ACCESS
        if ($this->role && $this->role->role_name === 'Admin') {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role
            ->permissions
            ->contains('permission_name', $permission);
    }
}