<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    //
    protected $fillable = [
        'batch_id', 'name', 'gender', 'birthyear', 'birthmonth', 'birthday', 'nationality', 'idno', 'is_foreigner', 'mobile', 'phone_home', 'phone_work', 'fax', 'line', 'wechat', 'email', 'postal_code', 'address', 'emergency_name', 'emergency_relationship', 'emergency_mobile', 'emergency_phone_home', 'emergency_phone_work', 'emergency_fax', 'introducer_name', 'introducer_relationship', 'introducer_phone', 'introducer_participated', 'portrait_agree', 'profile_agree', 'expectation', 
    ];

    protected $guarded = ['*'];
}
