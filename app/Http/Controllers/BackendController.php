<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdmittedMail;
use View;

class BackendController extends Controller
{
    protected $campDataService, $applicantService, $batch_id, $camp_data, $batch;
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
            $this->batch = Batch::find($request->route()->parameter('batch_id'));
            View::share('batch', $this->batch);
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
        return view('backend.MasterIndex')->with("camps", $camps);
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
                $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->admittedSN);
                $group = $groupAndNumber['group'];
                $number = $groupAndNumber['number'];
                $check = Applicant::select('applicants.*')
                ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                ->where('group', 'like', $group)->where('number', 'like', $number)->first();
                if($check){
                    $candidate = $this->applicantService->Mandarization($candidate);
                    $error = "報名序號重複。";
                    return view('backend.registration.showCandidate', compact('candidate', 'error'));
                }
                $candidate->is_admitted = 1;
                $candidate->group = $group;
                $candidate->number = $number;
                $candidate->save();
                $message = "錄取完成。";
            }
            $candidate = $this->applicantService->Mandarization($candidate);
            return view('backend.registration.showCandidate', compact('candidate', 'message'));
        }
        else{
            $candidates = Applicant::select('applicants.*')
            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id');
            $count = $candidates->count();
            $admitted = $candidates->where('is_admitted', 1)->count();
            return view('backend.registration.admission', compact('count', 'admitted'));
        }
    }

    public function batchAdmission(Request $request) {
        if ($request->isMethod('POST')) {
            $error = array();
            $message = array();
            $applicants = array();
            if(!isset($request->id)){
                return "沒有輸入任何欄位，請回上上頁重試。";
            }
            foreach($request->id as $key => $id){
                $skip = false;
                $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->admittedSN[$key]);
                $group = $groupAndNumber['group'];
                $number = $groupAndNumber['number'];
                $check = Applicant::select('applicants.*')
                ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                ->where('group', 'like', $group)->where('number', 'like', $number)->first();
                $candidate = Applicant::find($id);
                if($check){
                    array_push($error, $candidate->name . "，錄取序號" . $request->admittedSN[$key] . "重複。");
                    $skip = true;
                }
                if(!$skip){
                    $candidate->is_admitted = 1;
                    $candidate->group = $group;
                    $candidate->number = $number;
                    $applicant = $candidate->save();
                    array_push($message, $candidate->name . "，錄取序號" . $request->admittedSN[$key] . "錄取完成。");
                }
                $applicant = $this->applicantService->Mandarization($candidate);
                array_push($applicants, $applicant);
            }
            return view('backend.registration.showBatchCandidate', compact('applicants', 'error', 'message'));
        }
        else{
            $candidates = Applicant::select('applicants.*')
            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id');
            $count = $candidates->count();
            $admitted = $candidates->where('is_admitted', 1)->count();
            return view('backend.registration.batchAdmission', compact('count', 'admitted'));
        }
    }

    public function showBatchCandidate(Request $request){
        $applicants = explode(",", $request->snORadmittedSN);
        foreach($applicants as &$applicant){
            $groupAndNumber = $this->applicantService->groupAndNumberSeperator($applicant);
            $group = $groupAndNumber['group'];
            $number = $groupAndNumber['number'];
            $candidate = $this->applicantService->fetchApplicantData($this->campFullData->table, $applicant, $group, $number);
            if($candidate){
                $applicant = $this->applicantService->Mandarization($candidate);
            }
            else{
                $id = $applicant;
                $applicant = collect();
                $applicant->id = $id;
                $applicant->batch_id = "";
                $applicant->name = "無資料";
                $applicant->gender = "N/A";
                $applicant->region = "--";
                $applicant->group = "-";
                $applicant->number = "-";
            }
        }
        
        return view('backend.registration.showBatchCandidate', compact('applicants'));
    }

    public function showCandidate(Request $request){
        $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->snORadmittedSN);
        $group = $groupAndNumber['group'];
        $number = $groupAndNumber['number'];
        $candidate = $this->applicantService->fetchApplicantData($this->campFullData->table, $request->snORadmittedSN, $group, $number);
        if($candidate){
            $candidate = $this->applicantService->Mandarization($candidate);
        }
        
        return view('backend.registration.showCandidate', compact('candidate'));
    }

    public function showRegistration() {
        return view('backend.registration.registration');
    }

    public function showRegistrationList() {
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        return view('backend.registration.list', compact('batches'));
    }

    public function getRegistrationList(Request $request){
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        if(isset($request->region)){
            $query = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id);
            if($request->region == '全區'){
                $applicants = $query->get();
            }
            else{
                $applicants = $query->where('region', $request->region)->get();
            }
            $query = $request->region;
        }
        elseif(isset($request->school_or_course)){
            //教師營使用 school_or_course 欄位
            $applicants = Applicant::select("applicants.*", "tcamp.*", "batchs.name as bName", "applicants.id as sn")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join('tcamp', 'applicants.id', '=', 'tcamp.applicant_id')
                            ->where('camps.id', $this->campFullData->id)
                            ->where('school_or_course', $request->school_or_course)->get();
            $query = $request->school_or_course;
        }
        elseif(isset($request->batch)){
            $applicants = Applicant::select("applicants.*", "tcamp.*", "batchs.name as bName", "applicants.id as sn")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')                        
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)
                        ->where('batchs.name', $request->batch)->get();
            $query = $request->batch . '梯';
        }
        else{
            $applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                            ->where('camps.id', $this->campFullData->id)
                            ->where('address', "like", "%" . $request->address . "%")->get();
            $query = $request->address;
        }
        if(isset($request->download)){
            $fileName = $this->campFullData->abbreviation . $query . \Carbon\Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            $columns = array();

            $callback = function() use($applicants, $columns) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                $keys = $this->campDataService->csvColumnParser($this->campFullData->table);   
                $zhKeys = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table));             
                foreach($keys as $key){
                    $columns[] = $zhKeys[$key];
                }
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach($keys as $key){
                        array_push($rows, '="' . $applicant[$key] . '"');
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        return view('backend.registration.list', compact('applicants', 'query', 'batches'));
    }

    public function sendAdmittedMail(Request $request){
        foreach($request->emails as $key => $email){
            Mail::to($email)->send(new AdmittedMail($request->names[$key], $request->admittedNos[$key], $this->campFullData));
        }
        \Session::flash('message', "已成功寄送全組錄取通知信。");
        return back();
    }

    public function showGroupList() {
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch){
            $batch->regions = Applicant::select('region')->where('batch_id', $batch->id)->where('is_admitted', 1)->groupBy('region')->get();
            foreach($batch->regions as &$region){
                $region->groups = Applicant::select('group', \DB::raw('count(*) as count'))->where('batch_id', $batch->id)->where('region', $region->region)->where('is_admitted', 1)->groupBy('group')->get();
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.registration.groupList')->with('batches', $batches);
    }

    public function showGroup(Request $request){
        $batch_id = $request->route()->parameter('batch_id');
        $group = $request->route()->parameter('group');
        $applicants = Applicant::join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')->where('batch_id', $batch_id)->where('group', $group)->get();
        return view('backend.registration.group', compact('applicants'));
    }
    
    public function appliedDateStat() {
        $applicants = Applicant::select(\DB::raw('DATE_FORMAT(applicants.created_at, "%Y-%m-%d") as date, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('date')->get();
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
        
        return view('backend.statistics.appliedDate', compact('GChartData',  'total'));
    }

    public function genderStat() {
        $applicants = Applicant::select(\DB::raw('applicants.gender, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('applicants.gender')->get();
        $rows = count($applicants);
        foreach($applicants as $applicant){
            $applicant = $this->applicantService->Mandarization($applicant);
        }
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

        return view('backend.statistics.gender', compact('GChartData',  'total'));
    }

    public function countyStat() {
        $applicants = Applicant::select(\DB::raw('SUBSTRING(applicants.address, 1, 3) as county, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('county')->get();
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

        return view('backend.statistics.county', compact('GChartData',  'total'));
    }

    public function batchesStat(){
        $applicants = Applicant::select(\DB::raw('batchs.name as batch, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('batchs.name')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'batch','label'=>'梯次','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['batch'] == null ? '其他' : $record['batch']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.batches', compact('GChartData',  'total'));
    }
}
