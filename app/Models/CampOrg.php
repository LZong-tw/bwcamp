<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Models\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;

class CampOrg extends LaratrustRole
{
    use LaratrustRoleTrait;

    //
    protected $table = 'camp_org';

    public $resourceNameInMandarin = '營隊組織';

    // todo: 要再加上梯次和地區
    protected $fillable = [
        'camp_id', 'batch_id', 'section', 'position', 'description'
    ];

    protected $guarded = [];

    public function camp()
    {
        return $this->hasOne(Camp::class, 'id', 'camp_id');
    }

    public function batch()
    {
        return $this->hasOne(Batch::class, 'id', 'batch_id');
    }

    public function next() {
        return $this->belongsTo('App\Models\CampOrg', 'camp_id', 'camp_id')->where('section', '>', $this->section)->orderBy('section', 'asc');
    }
}
