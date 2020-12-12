<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
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
        return view('checkIn.home', compact('applicants'));
    }

    public function checkIn(Request $request) {
        return view('checkIn.home', compact('applicants'));
    }
}
