<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vcamp extends Camp
{
    protected $table = 'camps';

    public $resourceNameInMandarin = '義工營隊資料';

    public $resourceDescriptionInMandarin = '將義工視為一個新的營隊，可以設定義工營隊所有的基本資料(內容與學員營隊一樣)，提供營隊義工報名使用。';

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

    public function mainCamp()
    {
        return $this->hasOneThrough(Camp::class, CampVcampXref::class, 'vcamp_id', 'id', 'id', 'camp_id');
    }
    public function batchs() {
        return $this->hasMany('App\Models\Batch','camp_id');
    }
}
