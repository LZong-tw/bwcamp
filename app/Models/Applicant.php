<?php

namespace App\Models;

//use Carbon\Carbon;
use Illuminate\Support\Carbon;  //Carbon\Carbon 的加強版子類別
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Enums\Gender;
use App\Enums\AttendanceStatus;
use App\Services\PhoneFormatter;

class Applicant extends Model
{
    use SoftDeletes;

    //
    protected $fillable = [
        'batch_id', 'name', 'english_name', 'region', 'region_id', 'avatar','gender',
        'group_id', 'number_id', 'is_admitted', 'admitted_at', 'is_paid', 'is_attend',
        'birthyear', 'birthmonth', 'birthday', 'birthdate',
        'age_range', 'nationality', 'idno',
        'is_foreigner', 'is_allow_notified', 'mobile', 'phone_home', 'phone_work',
        'fax', 'line', 'wechat', 'email', 'zipcode', 'address',
        'emergency_name', 'emergency_relationship', 'emergency_mobile', 'emergency_phone_home', 'emergency_phone_work', 'emergency_fax',
        'introducer_name', 'introducer_relationship', 'introducer_phone', 'introducer_email', 'introducer_participated',
        'portrait_agree', 'profile_agree', 'expectation','fee', 'tax_id_no', 'created_at'
    ];

    //gender
    //顯示中文： {{ $applicant->gender->label() }}
    //程式判斷： if ($applicant->gender === Gender::Male) (不需要再寫死字串 'M')
    //自動轉換： 當你儲存資料時 $applicant->gender = Gender::Female，Laravel 會自動幫你存入 F。

    protected $casts = [
        'gender' => Gender::class,
        'is_attend' => AttendanceStatus::class,
    ];

    protected $appends = [
        //先轉幾個會用到的，其它之後再加
        'age',
        'birthdate_display',
        'birthdate_valid',
        'camp_table',
        'mobile_display',
        'phone_home_display',
        'phone_work_display',
        'emergency_mobile_display',
        'emergency_phone_home_display',
        'emergency_phone_work_display',
        'introducer_phone_display',
        'mobile_dial',
        'phone_home_dial',
        'phone_work_dial',
        'emergency_mobile_dial',
        'emergency_phone_home_dial',
        'emergency_phone_work_dial',
        'introducer_phone_dial',
        'portrait_agree_display',
        'profile_agree_display',
        ];

    //先記錄一部分，主要想辨識 mobile/tel/email
    /*public $fieldTypes = [
        'mobile' => 'tel',
        'phone_home' => 'tel',
        'phone_work' => 'tel',
        'emergency_mobile'=> 'tel',
        'emergency_phone_home' => 'tel',
        'emergency_phone_work' => 'tel',
        'introducer_phone' => 'tel',
        'email' => 'email',
        'introducer_email' => 'email',
        'line' => 'social',
        'wechat' => 'social',
    ];*/

    public $resourceNameInMandarin = '一般學員資料';

    public $resourceDescriptionInMandarin = '學員報名表或詳細資料頁面中的資料。';

    protected $guarded = [];

    private static $campCache;

    public function user()
    {
        if ($this->applicant_id ?? false) {
            return $this->hasOneThrough(User::class, UserApplicantXref::class, 'applicant_id', 'id', 'applicant_id', 'user_id');
        } else {
            return $this->hasOneThrough(User::class, UserApplicantXref::class, 'applicant_id', 'id', 'id', 'user_id');
        }
    }

    //    public function roles()
    //    {
    //        return $this->user()->first()?->belongsToMany(CampOrg::class, 'org_user', 'user_id', 'org_id')->where('camp_id', $this->camp->id) ?? collect([]);
    //    }

    public function batch()
    {
        //預設會使用 batch_id & id, 所以不需寫
        return $this->belongsTo(Batch::class);
    }

    public function camp()
    {
        return $this->hasOneThrough(Camp::class, Batch::class, 'id', 'id', 'batch_id', 'camp_id');
    }

    public function vcamp()
    {
        return $this->hasOneThrough(Vcamp::class, Batch::class, 'id', 'id', 'batch_id', 'camp_id');
    }

    /*重複
    public function getBatch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }*/

    public function checkInData()
    {
        return $this->hasMany(CheckIn::class);
    }

    public function traffic()
    {
        return $this->hasOne(Traffic::class, 'applicant_id', 'id');
    }

    public function lodging()
    {
        return $this->hasOne(Lodging::class, 'applicant_id', 'id');
    }

    /*
     * to replace all xcamp()
     * in the future,
     * camp_entry: individual applicant's preferences of the camp
     * camp_info: the camp's (global) settings
     */

    /*public function __call($method, $parameters)
    {
        // 定義所有可能的營隊關鍵字
        $camps = ['acamp', 'avcamp', 'actcamp', 'actvcamp',
        'ceocamp', 'ceovcamp', 'ecamp', 'evcamp', 'hcamp', 'hvcamp',
        'icamp', 'ivcamp', 'lrcamp', 'lrvcamp', 'mcamp', 'mvcamp',
        'nycamp', 'nyvcamp', 'scamp', 'svcamp', 'tcamp', 'tvcamp',
        'utcamp', 'utvcamp', 'wcamp', 'wvcamp', 'ycamp', 'yvcamp'];

        if (in_array($method, $camps)) {
            $model = "App\\Models\\" . ucfirst($method);

            // 檢查類別是否存在，避免 Fatal Error
            if (!class_exists($model)) {
                // 回傳一個空的關聯或是拋出異常
                throw new \Exception("Model {$model} dose not exist.");
            }
            return $this->hasOne($model, 'applicant_id', 'id');
        }

        return parent::__call($method, $parameters);
    }*/

    public function acamp()
    {
        return $this->hasOne(Acamp::class, 'applicant_id', 'id');
    }
    public function avcamp()
    {
        return $this->hasOne(Avcamp::class, 'applicant_id', 'id');
    }
    public function actcamp()
    {
        return $this->hasOne(Actcamp::class, 'applicant_id', 'id');
    }
    public function actvcamp()
    {
        return $this->hasOne(Actvamp::class, 'applicant_id', 'id');
    }
    public function ceocamp()
    {
        return $this->hasOne(Ceocamp::class, 'applicant_id', 'id');
    }
    public function ceovcamp()
    {
        return $this->hasOne(Ceovcamp::class, 'applicant_id', 'id');
    }
    public function ecamp()
    {
        return $this->hasOne(Ecamp::class, 'applicant_id', 'id');
    }
    public function evcamp()
    {
        return $this->hasOne(Evcamp::class, 'applicant_id', 'id');
    }
    public function hcamp()
    {
        return $this->hasOne(Hcamp::class, 'applicant_id', 'id');
    }
    public function icamp()
    {
        return $this->hasOne(Icamp::class, 'applicant_id', 'id');
    }
    public function ivcamp()
    {
        return $this->hasOne(Ivcamp::class, 'applicant_id', 'id');
    }
    public function lrcamp()
    {
        return $this->hasOne(Lrcamp::class, 'applicant_id', 'id');
    }
    public function lrvcamp()
    {
        return $this->hasOne(Lrvcamp::class, 'applicant_id', 'id');
    }
    public function mcamp()
    {
        return $this->hasOne(Mcamp::class, 'applicant_id', 'id');
    }
    public function mvcamp()
    {
        return $this->hasOne(Mvcamp::class, 'applicant_id', 'id');
    }
    public function nycamp()
    {
        return $this->hasOne(Nycamp::class, 'applicant_id', 'id');
    }
    public function nyvcamp()
    {
        return $this->hasOne(Nyvcamp::class, 'applicant_id', 'id');
    }
    public function scamp()
    {
        return $this->hasOne(Scamp::class, 'applicant_id', 'id');
    }
    public function svcamp()
    {
        return $this->hasOne(Scamp::class, 'applicant_id', 'id');
    }
    public function tcamp()
    {
        return $this->hasOne(Tcamp::class, 'applicant_id', 'id');
    }
    public function tvcamp()
    {
        return $this->hasOne(Tvcamp::class, 'applicant_id', 'id');
    }
    public function utcamp()
    {
        return $this->hasOne(Utcamp::class, 'applicant_id', 'id');
    }
    public function utvcamp()
    {
        return $this->hasOne(Utvcamp::class, 'applicant_id', 'id');
    }
    public function wcamp()
    {
        return $this->hasOne(Ycamp::class, 'applicant_id', 'id');
    }
    public function wvcamp()
    {
        return $this->hasOne(Yvcamp::class, 'applicant_id', 'id');
    }
    public function ycamp()
    {
        return $this->hasOne(Ycamp::class, 'applicant_id', 'id');
    }
    public function yvcamp()
    {
        return $this->hasOne(Yvcamp::class, 'applicant_id', 'id');
    }

    public function signData($orderBy = "desc")
    {
        return $this->hasMany(SignInSignOut::class)->orderBy('id', $orderBy);
    }

    public function sign_in_info()
    {
        return $this->hasMany(SignInSignOut::class)->whereType('in');
    }
    public function sign_out_info()
    {
        return $this->hasMany(SignInSignOut::class)->whereType('out');
    }

    public function contactlog()
    {
        return $this->contactlogs();
    }
    public function contactlogs()
    {
        return $this->hasMany(ContactLog::class);
    }

    public function hasSignedThisTime($datetime)
    {
        return $this->signData()->whereHas('referencedAvailability', function ($q) use ($datetime) {
            $q->where([['start', '<=', $datetime], ['end', '>=', $datetime]]);
        })->first();
    }

    public function hasAlreadySigned($availability_id)
    {
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

    public function dynamic_stats(): MorphMany
    {
        return $this->morphMany(DynamicStat::class, 'urltable');
    }

    protected function gender(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                // 如果傳入的是 '男'，轉成 Enum 實例，存入時會自動取其 value ('M')
                // 如果傳入的已經是 'M'，則嘗試從 value 轉換
                if ($enum = Gender::fromLabel($value)) {
                    return $enum;
                }

                // 備援方案：如果直接傳 'M' 或 'F'，嘗試用內建的 tryFrom
                return Gender::tryFrom($value) ?? Gender::NotToSpecify;
            },
            get: fn ($value) => Gender::tryFrom($value)->label()
        );
    }

    protected function isAttend(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                // 如果傳入的是 '男'，轉成 Enum 實例，存入時會自動取其 value ('M')
                // 如果傳入的已經是 'M'，則嘗試從 value 轉換
                if ($enum = AttendanceStatus::fromLabel($value)) {
                    return $enum;
                }

                // 備援方案：如果直接傳 'M' 或 'F'，嘗試用內建的 tryFrom
                return AttendanceStatus::tryFrom($value) ?? AttendanceStatus::NotYetCalled;
            },
            get: fn ($value) => AttendanceStatus::tryFrom($value)->label()
        );
    }

    public function contactlogHTML($isShowVolunteers = false, $applicant, $camp = null)
    {
        if (!self::$campCache) {
            self::$campCache = $applicant->camp;
        }
        $firstNote = $applicant->contactlog?->sortByDesc('id')->first()?->notes;
        $str = \Str::limit($firstNote ?? "-", 50, '...') ?? "-";
        $str .= "<div>";
        $str .= '<a href="' . route("showAttendeeInfoGET", self::$campCache->id) . '?snORadmittedSN=' . $applicant->id . '&openExternalBrowser=1#new" target="_blank" class="text-primary">⊕新增關懷記錄</a>';
        if (count($applicant->contactlog)) {
            $str .= "&nbsp;&nbsp;";
            $str .= '<a href="' . route("showContactLogs", [self::$campCache->id, $applicant->id]) . '" target="_blank">🔍看更多</a>';
        }
        $str .= "</div>";
        return $str;
    }

    public function contactlogHTMLoptimized($isShowVolunteers = false, $camp = null)
    {
        if (!self::$campCache) {
            self::$campCache = $this->camp;
        }
        $firstNote = $this->contactlog?->sortByDesc('id')->first()?->notes;
        $str = \Str::limit($firstNote ?? "-", 50, '...') ?? "-";
        $str .= "<div>";
        $str .= '<a href="' . route("showAttendeeInfoGET", self::$campCache->id) . '?snORadmittedSN=' . $this->id . '&openExternalBrowser=1#new" target="_blank" class="text-primary">⊕新增關懷記錄</a>';
        if (count($this->contactlog)) {
            $str .= "&nbsp;&nbsp;";
            $str .= '<a href="' . route("showContactLogs", [self::$campCache->id, $this->id]) . '" target="_blank">🔍看更多</a>';
        }
        $str .= "</div>";
        return $str;
    }

    /*public function getBirthdateAttribute()
    {
        return match ($this->birthyear && $this->birthmonth && $this->birthday) {
            true => Carbon::parse("{$this->birthyear}-{$this->birthmonth}-{$this->birthday}")->format('Y-m-d'),
            false => match ($this->birthyear && $this->birthmonth) {
                true => Carbon::parse("{$this->birthyear}-{$this->birthmonth}")->format('Y-m'),
                false => match ($this->birthyear && 1) {
                    // 單獨使用年為參數，要注意 1959 以前（包含 1959）的年份，也可被視為時間，因而造成誤判
                    // https://github.com/php/php-src/issues/15945
                    // true => Carbon::parse(mktime(0, year: "{$this->birthyear}",))->format('Y'),

                    // 如果只有一個year參數(1)補齊month,day(2)再create完整日期('Y-m-d')，而非'Y'，不然有可能出現奇怪數字。
                    true => Carbon::parse(mktime(0, year: "{$this->birthyear}", month: "7", day: "1"))->format('Y-m-d'),
                    false => null,
                },
            },
        };
    }*/

    /* 換個方式處理birthdate, 分成顯示用display及計算用valid */
    /**
     * 建立一個虛擬的 birthdate 屬性
     */
    protected function birthdate(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (empty($value)) {
                    return [
                        'birthyear' => null,
                        'birthmonth' => null,
                        'birthday' => null
                    ];
                }
                $date = \Illuminate\Support\Carbon::parse($value);

                return [
                    'birthyear'  => $date->year,
                    'birthmonth' => $date->month,
                    'birthday'   => $date->day,
                ];
            },
            get: fn () => sprintf(
                '%04d-%02d-%02d',
                $this->birthyear ?: 0,
                $this->birthmonth ?: 0,
                $this->birthday ?: 0
            )
        );
    }

    /**
     * 1. 顯示專用：會出現 1990-00-00 (Readable Date)
     * 用法：$applicant->birthdate_display
     */
    protected function birthdateDisplay(): Attribute
    {
        return Attribute::get(function () {
            // 感覺沒有year, 還是可以顯示
            //if (!$this->birthyear) return '0000-00-00';

            return sprintf(
                '%04d-%02d-%02d',
                $this->birthyear ?: 0,
                $this->birthmonth ?: 0,
                $this->birthday ?: 0
            );
        });
    }

    /**
     * 2. 計算專用：自動補齊成合法日期 (Valid Date)
     * 用法：$applicant->birthdate_valid
     */
    protected function birthdateValid(): Attribute
    {
        return Attribute::get(function () {
            // 計算時好像都會用到year，如果沒有year，還是return null好了
            if (!$this->birthyear) {
                return null;
            }

            // 沒月補1月，沒日補1日，確保 Carbon 可以解析
            return Carbon::create(
                $this->birthyear,
                $this->birthmonth ?: 1,
                $this->birthday ?: 1
            );
        });
    }

    /*下面重寫
    public function getAgeAttribute()
    {
        if (is_string($this->birthdate)) {
            return Carbon::parse($this->birthdate)->diff(now())->format('%y');
        }
        return $this->birthdate?->diff(now())->format('%y');
    }*/

    /**
     * 自動根據出生年月日計算目前的年齡
     * 用法：$applicant->age
     */
    protected function age(): Attribute
    {
        return Attribute::get(function () {
            // 呼叫剛才寫好的 birthdate_valid (已自動補齊 1月1日)
            $date = $this->birthdate_valid;

            if (!$date) {
                return null; // 連年份都沒有，就無法算年齡
            }

            // 使用 Carbon 內建的 diffInYears 方法計算到今天為止的差距
            // Carbon::diffInYears() 會自動處理今天是否過了生日的問題
            return $date->diffInYears(now());
        });
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

    /**
     * 取得當前營隊關聯
     */
    protected function campTable(): Attribute
    {
        return Attribute::get(function () {
            // 透過 hasOneThrough 抓到的 camp
            // 加上 ?-> 避免 batch 或 camp 不存在時報錯
            return $this->camp?->table;
        });
    }

    /**
     * 重用格式化邏輯的 Accessors
     */
    protected function mobileDisplay(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::format($this->mobile));
    }
    protected function phoneHomeDisplay(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::format($this->phone_home));
    }
    protected function phoneWorkDisplay(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::format($this->phone_work));
    }
    protected function emergencyMobileDisplay(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::format($this->emergency_mobile));
    }
    protected function emergencyPhoneHomeDisplay(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::format($this->emergency_phone_home));
    }
    protected function emergencyPhoneWorkDisplay(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::format($this->emergency_phone_work));
    }
    protected function introducerPhoneDisplay(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::format($this->introducer_phone));
    }
    protected function mobileDial(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::dial($this->mobile));
    }
    protected function phoneHomeDial(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::dial($this->phone_home));
    }
    protected function phoneWorkDial(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::dial($this->phone_work));
    }
    protected function emergencyMobileDial(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::dial($this->emergency_mobile));
    }
    protected function emergencyPhoneHomeDial(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::dial($this->emergency_phone_home));
    }
    protected function emergencyPhoneWorkDial(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::dial($this->emergency_phone_work));
    }
    protected function introducerPhoneDial(): Attribute
    {
        return Attribute::get(fn () => PhoneFormatter::dial($this->introducer_phone));
    }

    /*boolean*/
    protected function portraitAgreeDisplay(): Attribute
    {
        return Attribute::get(fn () => $this->portrait_agree ? '同意' : '不同意');
    }
    protected function profileAgreeDisplay(): Attribute
    {
        return Attribute::get(fn () => $this->profile_agree ? '同意' : '不同意');
    }

}
