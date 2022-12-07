<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utcamp extends Model
{
    //
    protected $table = 'utcamp';

    public $resourceNameInMandarin = '大專教師營特殊欄位';
    protected $fillable = [
        'applicant_id', 'title', 'position', 'unit', 'unit_county',
        'department', 'workshop_credit_type', 'info_source', 'info_source_other',
        'is_blisswisdom', 'blisswisdom_type'
    ];

    protected $guarded = [];
}
