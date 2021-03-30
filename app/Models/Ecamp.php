<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecamp extends Model
{
    //
    protected $table = 'ecamp';
    
    protected $fillable = [
        'applicant_id', 'belief', 'education', 'unit', 'unit_location', 'title', 'level', 'job_property', 'experience', 'employees', 'direct_managed_employees', 'industry'
    ];

    protected $guarded = [];
}
