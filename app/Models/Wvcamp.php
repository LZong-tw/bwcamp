<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wvcamp extends Model
{
    //
    protected $table = 'wvcamp';

    public $resourceNameInMandarin = '講師培訓營義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'lrcalss', 'self_intro'
    ];

    protected $guarded = [];
}
