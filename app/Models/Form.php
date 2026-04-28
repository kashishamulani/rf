<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title','description','slug','location',
    'valid_from',
    'valid_to',
    'instructions'];

    public function fields(){
        return $this->hasMany(FormField::class);
    }

    public function responses(){
        return $this->hasMany(FormResponse::class);
    }

}