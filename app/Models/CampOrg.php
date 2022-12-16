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

    protected $fillable = [
        'camp_id', 'section', 'position', 'description'
    ];

    protected $guarded = [];

    public function camp()
    {
        return $this->belongsTo(Camp::class);
    }

    public function next() {
        return $this->belongsTo('App\Models\CampOrg', 'camp_id', 'camp_id')->where('section', '>', $this->section)->orderBy('section', 'asc');
    }
}
