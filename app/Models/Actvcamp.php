<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actvcamp extends Model
{
    //
    protected $table = 'actvcamp';

    public $resourceNameInMandarin = '小活動義工特殊欄位';

    protected $fillable = [
        'applicant_id','transportation','self_intro'
    ];

    protected $guarded = [];
}
