<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicStat extends Model
{
    protected $fillable = [
        'google_sheet_url',
    ];
}
