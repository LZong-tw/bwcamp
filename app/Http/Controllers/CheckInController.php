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
        $this->middleware('permitted');
        $camp = Batch::orderBy('batch_start', 'desc')->first()->camp()->first();
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
        $applicants = Applicant::where('is_admitted', 1)
            ->where(function($query) use ($request, $group, $number){
                $query->where('id', $request->query_str)
                ->orWhere('group', $request->query_str);
                if($group && $number){
                    $query->orWhere([['group', $group], ['number', $number]]);
                }
                $query->orWhere('name', $request->query_str)
                ->orWhere('mobile', $request->query_str);
            })->get();
        $request->flash();
        return view('checkIn.home', compact('applicants'));
    }

    public function checkIn(Request $request) {
        if(CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', \Carbon\Carbon::today()->format('Y-m-d'))->first()){            
            return back()->withErrors(['無法重複報到。']);  
        }
        else{
            $checkin = new CheckIn;
            $checkin->applicant_id = $request->applicant_id;
            $checkin->checker_id = \Auth()->user()->id;
            $checkin->check_in_date = \Carbon\Carbon::today()->format('Y-m-d');
            $checkin->save();
        }
        \Session::flash('message', "報到成功。");
        return back();
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
