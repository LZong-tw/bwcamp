<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vcamp extends Camp
{
    protected $table = 'camps';

    public $resourceNameInMandarin = '義工營隊資料';
    public function mainCamp()
    {
        return $this->hasOneThrough(Camp::class, CampVcampXref::class, 'vcamp_id', 'id', 'id', 'camp_id');
    }
}
