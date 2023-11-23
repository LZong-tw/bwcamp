<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ivcamp extends Model
{
    //
    protected $table = 'ivcamp';

    public $resourceNameInMandarin = '國際事務處營隊義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'self_intro'
    ];

    protected $guarded = [];
}
