<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Traffic;

class Applicant extends Model {
    use SoftDeletes;

    //
    protected $fillable = [
        'batch_id', 'name', 'gender', 'region', 'avatar', 'birthyear', 'birthmonth', 'birthday', 'nationality', 'idno', 'is_foreigner', 'is_allow_notified', 'mobile', 'phone_home', 'phone_work', 'fax', 'line', 'wechat', 'email', 'zipcode', 'address', 'emergency_name', 'emergency_relationship', 'emergency_mobile', 'emergency_phone_home', 'emergency_phone_work', 'emergency_fax', 'introducer_name', 'introducer_relationship', 'introducer_phone', 'introducer_participated', 'portrait_agree', 'profile_agree', 'expectation', 'tax_id_no', 'age_range'
    ];

    protected $guarded = [];

    public function batch() {
        return $this->belongsTo(Batch::class);
    }

    public function getBatch() {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function checkInData() {
        return $this->hasMany(CheckIn::class);
    }
    
    public function traffic() {
        return $this->hasOne(Traffic::class, 'applicant_id', 'id');
    }

    public function acamp() {
        return $this->hasOne(Acamp::class, 'applicant_id', 'id');
    }

    public function ceocamp() {
        return $this->hasOne(Ceocamp::class, 'applicant_id', 'id');
    }

    public function ceovcamp() {
        return $this->hasOne(Ceovcamp::class, 'applicant_id', 'id');
    }

    public function ecamp() {
        return $this->hasOne(Ecamp::class, 'applicant_id', 'id');
    }

    public function hcamp() {
        return $this->hasOne(Hcamp::class, 'applicant_id', 'id');
    }

    public function tcamp() {
        return $this->hasOne(Tcamp::class, 'applicant_id', 'id');
    }

    public function ycamp() {
        return $this->hasOne(Ycamp::class, 'applicant_id', 'id');
    }
    
    public function signData($orderBy = "desc") {
        return $this->hasMany(SignInSignOut::class)->orderBy('id', $orderBy);
    }

    public function sign_in_info() {
        return $this->hasMany(SignInSignOut::class)->whereType('in');
    }

    public function sign_out_info() {
        return $this->hasMany(SignInSignOut::class)->whereType('out');
    }

    public function hasSignedThisTime($datetime) {
        return $this->signData()->whereHas('referencedAvailability', function ($q) use ($datetime) {
            $q->where([['start', '<=', $datetime], ['end', '>=', $datetime]]);
        })->first();
    }

    public function hasAlreadySigned($availability_id) {
        return $this->signData()->whereAvailabilityId($availability_id)->first();
    }
}
