<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acamp extends Model
{
    //
    protected $table = 'acamp';

    public $resourceNameInMandarin = '卓青營特殊欄位';

    protected $fillable = [
        'applicant_id', 'participation_mode', 'unit', 'unit_county', 'unit_subarea', 'industry', 'title', 'education', 'job_property', 'is_manager', 'is_cadre', 'is_technical_staff', 'is_student', 'class_location', 'class_county', 'class_subarea', 'county', 'subarea', 'way', 'belief', 'motivation', 'motivation_other', 'blisswisdom_type', 'blisswisdom_type_other', 'transportation', 'is_inperson', 'agent_name', 'agent_phone', 'agent_relationship'
    ];

    protected $guarded = [];
}
