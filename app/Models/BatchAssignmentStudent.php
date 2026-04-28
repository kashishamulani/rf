<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchAssignmentStudent extends Model
{
    protected $table = 'batch_assignment_students';
    
    protected $fillable = [
        'batch_id',
        'assignment_id',
        'student_id'
    ];

    // Relationship with batch
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    // Relationship with assignment
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}