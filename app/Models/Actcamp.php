<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actcamp extends Model
{
    //
    protected $table = 'actcamp';

    public $resourceNameInMandarin = '小活動特殊欄位';

    protected $fillable = [
        'applicant_id','category','transportation'
    ];

    protected $guarded = [];
}
