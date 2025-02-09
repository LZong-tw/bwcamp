<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Svcamp extends Model
{
    //
    protected $table = 'svcamp';

    public $resourceNameInMandarin = '永續課程義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'lrclass', 'self_intro'
    ];

    protected $guarded = [];
}
