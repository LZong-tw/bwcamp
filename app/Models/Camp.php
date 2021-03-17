<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Camp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullName', 'abbreviation', 'site_url', 'icon', 'table', 'registration_start', 'registration_end', 'admission_announcing_date', 'admission_confirming_end', 'final_registration_end', 'payment_startdate', 'payment_deadline', 'fee', 'has_early_bird', 'early_bird_fee', 'early_bird_last_day'
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'early_bird_last_day' => 'date'
    ];

    public function batchs(){
        return $this->hasMany('App\Models\Batch');
    }

    public function getSetFeeAttribute(){
        if($this->has_early_bird && Carbon::now()->lte($this->early_bird_last_day)){
            return $this->early_bird_fee;
        }        
        else{
            return $this->fee;
        }
    }

    public function getSetPaymentDeadlineAttribute(){
        if($this->has_early_bird && Carbon::now()->lte($this->early_bird_last_day)){
            if($this->table == 'tcamp' || $this->table == 'hcamp'){
                return $this->early_bird_last_day->subYears(1911)->format('ymd');
            }
        }  
        else{
            return $this->payment_deadline; 
        }
    }

    public static function getCampTable($batch_id)
    {
        return Camp::select('table as tableName')->join('batchs', 'batchs.camp_id', '=', 'camps.id')->where('batchs.id', $batch_id)->first()->tableName;
    }
}
