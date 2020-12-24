<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\CheckIn;
use View;

class CheckInController extends Controller
{
    protected $campDataService, $applicantService, $batch_id, $camp_data, $batch;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService, ApplicantService $applicantService, Request $request) {
        $this->middleware('auth');
        $camp = Batch::orderBy('batch_start', 'desc')->first()->camp()->first();
        $request->camp_id = $camp->id;
        $this->middleware('permitted');
        $this->camp = $camp;
        $this->campDataService = $campDataService;
        $this->applicantService = $applicantService;
        View::share('camp', $this->camp);
    }

    public function index() {
        return view('checkIn.home');
    }

    public function query(Request $request) {
        $group = null;
        $number = null;
        if(\Str::length($request->query_str) == 5){
            $group = substr($request->query_str, 0, 3);
            $number = substr($request->query_str, 3, 2);
        }
        $constrain = function($query){ $query->where('camps.id', $this->camp->id); };
        $applicants = Applicant::with(['batch', 'batch.camp' => $constrain])
            ->whereHas('batch.camp', $constrain)
            ->where('is_admitted', 1)
            ->where(function($query) use ($request, $group, $number){
                $query->where('id', $request->query_str)
                ->orWhere('group', $request->query_str);
                if($group && $number){
                    $query->orWhere([['group', $group], ['number', $number]]);
                }
                $query->orWhere('name', 'like', '%' . $request->query_str . '%')
                ->orWhere('mobile', 'like', '%' . $request->query_str . '%');
            })->get();
        $batches = $applicants->pluck('batch.name', 'batch.id')->unique();
        $request->flash();
        return view('checkIn.home', compact('applicants', 'batches'));
    }

    public function checkIn(Request $request) {
        if(CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', \Carbon\Carbon::today()->format('Y-m-d'))->first()){            
            return back()->withErrors(['無法重複報到。']);  
        }
        else{
            $applicant = Applicant::find($request->applicant_id);
            if($applicant->deposit - $applicant->fee < 0){
                return back()->withErrors([$applicant->name . '未繳費，無法報到。']);      
            }     
            $checkin = new CheckIn;
            $checkin->applicant_id = $request->applicant_id;
            $checkin->checker_id = \Auth()->user()->id;
            $checkin->check_in_date = \Carbon\Carbon::today()->format('Y-m-d');
            $checkin->save();
        }
        \Session::flash('message', "報到成功。");
        return back();
    }

    public function by_QR(Request $request) {
        try{
            $applicant = Applicant::find($request->applicant_id);
            if(!$applicant){
                return response()->json([
                    'msg' => '<h4 class="text-danger">找不到報名資料，請檢查後重試</h4>'
                ]);
            }
            if($applicant->deposit - $applicant->fee < 0){   
                return response()->json([
                    'msg' => '場次：' . $applicant->batch->name . '<br>錄取序號：' . $applicant->group . $applicant->number . '<br>姓名：' . $applicant->name . '<h4 class="text-danger">未繳費，無法報到。</h4>'
                ]);  
            }     
            if(CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', \Carbon\Carbon::today()->format('Y-m-d'))->first()){            
                return response()->json([
                    'msg' => '場次：' . $applicant->batch->name . '<br>錄取序號：' . $applicant->group . $applicant->number . '<br>姓名：' . $applicant->name . '<h4 class="text-warning">已報到完成，無法重複報到</h4>'
                ]);  
            }
            else{
                $checkin = new CheckIn;
                $checkin->applicant_id = $request->applicant_id;
                $checkin->checker_id = \Auth()->user()->id;
                $checkin->check_in_date = \Carbon\Carbon::today()->format('Y-m-d');
                $checkin->save();
                return response()->json([
                    'msg' => '場次：' . $applicant->batch->name . '<br>錄取序號：' . $applicant->group . $applicant->number . '<br>姓名：' . $applicant->name . '<h4 class="text-success">報到完成</h4>'
                ]);
            }  
        }
        catch(\Exception $e){
            logger($e);
            return response()->json([
                'msg' => '<h4 class="text-danger">發生未預期錯誤，無法完成報到程序</h4>'
            ]);
        }
    }

    public function uncheckIn(Request $request) {
        if(CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', $request->check_in_date)->first()->delete()){   
            \Session::flash('message', "報消報到成功。");       
        }
        else{
            return back()->withErrors(['取消報到過程發生未知錯誤。']);  
        }
        return back();
    }
}
