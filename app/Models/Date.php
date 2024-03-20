<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    //
    protected $table = 'dates';
    public $resourceNameInMandarin = '日期';
    public $resourceDescriptionInMandarin = '日期資料';

    protected $fillable = ['applicant_id', 'datetype_id', 'date_time'];
    protected $casts = [
        'id' => 'integer',
        'applicant_id' => 'integer',
        'datetype_id' => 'integer',
        'date_time' => 'date',
    ];
}
