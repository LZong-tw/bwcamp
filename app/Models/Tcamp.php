<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tcamp extends Model
{
    //
    protected $table = 'tcamp';
    
    protected $fillable = [
        'applicant_id', 'school', 'school_location', 'day_night', 'system', 'department', 'grade', 'way', 'is_educating', 'has_license', 'is_blisswisdom', 'blisswisdom_type', 'father_name', 'father_lamrim', 'father_phone', 'mother_name', 'mother_lamrim', 'mother_phone', 'is_inperson', 'agent_name', 'agent_phone', 'habbit', 'club', 'goal',
    ];

    protected $guarded = [];
}
