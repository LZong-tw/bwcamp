<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    /*protected $casts = [
        'is_civil_certificate' => 'boolean',
        'is_bwfoce_certificate' => 'boolean',
    ];*/

    protected $appends = [
        'is_civil_certificate_display',
        'is_bwfoce_certificate_display',
    ];


    protected $guarded = [];

    /*boolean*/
    protected function isCivilCertificateDisplay(): Attribute
    {
        return Attribute::get(fn () => $this->is_civil_certificate ? '申請' : '不申請');
    }
    protected function isBwfoceCertificateDisplay(): Attribute
    {
        return Attribute::get(fn () => $this->is_bwfoce_certificate ? '申請' : '不申請');
    }
}
