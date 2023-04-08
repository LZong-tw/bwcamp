<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgUser extends Model
{
    protected $table = 'org_user';

    protected $fillable = [
        'org_id', 'user_id', 'user_type'
    ];

    public $resourceNameInMandarin = '分配義工職務';

    public $resourceDescriptionInMandarin = '針對所有義工進行指派職務的功能，包括指派權限給其他人，提供指派/新增/查詢/修改/刪除義工指派職務的功能。';

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
        return $this->hasOneThrough(Camp::class, CampOrg::class, 'id', 'id', 'org_id', 'camp_id');
    }
}
