<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nycamp extends Model
{
    //
    protected $table = 'nycamp';

    public $resourceNameInMandarin = '國際青年營特殊欄位';

    protected $fillable = [
        'applicant_id', 'chinese_first_name', 'chinese_last_name', 'english_last_name', 'language',
        'addr_city', 'addr_state', 'addr_country', 'is_student', 'school', 'department', 'grade', 'unit', 'title',
        'dietary_needs', 'other_needs', 'accommodation_needs', 'companion_name', 'companion_as_roommate', 'motivation', 'info_source'
    ];

    protected $guarded = [];
}
