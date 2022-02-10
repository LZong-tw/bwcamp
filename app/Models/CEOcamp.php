<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecamp extends Model
{
    //
    protected $table = 'ceocamp';
    
    protected $fillable = [
        'applicant_id', 'unit', 'title', 'job_property', 'employees', 'direct_managed_employees', 'capital',
         'industry', 'organization_type', 'years_operation', 'contact_time', 'marital_status', 'exceptional_conditions',
         'participation_mode', 'reasons_online', 'reasons_recommend', 'substitute_name', 'substitute_phone', 'substitute_email'
    ];

    protected $guarded = [];
}
