<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nyvcamp extends Model
{
    //
    protected $table = 'nyvcamp';

    public $resourceNameInMandarin = '國際青年營義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'self_intro'
    ];

    protected $guarded = [];
}
