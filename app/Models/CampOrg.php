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
        'camp_id', 'section', 'position'
    ];

    protected $guarded = [];

    public function camp()
    {
        return $this->belongsTo(Camp::class);
    }
}
