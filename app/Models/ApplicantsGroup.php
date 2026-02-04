<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Applicant;

class ApplicantsGroup extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'batch_id',
        'alias',
        'category',
    ];

    public $resourceNameInMandarin = '學員組別';

    public $resourceDescriptionInMandarin = '營隊中的學員組別，附屬在梯次下。';

    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'group_id', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function numbers()
    {
        return $this->hasMany(GroupNumber::class, 'group_id', 'id');
    }

    public function camp_orgs()
    {
        return $this->hasMany(CampOrg::class, 'group_id', 'id');
    }

    public function carers()
    {
        $vbatchId = $this->batch->vbatch?->id;
        $groupId = $this->id;

        // 直接查詢 Applicant 模型，不用手動建立空集合
        return \App\Models\Applicant::where('batch_id', $vbatchId)
            ->whereHas('user.camp_orgs', function ($query) use ($groupId) {
                // 這裡的 $query 指的是 User 的 camp_orgs 關聯
                $query->where('group_id', $groupId);
            })
            ->get();
    }
}
