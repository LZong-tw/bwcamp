<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avcamp extends Model
{
    //
    protected $table = 'avcamp';

    public $resourceNameInMandarin = '卓青營義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'lrclass_level', 'lrclass'
    ];

    protected $guarded = [];
}
