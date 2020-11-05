<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tcamp extends Model
{
    //
    protected $table = 'tcamp';
    
    protected $fillable = [
        'applicant_id', 'is_educating', 'has_license', 'years_teached', 'education', 'school_or_course', 'subject_teaches', 'title', 'unit', 'unit_county', 'unit_district', 'is_blisswisdom', 'blisswisdom_type', 'blisswisdom_type_complement'
    ];

    protected $guarded = [];
}
