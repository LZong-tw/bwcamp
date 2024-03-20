<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateType extends Model
{
    //
    protected $table = 'datetypes';
    public $resourceNameInMandarin = '日期類型';
    public $resourceDescriptionInMandarin = '日期類型如「護持日期」、「住宿日期」等';

    protected $fillable = ['camp_id', 'type_name'];
    protected $casts = [
        'id' => 'integer',
        'camp_id' => 'integer',
        'type_name' => 'string',
    ];
}
