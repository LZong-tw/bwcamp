<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tvcamp extends Model
{
    //
    protected $table = 'tvcamp';

    public $resourceNameInMandarin = '教師營義工特殊欄位';
    
    protected $fillable = [
        'applicant_id', 'self_intro'
    ];

    protected $guarded = [];
}
