<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acamp extends Model
{
    //
    protected $table = 'acamp';
    
    protected $fillable = [
        'applicant_id', 'unit', 'unit_county', 'unit_subarea', 'industry', 'title', 'education', 'job_property', 'is_manager', 'is_cadre', 'is_technical_staff', 'class_location', 'way', 'belief', 'motivation', 'motivation_other', 'blisswisdom_type', 'blisswisdom_type_other', 'transportation', 'is_inperson', 'agent_name', 'agent_phone', 'agent_relationship'
    ];

    protected $guarded = [];
}
