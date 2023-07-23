<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lrcamp extends Model
{
    //
    protected $table = 'lrcamp';

    public $resourceNameInMandarin = '研討班特殊欄位';  //copy from ecamp

    protected $fillable = [
        'applicant_id', 'belief', 'education', 'unit', 'unit_location',
        'title', 'level', 'job_property', 'experience', 'employees',
        'direct_managed_employees', 'industry', 'after_camp_available_day', 'favored_event'
    ];

    protected $guarded = [];
}
