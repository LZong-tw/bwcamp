<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgUser extends Model
{
    protected $table = 'org_user';

    public $resourceNameInMandarin = '指派職務';

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
