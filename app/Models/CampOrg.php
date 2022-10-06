<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampOrg extends Model
{
    //
    protected $table = 'camp_org';
    
    protected $fillable = [
        'group', 'position'
    ];

    protected $guarded = [];
}
