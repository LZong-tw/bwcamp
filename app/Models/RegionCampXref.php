<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionCampXref extends Model
{
    protected $table = 'region_camp_xref';
    protected $fillable = ['region_id', 'camp_id'];
    protected $casts = [
        'id' => 'integer',
        'region_id' => 'integer',
        'camp_id' => 'integer',
    ];
}
