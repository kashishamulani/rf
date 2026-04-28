<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $table = 'progress';

    protected $fillable = [
        'name'
    ];

    // Relation: one progress can have many assignment students
    public function assignmentStudents()
    {
        return $this->hasMany(AssignmentStudent::class, 'progress_id');
    }
}