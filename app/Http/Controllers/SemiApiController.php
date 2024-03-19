<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\CampOrg;
use App\Services\BackendService;
use Illuminate\Http\Request;

class SemiApiController extends Controller
{
    //
    public function __construct(public BackendService $backendService)
    {
        parent::__construct();
    }

    public function getBatchGroups(Request $request)
    {
        $campId = $request->input('camp_id');
        $batchGroups = $this->backendService
                    ->getBatchGroups(Camp::findOrFail($campId));
        if ($request->input('batch_id')) {
            $groups = $batchGroups->filter(function ($batch) use ($request) {
                    return $batch->id == $request->input('batch_id');
                })->first()->groups;
        }
        else {
            $groups = $batchGroups->map(function ($batch) {
                return $batch->groups;
            })->flatten();
        }
        $groups = $groups->map(function ($group) {
            return [
                'id' => $group->id,
                'name' => $group->batch->name . ": " . $group->alias,
            ];
        });
        return response()->json($groups);
    }

    public function setGroup(Request $request)
    {
        $applicant = $this->backendService->setGroupNew($request->applicant_ids, $request->group_id);
        return response()->json(['status' => 'success']);
    }

    public function setCarer(Request $request)
    {
        $applicant = $this->backendService->setCarer($request->applicant_ids, $request->carer_id);
        return response()->json(['status' => 'success']);
    }

    public function setGroupOrg(Request $request)
    {
        $applicant = $this->backendService->setGroupOrg($request->applicant_ids, $request->group_id);
        return response()->json(['status' => 'success']);
    }

    public function groupCreation(Request $request)
    {
        $campId = $request->input('camp_id');
        $camp = Camp::findOrFail($campId);
        $batchs = $camp->batchs;
        $batchs->each(function ($batch) {
            $this->backendService->groupsCreation($batch);
        });
        return response()->json(true);
    }

    public function getCampOrganizations(Request $request)
    {
        $campId = $request->input('camp_id');
        $orgs = $this->backendService
                    ->getCampOrganizations(Camp::findOrFail($campId));
        // Get region name
        $orgs = $orgs->map(function ($org) {
            $org->region_name = $org->region?->name;
            return $org;
        });
        $orgs = $orgs->filter(function ($org) {
                    return 1;
                })->each(function ($org) {
                    if ($org->section == "root") {
                        $org->section = $org->region_name ."大會";
                    }
                    else {
                        $org->section = str_replace("root.", " - ", $org->section);
                    }
                })->unique();
        return response()->json($orgs);
    }

    public function getCampPositions(Request $request)
    {
        $campId = $request->input('camp_id');
        $nodeId = $request->input('node_id');
        $target_org = CampOrg::find($nodeId);
        $orgs = $this->backendService
                    ->getCampOrganizations(Camp::findOrFail($campId));
        $orgs = $orgs->filter(function ($org) use ($target_org) {
            if ($org->section == "root" && $target_org->section == "root") {
                return $org->prev_id == 0 && !$org->is_node && $org->region_id == $target_org->region_id;
            }
            return $org->position != 'root' && $org->section == $target_org->section && $org->prev_id == $target_org->prev_id;
        });
        $orgs = $orgs->each(function ($org) {
            if (str_contains($org->position, "關懷小組")) {
                $org->batch_name = $org->batch?->name;
            }
        });
        return response()->json($orgs);
    }

    public function getCampVolunteers(Request $request)
    {
        // todo: 臨時性，待更完整
        $campId = $request->input('camp_id');
        $camp = Camp::findOrFail($campId);
        $theVcamp = Camp::with(['batchs', 'batchs.applicants'])
                    ->where('table', 'like', '%vcamp%')
                    ->where('table', 'like', '%' . str_replace('camp', '', $camp->table) . '%')
                    ->where('year', $camp->year)
                    ->first();
        return response()->json($theVcamp);
    }

        public function getOrgSel(Request $request)
    {
        $campId = $request->input('camp_id_sel');
        $camp = Camp::findOrFail($campId);
        $orgs = $camp->organizations;
        $orgs = $orgs->sortByDesc('section');
        //dd($orgs);
        return response()->json($orgs);
    }
}
