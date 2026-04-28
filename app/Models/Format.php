<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        
    ];
       public function assignments()
    {
        return $this->hasMany(Assignment::class, 'format_id'); 
        // if you use format_id
    }
}
