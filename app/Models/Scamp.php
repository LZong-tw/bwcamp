<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scamp extends Model
{
    //
    protected $table = 'scamp';

    public $resourceNameInMandarin = '永續課程特殊欄位';

    protected $fillable = [
        'applicant_id', 'unit', 'address_work', 'department', 'title', 'seniority', 'expectation', 'is_allow_informed', 'exam_format'
    ];

    protected $guarded = [];
}
