<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Camp;
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
        $groups = $this->backendService
                    ->getBatchGroups(Camp::findOrFail($campId))
                    ->filter(function ($batch) use ($request) {
                        return $batch->id == $request->input('batch_id');
                    })
                    ->first()
                    ->groups;
        return response()->json($groups);
    }

    public function setGroup(Request $request)
    {
        $applicant = $this->backendService->setGroupNew($request->applicant_ids, $request->group_id);
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
        $orgs = $orgs->filter(function ($org) {
            return $org->position == 'root';
        });
        return response()->json($orgs);
    }

    public function getCampPositions(Request $request)
    {
        $campId = $request->input('camp_id');
        $orgs = $this->backendService
                    ->getCampOrganizations(Camp::findOrFail($campId));
        $orgs = $orgs->filter(function ($org) use ($request) {
            return $org->position != 'root' && $org->section == $request->input('section');
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
}
