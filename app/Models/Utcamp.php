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
        'department', 'certificate_type', 'is_civil_certificate', 'is_bwfoce_certificate', 
        'invoice_type', 'invoice_title', 'taxid', 
        'info_source', 'info_source_other', 'is_blisswisdom', 'blisswisdom_type',
        'transportation_depart', 'transportation_back', 'companion_name', 'companion_as_roommate'
    ];

    protected $guarded = [];
}
