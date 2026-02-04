<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mvcamp extends Model
{
    //利用self_intro快速篩出義工窗口
    //第一年舉辦；暫時的solution
    public const DESCRIPTION_UNIFIED_CONTACT = '第5組義工窗口';

    protected $table = 'mvcamp';

    public $resourceNameInMandarin = '醫事人員營義工特殊欄位';

    protected $fillable = [
        'applicant_id', 'lrclass', 'self_intro'
    ];
    
    public function applicant()
    {
        //若使用預設 applicant_id & id，可不寫
        return $this->belongsTo(Applicant::class,'applicant_id','id');
    }

    protected function batch(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->applicant?->batch,
        );
    }
}
