<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public function role_users(){
        return $this->hasMany(RoleUser::class);
    }

    public function camp(){
        return $this->belongsTo(Camp::class);
    }
}
