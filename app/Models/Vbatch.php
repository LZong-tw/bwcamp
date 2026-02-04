<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Vbatch extends Model
{
    //
    protected $table = 'batchs';

    public $resourceNameInMandarin = '義工梯次資料';

    public $resourceDescriptionInMandarin = '在義工營隊裡的皆是義工梯次，它和主營隊的梯次理論上是一對一';
    
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

    public function mainBatch()
    {
        return $this->hasOneThrough(Batch::class, BatchVbatchXref::class, 'vbatch_id', 'id', 'id', 'batch_id');
    }
    public function vcamp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    public function batch(): HasOneThrough
    {
        //vbatch's batch
        return $this->hasOneThrough(Batch::class, BatchVbatchXref::class, 'vbatch_id', 'id', 'id', 'batch_id');
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
