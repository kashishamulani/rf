<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssignmentStatus; 
use App\Models\AssignmentForm; 
use App\Models\ActivityAssignment; 



class Assignment extends Model
{
    use HasFactory;

     protected $table = 'assignments';

    protected $fillable = [
    'assignment_name','date','format_id','requirement',
    'state','district','location','description',
    'hr_id','deadline_date',
    'sm_name','sm_mobile','sm_email',
    'batch_type','store_code','sourcing_machine','business','region',
    'position_name','monthly_ctc','level','ft_pt',
    'minimum_education_qualification','work_experience',
    'status','status_date','remark'
];

    protected $casts = [
    'date'     => 'date',
     'deadline_date' => 'date',
];
    public function format()
{
    return $this->belongsTo(Format::class);
}

public function batches()
{
    return $this->belongsToMany(Batch::class, 'assignment_batch')
                ->withPivot('build')
                ->withTimestamps();
}
public function hr()
{
    return $this->belongsTo(Hr::class);
}



public function statusHistory()
{
    return $this->hasMany(AssignmentStatus::class)->latest();
}

public function forms()
{
    return $this->hasMany(AssignmentForm::class, 'assignment_id');
}
public function activityAssignments()
{
    return $this->hasMany(ActivityAssignment::class);
}
public function members(){
    return $this->hasMany(ActivityAssignmentMember::class);
}

public function assignment(){
    return $this->belongsTo(Assignment::class);
}

public function phase(){
    return $this->belongsTo(Phase::class);
}

public function activity(){
    return $this->belongsTo(Activity::class);
}
public function mobilizations()
{
    return $this->belongsToMany(
        \App\Models\Mobilization::class,
        'assignment_students', // pivot table name - MUST MATCH Mobilization model
        'assignment_id',
        'mobilization_id'
    )->withPivot([
        'samarth_done',
        'samarth_id',
        'samarth_certificate',
        'uan_done',
        'uan_number',
        'uan_certificate',
        'documents_done',
        'offer_letter_done',
        'registration_id',
        'registration_password',
        'registration_number',
        'ec_number',
        'date_of_placement',
        'placement_company',
        'placement_offering'
    ])->withTimestamps();
}
public function invoiceAssignmentItems()
{
    return $this->hasMany(\App\Models\InvoiceAssignmentItem::class);
}
}
