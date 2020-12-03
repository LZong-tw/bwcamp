<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    //
    protected $fillable = [
        'batch_id', 'name', 'gender', 'region', 'birthyear', 'birthmonth', 'birthday', 'nationality', 'idno', 'is_foreigner', 'is_allow_notified', 'mobile', 'phone_home', 'phone_work', 'fax', 'line', 'wechat', 'email', 'zipcode', 'address', 'emergency_name', 'emergency_relationship', 'emergency_mobile', 'emergency_phone_home', 'emergency_phone_work', 'emergency_fax', 'introducer_name', 'introducer_relationship', 'introducer_phone', 'introducer_participated', 'portrait_agree', 'profile_agree', 'expectation', 
    ];

    protected $guarded = [];

    public function batch()
    {
        return $this->belongsTo('App\Models\Batch');
    }

    public function getBatch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }
}
