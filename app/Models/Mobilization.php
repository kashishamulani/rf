<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FormResponse; 
use App\Models\MobilizationExperience;
use App\Models\Role;
use App\Models\SubRole;
use App\Models\MobilizationDocument;
use App\Models\MobilizationEducation;
use App\Models\MobilizationBankDetail;
use App\Models\MobilizationReference;

class Mobilization extends Model
{
    protected $fillable = [
        'identification_remark',
        'name','email','mobile','whatsapp_number',

        'highest_qualification','dob','age','gender',
        'marital_status','state','city','location',

        'relocation','languages',

        'current_salary','preferred_salary',

        'role_id','sub_role_id',

        // ✅ NEW FIELDS
        'father_name',
        'mother_name',
        'pincode',
        'category',
        'religion',
        'family_members',
        'dependents',
        'has_vehicle',
        'vehicle_details',
        'has_smartphone',
    ];

    protected $casts = [
        'languages' => 'array',
        'relocation' => 'array',
        'dob' => 'date',
        'has_vehicle' => 'boolean',
        'has_smartphone' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subRole()
    {
        return $this->belongsTo(SubRole::class);
    }

    public function experiences()
    {
        return $this->hasMany(MobilizationExperience::class);
    }

    public function assignments()
{
    return $this->belongsToMany(
        \App\Models\Assignment::class,
        'assignment_students',  // Change this from assignment_mobilization to assignment_students
        'mobilization_id',
        'assignment_id'
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

    public function remarks()
    {
        return $this->hasMany(MobilizationRemark::class);
    }

    public function latestRemark()
    {
        return $this->hasOne(MobilizationRemark::class)->latestOfMany();
    }

    public function assignmentBatches()
    {
        return $this->hasMany(\App\Models\BatchAssignmentStudent::class, 'student_id', 'id');
    }

    public function formResponses()
    {
        return $this->hasMany(FormResponse::class);
    }

    public function latestFormResponse()
    {
        return $this->hasOne(FormResponse::class, 'mobilization_id')->latestOfMany();
    }

    /*
    |--------------------------------------------------------------------------
    | ✅ NEW RELATIONS (IMPORTANT)
    |--------------------------------------------------------------------------
    */

    // 📁 Documents (1-1)
    public function documents()
    {
        return $this->hasOne(MobilizationDocument::class);
    }

    // 🎓 Education (1-1)
    public function education()
    {
        return $this->hasOne(MobilizationEducation::class);
    }

    // 🏦 Bank (1-1)
    public function bank()
    {
        return $this->hasOne(MobilizationBankDetail::class);
    }

    // 👥 References (1-MANY)
    public function references()
    {
        return $this->hasMany(MobilizationReference::class);
    }

    /*
    |--------------------------------------------------------------------------
    | PROFILE COMPLETION
    |--------------------------------------------------------------------------
    */

    public function getFilledFieldsCountAttribute()
    {
        $fields = $this->getProfileFields();
        $count = 0;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $count++;
            }
        }

        return $count;
    }

    public function getTotalFieldsCountAttribute()
    {
        return count($this->getProfileFields());
    }

    private function getProfileFields()
    {
        return [
            'name',
            'email',
            'mobile',
            'whatsapp_number',
            'highest_qualification',
            'dob',
            'age',
            'gender',
            'marital_status',
            'state',
            'city',
            'location',
            'current_salary',
            'preferred_salary',

            // ✅ NEW FIELDS
            'father_name',
            'mother_name',
            'pincode',
            'category',
            'religion',
            'family_members',
            'dependents',
            'has_vehicle',
            'has_smartphone',
        ];
    }
}