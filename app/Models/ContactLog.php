<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLog extends Model
{
    //
    protected $table = 'contact_log';

    public $resourceNameInMandarin = '關懷記錄';

    protected $fillable = [
        'applicant_id', 'takenby_id', 'notes'
    ];

    protected $guarded = [];
}
