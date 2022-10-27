<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $table = 'roles';

    public $guarded = [];

    public function role_users() {
        return $this->hasMany(RoleUser::class);
    }

    public function camp() {
        return $this->belongsTo(Camp::class);
    }
}
