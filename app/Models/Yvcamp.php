<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Yvcamp extends Model
{
    //
    protected $table = 'yvcamp';

    public $resourceNameInMandarin = '大專營義工特殊欄位';

    protected $fillable = [
        'applicant_id'
    ];

    protected $guarded = [];
}
