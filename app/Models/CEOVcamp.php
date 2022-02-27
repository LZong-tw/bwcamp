<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceovcamp extends Model
{
    //
    protected $table = 'ceovcamp';
    
    protected $fillable = [
        'applicant_id', 'group_priority1', 'group_priority2', 'group_priority3', 'group_priority_other',
        'lrclass', 'transport', 'transport_other', 'expertise', 'language',
        'language_other', 'unit', 'industry', 'industry_other', 'title',
        'job_property', 'job_property_other', 'employees', 'direct_managed_employees', 'capital',
        'org_type', 'org_type_other', 'years_operation'
    ];

    protected $guarded = [];
}
