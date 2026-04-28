<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name','country_id','lat','long'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}