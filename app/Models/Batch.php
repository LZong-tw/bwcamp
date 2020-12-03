<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batchs';

    public function camp()
    {
        return $this->belongsTo('App\Models\Camp');
    }
}
