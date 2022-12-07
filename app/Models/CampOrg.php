<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustTeamTrait;
//use Laratrust\Contracts\LaratrustTeamInterface;

class CampOrg extends Model //implements LaratrustTeamInterface
{
//    use LaratrustTeamTrait;

    //
    protected $table = 'camp_org';

    public $resourceNameInMandarin = '營隊組織';

    protected $fillable = [
        'camp_id', 'section', 'position'
    ];

    protected $guarded = [];
}
