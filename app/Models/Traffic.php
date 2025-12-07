<?php

namespace App\Models;

use App\Models\Applicant as ModelsApplicant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Traffic extends Model
{
    protected $table = 'traffic';
    public $resourceNameInMandarin = '交通資料';
    public $resourceDescriptionInMandarin = '交通資料含上/下車地點、費用、繳費金額等';

    //
    protected $fillable = [
        'applicant_id', 'depart_from', 'back_to',
        'fare', 'fare_currency_id', 'fare_xrate_to_std','fare_std',
        'deposit', 'deposit_currency_id', 'deposit_xrate_to_std', 'deposit_std',
        'cash','cash_currency_id', 'cash_xrate_to_std', 'cash_std', 'sum'
    ];

    protected $casts = [
        'id' => 'integer',
        'applicant_id' => 'integer',
        'depart_from' => 'string',
        'back_to' => 'string',
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

    protected $guarded = [];

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
