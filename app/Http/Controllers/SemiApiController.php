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
}
