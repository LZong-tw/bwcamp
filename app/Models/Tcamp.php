<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tcamp extends Model
{
    //
    protected $table = 'tcamp';
    
    protected $fillable = [
        'applicant_id', 'is_educating', 'has_license',  'workshop_credit_type', 
        'never_attend_any_stay_over_tcamps', 'is_attend_tcamp', 'tcamp_year', 'info_source', 
        'interesting', 'interesting_complement', 'after_camp_available_day', 'years_teached', 'education', 
        'school_or_course', 'subject_teaches', 'position', 'title', 
        'unit', 'unit_county', 'unit_district', 
        'is_blisswisdom', 'blisswisdom_type', 'blisswisdom_type_complement'
    ];

    protected $guarded = [];
}
