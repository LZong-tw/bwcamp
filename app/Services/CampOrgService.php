<?php

namespace App\Services;

use App\Models\CampOrg;
use Illuminate\Support\Collection;

class CampOrgService
{
    public function updatePrevIdChildren(Collection $orgs, CampOrg $parent)
    {
        if ($parent->prev_id == 0) {  //root
            $sec_tg = $parent->position;
        } else {
            $sec_tg = $parent->section . '.' . $parent->position;
        }

        $children = $orgs->where('section', $sec_tg)->where('prev_id', '<>', 0);
        foreach ($children as $child) {
            $child->prev_id = $parent->id;
            if ($child->is_node == 1) {
                $this->updatePrevIdChildren($orgs, $child);
            }
        }
    }

    public function updatePrevId(Collection $orgs)
    {
        $root = $orgs->where('prev_id', 0)->first();   //root
        //recusively go through all nodes/leaves
        if ($root->is_node == 1) {  //has children
            $this->updatePrevIdChildren($orgs, $root);
        }
        //$root->save();
        foreach ($orgs as $org) {
            $org->save();
        }
    }

    public function updateSectionChildren(Collection $orgs, CampOrg $parent)
    {
        $children = $orgs->where('prev_id', $parent->id);
        if ($parent->prev_id == 0) {  //root
            $sec = $parent->position;
        } else {
            $sec = $parent->section . '.' . $parent->position;
        }

        foreach ($children as $child) {
            $child->section = $sec;
            if ($child->is_node == 1) {
                $this->updateSectionChildren($orgs, $child);
            }
        }
    }

    public function updateSection(Collection $orgs)
    {
        $root = $orgs->where('prev_id', 0)->first();   //root
        //recursively go through all nodes/leaves
        if ($root->is_node == 1) {
            $this->updateSectionChildren($orgs, $root);
        }
        //$root->save();
        foreach ($orgs as $org) {    //1st layer
            $org->save();
        }
    }
}
