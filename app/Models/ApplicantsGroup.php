<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantsGroup extends Model
{
    //
    protected $fillable = [
        'batch_id',
        'alias',
    ];

    public $resourceNameInMandarin = '學員組別';

    public function applicants() {
        return $this->hasMany(Applicant::class, 'group_id', 'id');
    }
}
