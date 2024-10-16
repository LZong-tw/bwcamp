<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLogTag extends Model
{
    //
    protected $table = 'contact_log_tag';

    public $resourceNameInMandarin = '關懷記錄標籤';

    public $resourceDescriptionInMandarin = '針對「關懷記錄標籤」提供新增/查詢/修改/刪除的功能。';

    protected $fillable = [
        'tag', 'notes'
    ];

    protected $casts = [
        'tag' => 'varchar',
        'notes' => 'varchar'
    ];

    public function logs(): HasManyThrough
    {
        return $this->hasManyThrough(
            //App\Models\ContactLogTag, //id (local)
            'App\Models\ContactLog',    //id (foreign)
            'App\Models\ContactLogTagXref', //id, log_id (local), tag_id (foregin)
            'tag_id',
            'id', 
            'id',
            'log_id'
        );
    }

}
