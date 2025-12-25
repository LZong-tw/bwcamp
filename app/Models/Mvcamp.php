<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mvcamp extends Model
{
    protected $table = 'mvcamp';

    public $resourceNameInMandarin = '醫事人員營義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'lrclass', 'self_intro'
    ];

    protected $guarded = [];
}
