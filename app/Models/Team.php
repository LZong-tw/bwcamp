<?php

namespace App\Models;

use Laratrust\Models\LaratrustTeam;
use Laratrust\Traits\LaratrustTeamTrait;
class Team extends LaratrustTeam
{
    use LaratrustTeamTrait;

    public $guarded = [];

    public $resourceNameInMandarin = '職務權限組別';
}
