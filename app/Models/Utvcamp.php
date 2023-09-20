<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utvcamp extends Model
{
    //
    protected $table = 'utvcamp';

    public $resourceNameInMandarin = '大專教師營義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'self_intro'
    ];

    protected $guarded = [];
}
