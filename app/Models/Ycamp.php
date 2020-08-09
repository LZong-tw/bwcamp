<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ycamp extends Model
{
    //
    protected $table = 'ycamp';
    
    protected $fillable = [
        'applicant_id', 'school', 'school_location', 'region', 'day_night', 'system', 'department', 'grade', 'way', 'is_blisswisdom', 'blisswisdom_type', 'father_name', 'father_lamrim', 'father_phone', 'mother_name', 'mother_lamrim', 'mother_phone', 'is_inperson', 'agent_name', 'agent_phone', 'habbit', 'club', 'goal',
    ];
}
