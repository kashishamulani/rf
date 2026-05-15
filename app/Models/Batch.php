<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_code',
        'state',
        'district',
        'address',
        'status',
        'number_allotted',
        'training_from',
        'training_to',
        'training_hours',
        'po_id',
        'batch_size',
           'service_from',
             'service_to'
    ];

    protected $casts = [
        'training_from' => 'date',
        'training_to'   => 'date',
           'service_from'   => 'date',
             'service_to' => 'date',
    ];


public function po()
{
    return $this->belongsTo(Po::class);
}

public function assignments()
{
    return $this->belongsToMany(
        Assignment::class,
        'assignment_batch'
    )->withPivot('build')->withTimestamps();
}



public function invoice()
{
    return $this->hasOne(
        \App\Models\Invoice::class,
        'batch_id'
    );
}

    // ✅ Students mapped to batch
    public function assignmentStudents()
    {
        return $this->hasMany(BatchAssignmentStudent::class);
    }

    // ✅ Shortcut for candidates
    public function candidates()
    {
        return $this->hasMany(BatchAssignmentStudent::class, 'batch_id');
    }

    // ✅ Assignments with forms
    public function assignmentsWithForms()
    {
        return $this->assignments()->with('forms');
    }

    // ✅ Candidates grouped by assignment
    public function candidatesByAssignment()
    {
        return $this->candidates()
            ->select('batch_assignment_students.*', 'assignments.assignment_name')
            ->join('assignments', 'batch_assignment_students.assignment_id', '=', 'assignments.id')
            ->get()
            ->groupBy('assignment_id');
    }



public function batchPoItems()
{
    return $this->hasMany(BatchPoItem::class);
}


public function invoiceItems()
{
    return $this->hasManyThrough(
        PoItem::class,
        BatchPoItem::class,
        'batch_id',
        'id',
        'id',
        'po_item_id'
    );
}


public function students()
{
    return $this->hasMany(\App\Models\BatchAssignmentStudent::class, 'batch_id');
}
public function phaseReports()
{
    return $this->hasMany(BatchPhaseReport::class);
}
}