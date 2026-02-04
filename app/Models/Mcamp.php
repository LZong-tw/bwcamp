<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Mcamp extends Model
{
    protected $table = 'mcamp';

    public $resourceNameInMandarin = '醫事人員營特殊欄位';

    protected $fillable = [
        'applicant_id', 'unit', 'title', 'status', 'medical_specialty', 'work_category',
    ];

    public function applicant()
    {
        //若使用預設 applicant_id & id，可不寫
        return $this->belongsTo(Applicant::class,'applicant_id','id');
    }

    protected function batch(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->applicant?->batch,
        );
    }
}
