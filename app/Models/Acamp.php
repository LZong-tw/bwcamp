<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acamp extends Model
{
    //
    protected $table = 'acamp';
    
    protected $fillable = [
        'applicant_id', 'unit', 'unit_county', 'unit_district', 'industry', 'title', 'education', 'job_property', 'is_manager', 'is_cadre', 'is_technicalstaff', 'class_location', 'way', 'belief', 'motivation', 'blisswisdom_type', 'is_inperson', 'agent_name', 'agent_phone', 'agent_relationship'
    ];

    protected $guarded = [];
}
