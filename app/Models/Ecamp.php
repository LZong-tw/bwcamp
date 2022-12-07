<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecamp extends Model
{
    //
    protected $table = 'ecamp';

    public $resourceNameInMandarin = '企業營特殊欄位';

    protected $fillable = [
        'applicant_id', 'belief', 'education', 'unit', 'unit_location',
        'title', 'level', 'job_property', 'experience', 'employees',
        'direct_managed_employees', 'industry', 'after_camp_available_day', 'favored_event'
    ];

    protected $guarded = [];
}
