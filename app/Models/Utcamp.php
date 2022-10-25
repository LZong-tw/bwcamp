<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utcamp extends Model
{
    //
    protected $table = 'utcamp';
    
    protected $fillable = [
        'applicant_id', 'title', 'position', 'unit', 'unit_county', 
        'department', 'info_source', 'info_source_other', 
        'is_blisswisdom', 'blisswisdom_type'
    ];

    protected $guarded = [];
}
