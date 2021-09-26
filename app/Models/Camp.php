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
        'fullName', 'test', 'abbreviation', 'site_url', 'icon', 'table', 'registration_start', 'registration_end', 'admission_announcing_date', 'admission_confirming_end', 'final_registration_end', 'payment_startdate', 'payment_deadline', 'fee', 'has_early_bird', 'early_bird_fee', 'early_bird_last_day', 'modifying_deadline', 'cancellation_deadline'
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
        'cancellation_deadline' => 'date'
    ];

    public function batchs(){
        return $this->hasMany('App\Models\Batch');
    }

    // 決定當下的費用是原價或早鳥價
    public function getSetFeeAttribute(){
        if($this->early_bird_last_day){
            $early_bird_last_day = Carbon::createFromFormat('Y-m-d', $this->early_bird_last_day);
            if($this->has_early_bird && Carbon::today()->lte($early_bird_last_day)){
                return $this->early_bird_fee;
            }        
            else{
                return $this->fee;
            }
        }
        // 或根本沒早鳥 
        return $this->fee;
    }

    // 決定當下的繳費期限是最終繳費期限或早鳥繳費期限
    public function getSetPaymentDeadlineAttribute(){
        if($this->has_early_bird){
            $early_bird_last_day = Carbon::createFromFormat('Y-m-d', $this->early_bird_last_day);
            if(Carbon::today()->lte($early_bird_last_day) && 
                ($this->attributes['table'] == 'tcamp' || $this->attributes['table'] == 'hcamp')){
                return $early_bird_last_day->subYears(1911)->format('ymd');
            }
            else{
                return $this->payment_deadline; 
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
