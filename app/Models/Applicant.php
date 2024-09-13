<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

//use Traffic;

class Applicant extends Model {
    use SoftDeletes;

    //
    protected $fillable = [
        'batch_id', 'name', 'english_name', 'region', 'region_id', 'avatar','gender',
        'group_id', 'number_id', 'is_admitted', 'is_paid', 'is_attend',
        'birthyear', 'birthmonth', 'birthday', 'age_range', 'nationality', 'idno',
        'is_foreigner', 'is_allow_notified', 'mobile', 'phone_home', 'phone_work',
        'fax', 'line', 'wechat', 'email', 'zipcode', 'address',
        'emergency_name', 'emergency_relationship', 'emergency_mobile', 'emergency_phone_home', 'emergency_phone_work', 'emergency_fax',
        'introducer_name', 'introducer_relationship', 'introducer_phone', 'introducer_email', 'introducer_participated',
        'portrait_agree', 'profile_agree', 'expectation','fee', 'tax_id_no'
    ];

    public $resourceNameInMandarin = '一般學員資料';

    public $resourceDescriptionInMandarin = '學員報名表或詳細資料頁面中的資料。';

    protected $guarded = [];

    private static $campCache;

    public function user()
    {
        if ($this->applicant_id ?? false) {
            return $this->hasOneThrough(User::class, UserApplicantXref::class, 'applicant_id', 'id', 'applicant_id', 'user_id');
        }
        else {
            return $this->hasOneThrough(User::class, UserApplicantXref::class, 'applicant_id', 'id', 'id', 'user_id');
        }
    }

//    public function roles()
//    {
//        return $this->user()->first()?->belongsToMany(CampOrg::class, 'org_user', 'user_id', 'org_id')->where('camp_id', $this->camp->id) ?? collect([]);
//    }

    public function batch() {
        return $this->belongsTo(Batch::class);
    }

    public function camp() {
        return $this->hasOneThrough(Camp::class, Batch::class, 'id', 'id', 'batch_id', 'camp_id');
    }

    public function vcamp() {
        return $this->hasOneThrough(Vcamp::class, Batch::class, 'id', 'id', 'batch_id', 'camp_id');
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

    public function lodging() {
        return $this->hasOne(Lodging::class, 'applicant_id', 'id');
    }

    public function acamp() {
        return $this->hasOne(Acamp::class, 'applicant_id', 'id');
    }
    public function avcamp() {
        return $this->hasOne(Avcamp::class, 'applicant_id', 'id');
    }

    public function actcamp() {
        return $this->hasOne(Actcamp::class, 'applicant_id', 'id');
    }
    public function actvcamp() {
        return $this->hasOne(Actvamp::class, 'applicant_id', 'id');
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

    public function evcamp() {
        return $this->hasOne(Evcamp::class, 'applicant_id', 'id');
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
    public function yvcamp() {
        return $this->hasOne(Yvcamp::class, 'applicant_id', 'id');
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

    public function contactlog() {
        return $this->contactlogs();
    }

    public function contactlogs() {
        return $this->hasMany(ContactLog::class);
    }

    public function hasSignedThisTime($datetime) {
        return $this->signData()->whereHas('referencedAvailability', function ($q) use ($datetime) {
            $q->where([['start', '<=', $datetime], ['end', '>=', $datetime]]);
        })->first();
    }

    public function hasAlreadySigned($availability_id) {
        return $this->signData()->whereAvailabilityId($availability_id)->first();
    }

    public function groupRelation()
    {
        return $this->belongsTo(ApplicantsGroup::class, 'group_id', 'id');
    }

    public function groupOrgRelation()
    {
        return $this->belongsTo(CampOrg::class, 'group_id', 'id');
    }

    public function numberRelation()
    {
        return $this->belongsTo(GroupNumber::class, 'number_id', 'id');
    }

    public function carers()
    {
        return $this->belongsToMany(\App\User::class, 'carer_applicant_xrefs', 'applicant_id', 'user_id');
    }

    public function carer_names()
    {
        //to concatenate the names of all carers
        //return $this->carers()->implode('name', ', ');
        return $this->carers->flatten()->pluck('name')->implode(',');
    }

    /*public function dynamic_stats()
    {
        return $this->hasMany(DynamicStat::class);
    }*/

    public function dynamic_stats(): MorphMany
    {
        return $this->morphMany(DynamicStat::class, 'urltable');
    }

    public function getBirthdateAttribute() {
        return match ($this->birthyear && $this->birthmonth && $this->birthday) {
            true => Carbon::parse("{$this->birthyear}-{$this->birthmonth}-{$this->birthday}")->format('Y-m-d'),
            false => match ($this->birthyear && $this->birthmonth) {
                true => Carbon::parse("{$this->birthyear}-{$this->birthmonth}")->format('Y-m'),
                false => match ($this->birthyear && 1) {
                    true => Carbon::parse("{$this->birthyear}")->format('Y'),
                    false => null,
                },
            },
        };
    }

    public function getAgeAttribute() {
        if (is_string($this->birthdate)) {
            return Carbon::parse($this->birthdate)->diff(now())->format('%y');
        }
        return $this->birthdate?->diff(now())->format('%y');
    }

    public function getGenderZhTwAttribute() {
        return $this->gender == 'M' ? '男' : '女';
    }

    public function contactlogHTML($isShowVolunteers = false) {
        if (!self::$campCache) {
            self::$campCache = $this->camp;
        }
        $str = \Str::limit($this->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-";
        $str .= "<div>";
        $str .= '<a href="' . route("showAttendeeInfoGET", ($isShowVolunteers ?? false) ? $this->camp->vcamp->id : $this->camp->id) . '?snORadmittedSN=' . $this->id . '&openExternalBrowser=1#new" target="_blank">⊕新增關懷記錄</a>';
        if(count($this->contactlog)) {
            $str .= "&nbsp;&nbsp;";
            $str .= '<a href="' . route("showContactLogs", [$this->camp->id, $this->id]) . '" target="_blank">🔍看更多</a>';
        }
        $str .= "</div>";
        return $str;
    }

    /**
     * Get applicant's group by app version.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function group(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->groupRelation()->first()?->alias,
            set: fn ($value) => $value,
        );
    }

    /**
     * Get applicant's number by app version.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function number(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->numberRelation()->first()?->number,
            set: fn ($value) => $value,
        );
    }
}
