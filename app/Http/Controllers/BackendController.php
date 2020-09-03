<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Models\Camp;
use View;

class BackendController extends Controller
{
    protected $campDataService, $batch_id, $camp_data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService,  Request $request) {
        $this->middleware('auth');
        $this->campDataService = $campDataService;
        if($request->route()->parameter('batch_id')){
            // 營隊資料，存入 view 全域
            $this->batch_id = $request->route()->parameter('batch_id');
            $this->camp_data = $this->campDataService->getCampData($this->batch_id)['camp_data'];
            View::share('batch_id', $this->batch_id);
            View::share('camp_data', $this->camp_data);
        }
        if($request->route()->parameter('camp_id')){
            $this->middleware('permitted');
            $this->camp_id = $request->route()->parameter('camp_id');
            $this->campFullData = Camp::find($this->camp_id);
            View::share('camp_id', $this->camp_id);
            View::share('campFullData', $this->campFullData);
        }
    }

    /**
     * 營隊選單、登入後顯示的畫面
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function masterIndex() {
        // 檢查權限
        $permission = auth()->user()->getPermission();
        $camps = $this->campDataService->getAvailableCamps($permission);
        return view('backend.masterIndex')->with("camps", $camps);
    }

    public function campIndex() {
        return view('backend.campIndex');
    }

    public function admission() {
        return view('backend.admission');
    }

    public function showRegistration() {
        return view('backend.registration');
    }
}
