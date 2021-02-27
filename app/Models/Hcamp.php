<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hcamp extends Model
{
    //
    protected $table = 'hcamp';
    
    protected $fillable = [
        'applicant_id', 'education', 'special_condition', 'traffic', 'branch_or_classroom_belongs_to', 'class_type', 'parent_lamrim_class', 'is_recommended_by_reading_class'
    ];

    protected $guarded = [];
}
