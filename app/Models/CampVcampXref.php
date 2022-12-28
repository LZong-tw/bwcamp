<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampVcampXref extends Model
{
    protected $table = 'camp_vcamp';

    protected $fillable = [
        'camp_id', 'vcamp_id'
    ];
}
