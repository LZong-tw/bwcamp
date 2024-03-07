<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Models\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;

class CampOrg extends LaratrustRole
{
    use LaratrustRoleTrait;

    //
    protected $table = 'camp_org';

    public $resourceNameInMandarin = '營隊組織 / 義工職務組別';

    public $resourceDescriptionInMandarin = '在學員營隊下新增營隊職務的操作(新增/查詢/修改/刪除)。包括功能大組及各個職稱(但不須增設關懷組的各小組長/副小組長/組員)，有這個資源權限的人通常就可以直接變動營隊的職務組織架構。';

    protected $fillable = [
        'camp_id', 'batch_id', 'region_id', 'section', 'position', 'group_id', 'all_group', 'is_node', 'prev_id', 'order', 'display_name', 'description', 'name'
    ];

    protected $guarded = [];

    public function camp()
    {
        return $this->hasOne(Camp::class, 'id', 'camp_id');
    }

    public function batch()
    {
        return $this->hasOne(Batch::class, 'id', 'batch_id');
    }

    public function region()
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }

    public function is_root()
    {
        if ($this->prev_id) {
            return false;
        } else {
            return true;
        }
    }

    public function is_leaf()
    {
        if ($this->is_node) {
            return false;
        } else {
            return true;
        }
    }

    public function parent()
    {
        if ($this->prev_id) {
            return $this->hasOne('App\Models\CampOrg', 'id', 'prev_id');
        } else {
            return null;
        }
    }

    public function children()
    {
        if ($this->is_leaf) {
            return null;
        } else {
            return $this->hasMany('App\Models\CampOrg', 'prev_id', 'id');
        }
    }

    public function path()
    {
        $section = 'root';
        if ($this->prev_id) {
            $parent = $this->parent();
            $section = $parent->position;
            $parent = $parent->parent();
            while ($parent->prev_id > 0) {
                $section = $section.$parent->position;
                $parent = $parent->parent();
            }
        }
        return $section;
    }

    public function next()
    {
        return $this->belongsTo('App\Models\CampOrg', 'camp_id', 'camp_id')->where('section', '>', $this->section)->orderBy('section', 'asc');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'org_user', 'org_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'org_permission', 'org_id', 'permission_id');
    }

    public function applicant_group()
    {
        return $this->hasOne(ApplicantsGroup::class, 'id', 'group_id');
    }
}
