<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icamp extends Model
{
    //
    protected $table = 'icamp';

    public $resourceNameInMandarin = '國際事務處特殊欄位';

    protected $fillable = [
        'applicant_id', 'lrclass', 'passport_expiry_year', 'passport_expiry_month', 'passport_expiry_day', 'participation_mode', 'participation_dates', 'transportation_depart', 'transportation_back', 'transportation_back_location', 'acommodation_needs', 'dietary_needs', 'other_needs', 'questions'
    ];

    protected $guarded = [];
}
