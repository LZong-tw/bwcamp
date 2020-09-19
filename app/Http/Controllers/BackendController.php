<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use View;

class BackendController extends Controller
{
    protected $campDataService, $applicantService,$batch_id, $camp_data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService, ApplicantService $applicantService, Request $request) {
        $this->middleware('auth');
        $this->campDataService = $campDataService;
        $this->applicantService = $applicantService;
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

    public function admission(Request $request) {
        if ($request->isMethod('POST')) {
            $candidate = Applicant::find($request->id);
            if($request->get("clear") == "清除錄取序號"){
                $candidate->is_admitted = 0;
                $candidate->group = null;
                $candidate->number = null;
                $candidate->save();
                $message = "錄取序號已清除。";
            }
            else{
                $group = substr($request->admittedSN, 0, 4);
                $number = substr($request->admittedSN, 4, strlen($request->admittedSN));
                $check = Applicant::select('applicants.*')
                ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                ->where('group', 'like', $group)->where('number', 'like', $number)->first();
                if($check){
                    $candidate = $this->applicantService->Mandarization($candidate);
                    $error = "報名序號重複。";
                    return view('backend.showCandidate', compact('candidate', 'error'));
                }
                $candidate->is_admitted = 1;
                $candidate->group = $group;
                $candidate->number = $number;
                $candidate->save();
                $message = "錄取完成。";
            }
            $candidate = $this->applicantService->Mandarization($candidate);
            return view('backend.showCandidate', compact('candidate', 'message'));
        }
        else{
            $candidates = Applicant::select('applicants.*')
            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id');
            $count = $candidates->count();
            $admitted = $candidates->where('is_admitted', 1)->count();
            return view('backend.admission', compact('count', 'admitted'));
        }
    }

    public function showCandidate(Request $request){
        $group = substr($request->snORadmittedSN, 0, 4);
        $number = substr($request->snORadmittedSN, 4, strlen($request->snORadmittedSN));
        $candidate = Applicant::select('applicants.*')
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->where('applicants.id', $request->snORadmittedSN)
        ->orWhere(function ($query) use ($group, $number) {
            $query->where('group', 'like', $group);
            $query->where('number', 'like', $number);
        })->first();
        if($candidate){
            $candidate = $this->applicantService->Mandarization($candidate);
        }
        
        return view('backend.showCandidate', compact('candidate'));
    }

    public function showRegistration() {
        return view('backend.registration');
    }
    
    public function appliedDateStat() {
        $applicants = Applicant::select(\DB::raw('DATE_FORMAT(applicants.created_at, "%Y-%m-%d") as date, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->groupBy('applicants.created_at')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();
        
        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'date','label'=>'日期','type'=>'date'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            $year = (int) substr($record['date'], 0, 4);
            $month = ((int) substr($record['date'], 5, 2)) - 1;
            $day = (int) substr($record['date'], -2);
            array_push($GChartData['rows'], array('c' => array(
                array('v' => "Date($year, $month, $day)"),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);
        
        return view('backend.stat.appliedDate', compact('GChartData',  'total'));
    }

    public function genderStat() {
        $applicants = Applicant::select(\DB::raw('applicants.gender, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->groupBy('applicants.gender')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'gender','label'=>'性別','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['gender']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.stat.gender', compact('GChartData',  'total'));
    }

    public function countyStat() {
        $applicants = Applicant::select(\DB::raw('SUBSTRING(applicants.address, 1, 3) as county, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->groupBy('applicants.address')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'city','label'=>'縣市','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['county'] == null ? '其他' : $record['county']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.stat.county', compact('GChartData',  'total'));
    }
}
