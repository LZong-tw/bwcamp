<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Camp extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'table', 'registration_start', 'registration_end', 'admission_announcing_date', 'admission_confirming_end', 'camp_start', 'camp_end',
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
        return Camp::join('batchs', 'batchs.camp_id', '=', 'camps.id')->where('camps.id', $camp_id)->where('batchs.id', $batch_id)->get();
    }
}
