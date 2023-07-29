<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    protected $table = 'coupon';

    public $resourceNameInMandarin = '優惠碼/劵特殊欄位';

    protected $fillable = [
        'applicant_id',
    ];

    protected $guarded = [];
}
