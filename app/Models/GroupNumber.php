<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupNumber extends Model
{
    //
    protected $fillable = [
        'group_id',
        'applicant_id',
        'number',
    ];

    public function group()
    {
        return $this->belongsTo(ApplicantsGroup::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
