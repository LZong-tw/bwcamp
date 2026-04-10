<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecamp extends Model
{
    //
    protected $table = 'ecamp';

    public $resourceNameInMandarin = '企業營特殊欄位';

    protected $fillable = [
        'applicant_id', 'belief', 'education', 'unit', 'unit_location',
        'title', 'level', 'job_property', 'experience', 'employees',
        'direct_managed_employees', 'industry', 'info_source', 'info_source_other',
        'is_membership', 'after_camp_available_day', 'favored_event'
        'after_camp_available_day', 'favored_event', 'created_at'
    ];

    protected $guarded = [];

    protected $casts = [];

    protected function infoSource(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return str_replace(',', "||/", $value);
            },
            get: fn ($value) => $value
        );
    }

    protected function isMembership(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                // 統一轉為字串並去除空白，增加比對成功率
                $valueStr = (string)$value;
                if ($valueStr === '1' || $valueStr === '是' || str_contains($valueStr, '立即加入')) {
                    return 1;
                } elseif ($valueStr === '0' || $valueStr === '否' || str_contains($valueStr, '暫時不要')) {
                    return 0;
                } else {
                    // 如果都不匹配，給予一個預設值（例如 0），避免回傳 null 導致資料庫報錯
                    return 0;
                }
            },
            get: fn ($value) => $value
        );
    }
}
