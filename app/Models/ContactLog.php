<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLog extends Model
{
    //
    protected $table = 'contact_log';

    public $resourceNameInMandarin = '關懷記錄';

    public $description = '針對不同職別的義工，在使用學員及義工的關懷紀錄上，提供不指定/義工大組／小組/個人不同的權限範疇。針對關懷紀錄提供新增/查詢/修改/刪除的功能。';

    protected $fillable = [
        'applicant_id', 'takenby_id', 'notes'
    ];

    protected $guarded = [];
}
