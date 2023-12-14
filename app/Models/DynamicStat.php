<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DynamicStat extends Model
{
    protected $fillable = [
        'urltable_id', 'urltable_type', 'purpose', 'google_sheet_url', 'spreadsheet_id', 'sheet_name'
    ];

    public function urltable(): MorphTo
    {
        return $this->morphTo();
    }
}
