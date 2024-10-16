<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLogTagXref extends Model
{
    //
    protected $table = 'contact_log_tag_xref';

    public $resourceNameInMandarin = '關懷記錄與標籤的連結';

    public $resourceDescriptionInMandarin = '就是「關懷記錄」與「關懷記錄標籤」的連結。';

    protected $fillable = [
        'log_id', 'tag_id'
    ];

    protected $casts = [
        'id' => 'bigint unsigned',
        'log_id' => 'bigint unsigned',
        'tag_id' => 'bigint unsgined',
    ];

}
