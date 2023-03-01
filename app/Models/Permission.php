<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;
use Laratrust\Traits\LaratrustPermissionTrait;

class Permission extends LaratrustPermission
{
    use LaratrustPermissionTrait;
    public $guarded = [];
    public $fillable = ['name', 'display_name', 'description', 'action', 'resource', 'range'];
    public string $resourceNameInMandarin = '權限';
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
        return $this->attributes['range_parsed'] = $this->ranges[$this->range];
    }
}
