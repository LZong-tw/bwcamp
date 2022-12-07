<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;

class Role extends LaratrustRole
{
    use LaratrustRoleTrait;

    protected $table = 'roles';

    public $guarded = [];

    public $resourceNameInMandarin = '權限角色';

    public function role_users() {
        return $this->hasMany(RoleUser::class);
    }

    public function camp() {
        return $this->belongsTo(Camp::class);
    }
}
