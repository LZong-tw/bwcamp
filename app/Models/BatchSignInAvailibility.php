<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchSignInAvailibility extends Model
{
    use SoftDeletes;

    //
    protected $table = "batch_sign_availibilities";

    protected $fillable = ["batch_id", "start", "end"];

    public function batch() {
        return $this->belongsTo(Batch::class);
    }
    
    public function camp() {
        return $this->batch()->camp();
    }
}
