<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicStat extends Model
{
    protected $fillable = [
        'applicant_id', 'google_sheet_url',
    ];
}
