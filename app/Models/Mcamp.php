<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mcamp extends Model
{
    //
    protected $table = 'mcamp';

    public $resourceNameInMandarin = '醫事人員營特殊欄位';

    protected $fillable = [
        'applicant_id', 'unit', 'title', 'status', 'medical_specialty', 'work_category', 
    ];

    protected $guarded = [];
}

