<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evcamp extends Model
{
    //
    protected $table = 'evcamp';

    public $resourceNameInMandarin = '企業營義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'group_priority1', 'group_priority2', 'group_priority3',
        'lrclass', 'cadre_experiences', 'volunteer_experiences', 'transport', 'transport_other',
        'expertise', 'expertise_other', 'language', 'language_other',
        'unit', 'industry', 'title', 'job_property', 'employees', 'direct_managed_employees',
        'capital', 'capital_unit', 'org_type', 'years_operation'
    ];

    protected $guarded = [];
}
