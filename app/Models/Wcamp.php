<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wcamp extends Model
{
    //
    protected $table = 'wcamp';

    public $resourceNameInMandarin = '講師培訓營特殊欄位';

    protected $fillable = [
        'applicant_id', 'lrclass', 'unit', 'title', 'learning_experiences', 'volunteer_experiences', 
        'speak_experiences', 'character', 'potential', 'comments'
    ];

    protected $guarded = [];
}

