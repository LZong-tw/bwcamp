<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyCampXref extends Model
{
    //
    protected $table = 'currency_camp_xref';
    public $resourceNameInMandarin = '營隊使用貨幣';
    public $resourceDescriptionInMandarin = '記錄每個營隊所使用的幣別';

    protected $fillable = ['camp_id', 'currency_id', 'is_std', 'is_fix_xrate', 'xrate_to_std'];

    protected $casts = [
        'id' => 'integer',
        'camp_id' => 'integer',
        'currency_id' => 'integer',
        'is_std' => 'boolean',          //是否為營隊的標準匯率
        'is_fix_xrate' => 'boolean',    //是否使用固定匯率
        'xrate_to_std' => 'decimal',    //此幣別轉換成標準幣別的匯率
    ];

}
