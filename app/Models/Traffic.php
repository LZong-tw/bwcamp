<?php

namespace App\Models;

use App\Models\Applicant as ModelsApplicant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Traffic extends Model {

    //
    protected $fillable = [
        'applicant_id', 'depart_from', 'back_to', 'fare', 'deposit'
    ];

    public $resourceNameInMandarin = '交通資料';
    public $resourceDescriptionInMandarin = '交通資料含地點、費用、繳費金額等';


    protected $guarded = [];

    public function applicant() {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'id');
    }
}
