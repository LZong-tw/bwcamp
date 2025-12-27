<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Batch extends Model
{
    protected $table = 'batchs';

    public $resourceNameInMandarin = '梯次';

    public $resourceDescriptionInMandarin = '營隊中的梯次。';

    protected $fillable = ['camp_id', 'name', 'admission_suffix', 'batch_start', 'batch_end', 
    'is_appliable', 'is_late_registration_end', 'late_registration_end', 'locationName', 'location', 
    'check_in_day', 'tel', 'num_groups', 'contact_card'];

    protected $casts = [
        'batch_start' => 'date',
        'batch_end' => 'date',
        'late_registration_end' => 'date',
        'check_in_day' => 'date',
    ];

    /*
        put attribute in $appends，這樣當把 Model 轉成 JSON 時，這些欄位才會出現
    */
    protected $appends = [
        'batch_start_weekday',      //default chinese
        'batch_start_weekday_eng',  //english
        'batch_start_weekday_short',    //english short
        'batch_end_weekday',    //default chinese
        'batch_end_weekday_eng',    //english
        'batch_end_weekday_short',  //english short
    ];

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(ApplicantsGroup::class);
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function sign_info($date = null)
    {
        $relation = $this->hasMany(BatchSignInAvailibility::class, 'batch_id')->orderBy('start', 'asc');
        if ($date) {
            $relation = $relation->where('start', 'like', "%{$date}%")->get();
        }
        return $relation;
    }

    public function canSignNow()
    {
        return $this->hasOne(BatchSignInAvailibility::class, 'batch_id')
                ->where([['start', '<=', now()], ['end', '>=', now()]])->first();
    }

    public function dynamic_stats(): MorphMany
    {
        return $this->morphMany(DynamicStat::class, 'urltable');
    }

    public function vbatch(): HasOneThrough
    {
        //foreign key of BatchVbatchXref (batch_id)
        //foreign key of Vbatch (id)
        //local key of Batch (id)
        //local key of BatchVbatchXref (vbatch_id)

        //batch's vbatch
        return $this->hasOneThrough(Vbatch::class, BatchVbatchXref::class, 'batch_id', 'id', 'id', 'vbatch_id');
    }

    public function is_vbatch(): bool
    {
        //the batch is vbatch if it belongs to a vcamp
        return ($this->camp->is_vcamp());
    }

    /*
     * 取得 batch_start 日期的星期幾
     */
    protected function batchStartWeekday(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->batch_start?->locale('zh_TW')->dayName, // 星期一
        );
    }

    protected function batchStartWeekdayEng(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->batch_start?->format('l'), // Monday
        );
    }

    protected function batchStartWeekdayShort(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->batch_start?->format('D'), // Mon
        );
    }

    /*
     * 取得 batch_end 日期的星期幾
     */
    protected function batchEndWeekday(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->batch_end?->locale('zh_TW')->dayName, // 星期一
        );
    }

    protected function batchEndWeekdayEng(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->batch_end?->format('l'), // Monday
        );
    }

    protected function batchEndWeekdayShort(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->batch_end?->format('D'), // Mon
        );
    }
}
