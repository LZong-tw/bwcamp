<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

class Camp extends Model
{
    protected $table = 'camps';
    public $resourceNameInMandarin = '學員營隊資料';
    public $resourceDescriptionInMandarin = '每年學員營隊有關的資料，包括營隊名稱、簡稱、舉辦年、報名日期、錄取日期……等資料。';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullName', 'test', 'abbreviation', 'site_url', 'icon', 'table', 'year', 'variant', 'mode',
        'registration_start', 'registration_end', 'admission_announcing_date', 'admission_confirming_end',
        'rejection_showing_date', 'certificate_available_date', 'needed_to_reply_attend', 'final_registration_end',
        'payment_startdate', 'payment_deadline', 'fee', 'has_early_bird', 'early_bird_fee', 'early_bird_last_day',
        'discount_fee', 'discount_last_day', 'modifying_deadline', 'cancellation_deadline',
        'access_start', 'access_end'
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //在blade中仍要手動指定格式，$batch->batch_start->format('Y-m-d')
        'email_verified_at' => 'datetime',
        'registration_start' => 'date:Y-m-d',
        'registration_end' => 'date:Y-m-d',
        'final_registration_end' => 'date:Y-m-d',
        'admission_announcing_date' => 'date:Y-m-d',
        'final_registration_end' => 'date:Y-m-d',
        'rejection_showing_date' => 'date:Y-m-d',
        'certificate_available_date' => 'date:Y-m-d',
        'admission_confirming_end' => 'date:Y-m-d',
        'modifying_deadline' => 'date:Y-m-d',
        'cancellation_deadline' => 'date:Y-m-d',
        'payment_startdate' => 'date:Y-m-d',
        'payment_deadline' => 'date:Y-m-d',
        'early_bird_last_day' => 'date:Y-m-d',
        'discount_last_day' => 'date:Y-m-d',
    ];

    /*
        put attribute in $appends，這樣當把 Model 轉成 JSON 時，這些欄位才會出現
    */
    protected $appends = [
        //先轉幾個會用到的，其它之後再加？
        'registration_start_weekday',
        'registration_start_weekday_eng',
        'registration_start_weekday_short',
        'registration_end_weekday',
        'registration_end_weekday_eng',
        'registration_end_weekday_short',
        'admission_announcing_date_weekday_eng',
        'admission_announcing_date_weekday',
        'admission_announcing_date_weekday_short',
        'admission_confirming_end_weekday',
        'admission_confirming_end_weekday_eng',
        'admission_confirming_end_weekday_short',
    ];


    public function batchs()
    {
        return $this->hasMany('App\Models\Batch');
    }

    public function currencies()
    {
        /*
        return $this->hasMany(CurrencyCampXref::class)
            ->join('currencies', 'currencies.id', '=', 'currency_camp_xref.currency_id')
            ->get();
        */
        return $this->belongsToMany(Currency::class, 'currency_camp_xref', 'camp_id', 'currency_id')
            ->withPivot('is_std', 'is_fix_xrate', 'xrate_to_std');
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'region_camp_xref', 'camp_id', 'region_id');
    }

    public function applicants()
    {
        return $this->hasManyThrough(Applicant::class, Batch::class);
    }

    public function organizations()
    {
        return $this->hasMany(CampOrg::class);
    }

    public function org_root()
    {
        return $this->hasMany(CampOrg::class)->firstWhere('prev_id', 0);
    }

    public function org_layer1() //第一層組織:大組
    {
        $prev_id = $this->org_root()?->id;
        return $this->hasMany(CampOrg::class)->where('prev_id', $prev_id);
    }

    public function org_layerx($prev_id) //第N層組織:小組
    {
        return $this->hasMany(CampOrg::class)->where('prev_id', $prev_id);
    }

    public function roles()
    {
        return $this->hasMany(CampOrg::class)->where('position', 'not like', 'root');
    }

    public function layer1_sections() //第一層組織:大組
    {
        return $this->hasMany(CampOrg::class)->where('section', '=', 'root');
    }

    public function layer2_sections() //第二層組織:小組
    {
        $layer1_ids = $this->layer1_sections->pluck('id');
        return $this->hasMany(CampOrg::class)->whereIn('prev_id', $layer1_ids);
    }

    public function groups()
    {
        return $this->hasManyThrough(ApplicantsGroup::class, Batch::class);
    }

    public function vcamp()
    {
        return $this->hasOneThrough(Vcamp::class, CampVcampXref::class, 'camp_id', 'id', 'id', 'vcamp_id');
    }

    public function is_vcamp(): bool
    {
        return (str_contains($this->attributes['table'], 'vcamp') ? true : false);
    }

    public function allSignAvailabilities()
    {
        return $this->hasManyThrough(BatchSignInAvailibility::class, Batch::class);
    }

    // 決定當下的費用是原價或早鳥價
    public function getSetFeeAttribute()
    {
        if ($this->early_bird_last_day) {
            $early_bird_last_day = Carbon::createFromFormat('Y-m-d', $this->early_bird_last_day);
            if ($this->has_early_bird && Carbon::today()->lte($early_bird_last_day)) {
                return $this->early_bird_fee;
            } else {
                return $this->fee;
            }
        }
        // 或根本沒早鳥
        return $this->fee;
    }

    // 決定當下的繳費期限是最終繳費期限或早鳥繳費期限
    public function getSetPaymentDeadlineAttribute()
    {
        if ($this->has_early_bird) {
            $early_bird_last_day = Carbon::createFromFormat('Y-m-d', $this->early_bird_last_day);
            if (Carbon::today()->lte($early_bird_last_day) &&
                ($this->attributes['table'] == 'tcamp' || $this->attributes['table'] == 'hcamp')) {
                return $early_bird_last_day->subYears(1911)->format('ymd');
            } else {
                return $this->payment_deadline;
            }
        } else {
            return $this->payment_deadline;
        }
    }

    public static function getCampTable($batch_id)
    {
        return Camp::select('table as tableName')->join('batchs', 'batchs.camp_id', '=', 'camps.id')->where('batchs.id', $batch_id)->first()->tableName;
    }

    public function dynamic_stats(): MorphMany
    {
        return $this->morphMany(DynamicStat::class, 'urltable');
    }

    /*
     * 取得 registration_start 日期的星期幾
     */
    protected function registrationStartWeekday(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->registration_start?->locale('zh_TW')->dayName, // 星期一
        );
    }

    protected function registrationStartWeekdayEng(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->registration_start?->format('l'), // Monday
        );
    }

    protected function registrationStartWeekdayShort(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->registration_start?->format('D'), // Mon
        );
    }

    /*
     * 取得 registration_end 日期的星期幾
     */
    protected function registrationEndWeekday(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->registration_end?->locale('zh_TW')->dayName, // 星期一
        );
    }

    protected function registrationEndWeekdayEng(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->registration_end?->format('l'), // Monday
        );
    }

    protected function registrationEndWeekdayShort(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->registration_end?->format('D'), // Mon
        );
    }

    /*
     * 取得 admission_announcing_date 日期的星期幾
     */
    protected function admissionAnnouncingDateWeekday(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->admission_announcing_date?->locale('zh_TW')->dayName, // 星期一
        );
    }

    protected function admissionAnnouncingDateWeekdayEng(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->admission_announcing_date?->format('l'), // Monday
        );
    }

    protected function admissionAnnouncingDateWeekdayShort(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->admission_announcing_date?->format('D'), // Mon
        );
    }

    /*
     * 取得 admission_confirming_end 日期的星期幾
     */
    protected function admissionConfirmingEndWeekday(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->admission_confirming_end?->locale('zh_TW')->dayName, // 星期一
        );
    }

    protected function admissionConfirmingEndWeekdayEng(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->admission_confirming_end?->format('l'), // Monday
        );
    }

    protected function admissionConfirmingEndWeekdayShort(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->admission_confirming_end?->format('D'), // Mon
        );
    }
}
