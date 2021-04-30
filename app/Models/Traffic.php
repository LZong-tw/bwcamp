<?php

namespace App\Models;

use App\Models\Applicant as ModelsApplicant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Traffic extends Model {
    
    //
    protected $fillable = [
    ];

    protected $guarded = [];

    public function applicant() {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'id');
    }
}
