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
}
