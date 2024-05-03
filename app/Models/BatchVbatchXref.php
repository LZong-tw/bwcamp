<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchVbatchXref extends Model
{
    protected $table = 'batch_vbatch';

    protected $fillable = [
        'batch_id', 'vbatch_id'
    ];
}
