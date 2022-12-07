<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;
use Laratrust\Traits\LaratrustPermissionTrait;

class Permission extends LaratrustPermission
{
    use LaratrustPermissionTrait;
    public $guarded = [];
    public $fillable = ['name', 'display_name', 'description'];
    public $resourceNameInMandarin = '權限';
}
