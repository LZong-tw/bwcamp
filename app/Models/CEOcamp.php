<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CEOcamp extends Model
{
    //
    protected $table = 'ceocamp';
    
    protected $fillable = [
        'applicant_id', 'unit', 'title', 'job_property', 'job_property_other',
        'employees', 'direct_managed_employees', 'capital', 'industry', 'industry_other',
        'org_type', 'org_type_other', 'years_operation', 'contact_time', 'marital_status',
        'exceptional_conditions', 'participation_mode', 'reasons_online', 'reasons_recommend', 'substitute_name',
        'substitute_phone', 'substitute_email'
    ];

    protected $guarded = [];
}
