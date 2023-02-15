<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarerApplicantXref extends Model
{
    //
    public $resourceNameInMandarin = '關懷員';

    protected $fillable = [
        'applicant_id',
        'user_id'
    ];


}
