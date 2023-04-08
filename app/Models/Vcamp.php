<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vcamp extends Camp
{
    protected $table = 'camps';

    public $resourceNameInMandarin = '義工營隊資料';

    public $description = '將義工視為一個新的營隊，可以設定義工營隊所有的基本資料(內容與學員營隊一樣)，提供營隊義工報名使用。';
    public function mainCamp()
    {
        return $this->hasOneThrough(Camp::class, CampVcampXref::class, 'vcamp_id', 'id', 'id', 'camp_id');
    }
}
