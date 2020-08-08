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
        'name', 'table', 'registration_start', 'registration_end', 'admission_announcing_date', 'admission_confirming_end', 
    ];

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

    public static function getCampWithBatch($camp_id, $batch_id)
    {
        return Camp::join('batchs', 'batchs.camp_id', '=', 'camps.id')->where('camps.id', $camp_id)->where('batchs.id', $batch_id)->first();
    }
}
