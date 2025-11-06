<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $table = 'currencies';
    public $resourceNameInMandarin = '幣別';
    public $resourceDescriptionInMandarin = '幣別資訊，用來處跨國營隊的金流';

    protected $fillable = ['code', 'symbol', 'name'];
    
    protected $casts = [
        'id' => 'integer',
        'code' => 'string',     //-- ISO 4217 貨幣代碼，例如 'TWD', 'USD'
        'symbol' => 'string',   //-- 貨幣符號，例如 'NT$', '$'
        'name' => 'string',     //-- 貨幣名稱，例如 '新台幣', 'US Dollar'
    ];

}