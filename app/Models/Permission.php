<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;
use Laratrust\Traits\LaratrustPermissionTrait;

class Permission extends LaratrustPermission
{
    use LaratrustPermissionTrait;
    public $guarded = [];
    public $fillable = ['name', 'display_name', 'description', 'action', 'resource', 'range', 'camp_id', 'batch_id'];
    public string $resourceNameInMandarin = '權限';
    public string $resourceDescriptionInMandarin = '針對權限清單的項目擁有新增/查詢/修改的功能。可以進行職務與權限之間的連結，依據營隊的需求設定每個職務可以行使的權限內容，提供新增/查詢/修改/刪除的功能。';
    public array $ranges = [
        "na" => 0,
        "all" => 0,
        "volunteer_large_group" => 1,
        "learner_group" => 2,
        "person" => 3,
    ];
    protected $appends = ['range_parsed'];

    public function getRangeParsedAttribute($value): bool|int|string
    {
        if (isset($this->ranges[$this->range])) {
            return $this->attributes['range_parsed'] = $this->ranges[$this->range];
        }
        return false;
    }
}
