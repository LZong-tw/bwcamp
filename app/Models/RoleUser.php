<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    public function role_data()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
