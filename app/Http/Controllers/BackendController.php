<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use View;

class BackendController extends Controller
{
    protected $campDataService, $batch_id, $camp_data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService,  Request $request)
    {
        $this->middleware('auth');
        $this->middleware('permitted');
        $this->campDataService = $campDataService;
        if($request->route()->parameter('batch_id')){
            // 營隊資料，存入 view 全域
            $this->batch_id = $request->route()->parameter('batch_id');
            $this->camp_data = $this->campDataService->getCampData($this->batch_id)['camp_data'];
            View::share('batch_id', $this->batch_id);
            View::share('camp_data', $this->camp_data);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function backendMasterIndex()
    {
        // $this->campDataService->getCampData($this->batch_id);
        return view('backend.MasterIndex');
    }

    public function backendIndex()
    {
        $this->campDataService->getCampData($this->batch_id);
        return view('backend.index');
    }
}
