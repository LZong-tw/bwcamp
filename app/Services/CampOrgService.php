<?php
namespace App\Services;

use App\Models\Camp;
use App\Models\CampOrg;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CampOrgService
{
    public function updatePrevIdChildren(Collection $orgs, CampOrg $parent){
        if ($parent->prev_id == 0)  //root
            $sec_tg = $parent->position;
        else
            $sec_tg = $parent->section . '.' . $parent->position;

        $children = $orgs->where('section', $sec_tg)->where('prev_id', '<>', 0);
        foreach ($children as $child) {
            $child->prev_id = $parent->id;
            if ($child->is_node == 1) {
                $this->updatePrevIdChildren($orgs, $child);
            }
        }
    }

    public function updatePrevId(Collection $orgs){
        $root = $orgs->where('prev_id',0)->first();   //root
        //recusively go through all nodes/leaves
        if ($root->is_node == 1) {  //has children
            $this->updatePrevIdChildren($orgs, $root);
        }
        //$root->save();
        foreach ($orgs as $org) {
            $org->save();
        }
    }

    public function updateSectionChildren(Collection $orgs, CampOrg $parent) {
        $children = $orgs->where('prev_id', $parent->id);
        if ($parent->prev_id == 0)  //root
            $sec = $parent->position;
        else
            $sec = $parent->section . '.' . $parent->position;

        foreach ($children as $child) {
            $child->section = $sec;
            if ($child->is_node == 1) {
                $this->updateSectionChildren($orgs, $child);
            }
        }
    }

    public function updateSection(Collection $orgs){
        $root = $orgs->where('prev_id',0)->first();   //root
        //recursively go through all nodes/leaves
        if ($root->is_node == 1) {
            $this->updateSectionChildren($orgs, $root);
        }
        //$root->save();
        foreach ($orgs as $org) {    //1st layer
            $org->save();
        }
    }

    public function copyPermissions(Camp $campDst, Camp $campSrc, CampOrg $orgDst, CampOrg $orgSrc, array $batchIdMatchList) {
        /*
        //match batches
        $batchesDst = $campDst->batchs;
        $batchesSrc = $campSrc->batchs;
        //check if number of batches is the same
        if ($batchesDst->count != $batchesSrc->count) {
            return;
        }
        $batchIdMatchList = array("0"=>"0");
        foreach($batchesSrc as $batchSrc) {
            $batchDst = $batchesDst->pull();
            //batch
            $batchMatchList[$batchSrc->id] = $batchDst->id;
            //vbatch
            $batchMatchList[$batchSrc->vbatch->id] = $batchDst->vbatch->id;
        }*/

        $permissionsSrc = $orgSrc->permissions;
        if ($permissionsSrc->count() == 0) return;   //nothing to copy
        $permissionsDst = collect();
        foreach($permissionsSrc as $permissionSrc) {
            $permissionDst = $permissionSrc->replicate();
            $permissionDst->camp_id = $campDst->id;
            if ( !is_null($permissionDst->batch_id) ) {
                $permissionDst->batch_id = $batchIdMatchList[$permissionSrc->batch_id];
            }
            $permissionDst->display_name = str_replace($campSrc->abbreviation, $campDst->abbreviation, $permissionSrc->display_name);
            $permissionDst->description = str_replace($campSrc->abbreviation, $campDst->abbreviation, $permissionSrc->description);
            $permissionDst->created_at = Carbon::now();
            $permissionDst->save();
            $permissionsDst->push($permissionDst);
        }
        $orgDst->syncPermissions($permissionsDst);
        return;
    }
    
    //campDst = campSrc && batchDst = batchSrc
    public function duplicatePermissions(CampOrg $orgDst, CampOrg $orgSrc) {
        $permissionsSrc = $orgSrc->permissions;
        if ($permissionsSrc->count() == 0) return;   //nothing to copy
        $permissionsDst = collect();
        foreach($permissionsSrc as $permissionSrc) {
            $permissionDst = $permissionSrc->replicate();
            $permissionDst->created_at = Carbon::now();
            $permissionDst->save();
            $permissionsDst->push($permissionDst);
        }
        $orgDst->syncPermissions($permissionsDst);
        return;
    }
}