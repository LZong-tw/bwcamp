<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lodging extends Model
{
    //
    protected $table = 'lodging';
    public $resourceNameInMandarin = '住宿資料';
    public $resourceDescriptionInMandarin = '住宿資料，含房型、天數、費用、繳費金額等';

    protected $fillable = ['applicant_id', 'room_type', 'nights',
        'fare', 'fare_currency_id', 'fare_xrate_to_std','fare_std',
        'deposit', 'deposit_currency_id', 'deposit_xrate_to_std', 'deposit_std',
        'cash','cash_currency_id', 'cash_xrate_to_std', 'cash_std', 'sum'
    ];

    protected $casts = [
        'id' => 'integer',
        'applicant_id' => 'integer',
        'room_type' => 'string',
        'nights' => 'integer',
        'fare' => 'float',
        'fare_currency_id' => 'integer',
        'fare_xrate_to_std' => 'float',
        'fare_std' => 'float',
        'deposit' => 'float',
        'deposit_currency_id' => 'integer',
        'deposit_xrate_to_std' => 'float',
        'deposit_std' => 'float',
        'cash' => 'float',
        'cash_currency_id' => 'integer',
        'cash_xrate_to_std' => 'float',
        'cash_std' => 'float',
        'sum' => 'integer'      //to beremoved
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'id');
    }

    public function fareCurrency()
    {
        return $this->belongsTo(Currency::class, 'fare_currency_id', 'id');
    }

    public function depositCurrency()
    {
        return $this->belongsTo(Currency::class, 'deposit_currency_id', 'id');
    }
}
