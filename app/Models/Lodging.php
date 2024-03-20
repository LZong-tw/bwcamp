<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lodging extends Model
{
    //
    protected $table = 'lodging';
    public $resourceNameInMandarin = '住宿資料';
    public $resourceDescriptionInMandarin = '住宿資料，含房型、天數、費用、繳費金額等';

    protected $fillable = ['applicant_id', 'room_type', 'nights', 'fare', 'deposit', 'cash', 'sum'];
    
    protected $casts = [
        'id' => 'integer',
        'applicant_id' => 'integer',
        'room_type' => 'integer',
        'nights' => 'integer',
        'fare' => 'integer',
        'deposit' => 'integer',
        'cash' => 'integer',
        'sum' => 'integer',
    ];

}
