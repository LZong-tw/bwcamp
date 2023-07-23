<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lrvcamp extends Model
{
    //
    protected $table = 'lrvcamp';

    public $resourceNameInMandarin = '研討班護持特殊欄位';

    protected $fillable = [
        'applicant_id', 'self_intro'
    ];

    protected $guarded = [];
}
