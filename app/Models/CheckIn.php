<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    //
    protected $table = 'check_in';

    public function applicant() {
        return $this->belongsTo(Applicant::class);
    }
}
