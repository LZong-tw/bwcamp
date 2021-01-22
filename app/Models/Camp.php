<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullName', 'abbreviation', 'site_url', 'icon', 'table', 'registration_start', 'registration_end', 'admission_announcing_date', 'admission_confirming_end', 'final_registration_end', 'payment_startdate', 'payment_deadline', 'fee'
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
    ];

    public function batchs(){
        return $this->hasMany('App\Models\Batch');
    }

    public static function getCampWithBatch($batch_id)
    {
        return Camp::join('batchs', 'batchs.camp_id', '=', 'camps.id')->where('batchs.id', $batch_id)->first();
    }

    public static function getCampTable($batch_id)
    {
        return Camp::select('table as tableName')->join('batchs', 'batchs.camp_id', '=', 'camps.id')->where('batchs.id', $batch_id)->first()->tableName;
    }
}
