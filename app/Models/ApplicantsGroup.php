<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantsGroup extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'batch_id',
        'alias',
    ];

    public $resourceNameInMandarin = '學員組別';

    public $resourceDescriptionInMandarin = '營隊中的學員組別，附屬在梯次下。';

    public function applicants() {
        return $this->hasMany(Applicant::class, 'group_id', 'id');
    }

    public function batch() {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function numbers() {
        return $this->hasMany(GroupNumber::class, 'group_id', 'id');
    }
}
