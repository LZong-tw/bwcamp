<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgUser extends Model
{
    protected $table = 'org_user';

    protected $fillable = [
        'org_id', 'user_id', 'user_type'
    ];

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

    public function camp()
    {
        return $this->hasOneThrough(Camp::class, CampOrg::class, 'camp_id', 'id', 'user_id', 'camp_id');
    }
}
