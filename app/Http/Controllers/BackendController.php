<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\CheckIn;
use App\Models\Traffic;
use Carbon\Carbon;
use View;
use App\Traits\EmailConfiguration;
use App\Models\SignInSignOut;

class BackendController extends Controller {
    use EmailConfiguration;

    protected $campDataService, $applicantService, $batch_id, $camp_data, $batch, $has_attend_data;

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
            if($this->camp_data->table == 'ycamp' || $this->camp_data->table == 'acamp'){
                if($this->camp_data->admission_confirming_end && Carbon::now()->gt($this->camp_data->admission_confirming_end)){
                    $this->has_attend_data = true; 
                }
            }
            // 動態載入電子郵件設定
            $this->setEmail($this->camp_data->table, $this->camp_data->variant);
        }
        if($request->route()->parameter('camp_id')){
            $this->middleware('permitted');
            $this->camp_id = $request->route()->parameter('camp_id');
            $this->campFullData = Camp::find($request->route()->parameter('camp_id'));
            View::share('camp_id', $this->camp_id);
            View::share('campFullData', $this->campFullData);
            if($this->campFullData->table == 'ycamp' || $this->campFullData->table == 'acamp'){
                if($this->campFullData->admission_confirming_end && Carbon::now()->gt($this->campFullData->admission_confirming_end)){
                    $this->has_attend_data = true; 
                }
            }
            // 動態載入電子郵件設定
            $this->setEmail($this->campFullData->table, $this->campFullData->variant);
        }
        if(\Str::contains(url()->current(), "campManage")){
            $this->middleware('admin');
        }     
    }

    /**
     * 營隊選單、登入後顯示的畫面
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function masterIndex() {
        // 檢查權限
        $permission = auth()->user()->getPermission('all');
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
                ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                ->where('group', 'like', $group)->where('number', 'like', $number)
                ->where('camps.id', $this->campFullData->id)->first();
                if($check){
                    $candidate = $this->applicantService->Mandarization($candidate);
                    $error = "報名序號重複。";
                    return view('backend.registration.showCandidate', compact('candidate', 'error'));
                }                
                $candidate->is_admitted = 1;
                $candidate->group = $group;
                $candidate->number = $number;
                $candidate = $this->applicantService->fillPaymentData($candidate);
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
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $this->campFullData->id);
            $count = $candidates->count();
            $admitted = $candidates->where('is_admitted', 1)->count();
            return view('backend.registration.admission', compact('count', 'admitted'));
        }
    }

    public function showPaymentForm($camp_id, $applicant_id) {
        $applicant = Applicant::find($applicant_id);
        $applicant = $this->applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->save();
        $download = $_GET['download'] ?? false;
        if(!$download){
            return view('camps.' . $applicant->batch->camp->table . '.paymentForm', compact('applicant','download'));
        }
        else{
            return \PDF::loadView('camps.' . $applicant->batch->camp->table . '.paymentFormPDF', compact('applicant'))->download(Carbon::now()->format('YmdHis') . $applicant->batch->camp->table . $applicant->id . '.pdf');
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
            $batches = Batch::where("camp_id", $this->camp_id)->get()->pluck("id");
            foreach($request->id as $key => $id){
                $skip = false;
                $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->admittedSN[$key]);
                $group = $groupAndNumber['group'];
                $number = $groupAndNumber['number'];                
                $check = Applicant::select('applicants.*')
                ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                ->where('group', 'like', $group)->where('number', 'like', $number)
                ->whereIn("batch_id", $batches)->first();
                $candidate = Applicant::find($id);
                if($check){
                    array_push($error, $candidate->name . "，錄取序號" . $request->admittedSN[$key] . "重複，沒有針對此人執行任何動作。");
                    $skip = true;
                }
                if(!$skip){                    
                    $candidate->is_admitted = 1;
                    $candidate->group = $group;
                    $candidate->number = $number;
                    $candidate = $this->applicantService->fillPaymentData($candidate);
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
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $this->camp_id);
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
            $candidate = $this->applicantService->fetchApplicantData($this->campFullData->id, $this->campFullData->table, $applicant, $group, $number);
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
        $candidate = $this->applicantService->fetchApplicantData($this->campFullData->id, $this->campFullData->table, $request->snORadmittedSN, $group, $number);
        if($candidate){
            $candidate = $this->applicantService->Mandarization($candidate);
        }
        
        if(isset($request->change)){
            $batches = Batch::where('camp_id', $this->campFullData->id)->get();
            return view('backend.registration.changeBatchOrRegionForm', compact('candidate', 'batches'));
        }
        
        if(\Str::contains(request()->headers->get('referer'), 'accounting')){
            $candidate = $this->applicantService->checkPaymentStatus($candidate);
            return view('backend.modifyAccounting', ['applicant' => $candidate]);
        }
        return view('backend.registration.showCandidate', compact('candidate'));
    }

    public function showRegistration() {
        $user_batch_or_region = null;
        if($this->campFullData->table == 'ecamp' && auth()->user()->getPermission('all')->first()->level > 2){
            $user_batch_or_region = Batch::where('camp_id', $this->campFullData->id)->where('name', 'like', '%' . auth()->user()->getPermission(true, $this->campFullData->id)->region . '%')->first();
            $user_batch_or_region = $user_batch_or_region ?? "empty";
        }
        return view('backend.registration.registration', compact('user_batch_or_region'));
    }

    public function showRegistrationList() {
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        return view('backend.registration.list', compact('batches'));
    }

    public function getRegistrationList(Request $request){
        ini_set('max_execution_time', 1200);
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        if(isset($request->region)){
            $query = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)->withTrashed();
            if($request->region == '全區'){
                $applicants = $query->get();
            }
            elseif($request->region == '其他'){
                if ($this->campFullData->table == 'ceocamp' || $this->campFullData->table == 'ceovcamp') {
                    $applicants = $query->whereNotIn('region', ['北區', '竹區', '中區', '高區'])->get();
                } elseif ($this->campFullData->table == 'ecamp') {
                    $applicants = $query->whereNotIn('region', ['台北', '桃園', '新竹', '中區', '雲嘉', '台南', '高區'])->get();
                } else {
                    $applicants = $query->whereNotIn('region', ['台北', '桃園', '新竹', '台中', '雲嘉', '台南', '高雄'])->get();
                }
            }
            else{
                $applicants = $query->where('region', $request->region)->get();
            }
            $query = $request->region;
        }
        elseif(isset($request->school_or_course)){
            //教師營使用 school_or_course 欄位
            $applicants = Applicant::select("applicants.*", "tcamp.*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join('tcamp', 'applicants.id', '=', 'tcamp.applicant_id')
                            ->where('camps.id', $this->campFullData->id);
            if($request->school_or_course == "無") {
                $applicants = $applicants->where(function($q) {
                    $q->where('school_or_course',  "")
                    ->orWhereNull('school_or_course');
                });
            }
            else{
                $applicants = $applicants->where('school_or_course',  $request->school_or_course);
            }                            
            $applicants = $applicants->withTrashed()->get();
            $query = $request->school_or_course;
        }
        elseif(isset($request->education)){
            //快樂營使用 education 欄位
            $applicants = Applicant::select("applicants.*", "hcamp.*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join('hcamp', 'applicants.id', '=', 'hcamp.applicant_id')
                            ->where('camps.id', $this->campFullData->id)
                            ->where('education', $request->education)
                            ->withTrashed()->get();
            $query = $request->education;
        }
        elseif(isset($request->batch)){
            $applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')                        
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)
                        ->where('batchs.name', $request->batch)
                        ->withTrashed()->get();
            $query = $request->batch . '梯';
        }
        else{
            $applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                            ->where('camps.id', $this->campFullData->id)
                            ->where('address', "like", "%" . $request->address . "%")
                            ->withTrashed()->get();
            $query = $request->address;
        }
        if($request->show_cancelled){
            $query .= "(已取消)";
            $applicants = $applicants->whereNotNull('deleted_at');
        }
        foreach($applicants as $applicant){
            if($applicant->fee > 0){
                if($applicant->fee - $applicant->deposit <= 0){
                    $applicant->is_paid = "是";
                }
                else{
                    $applicant->is_paid = "否";
                }
            }
            else{
                $applicant->is_paid = "無費用";
            }
            if($applicant->trashed()){
                $applicant->is_cancelled = "是";
            }
            else{
                $applicant->is_cancelled = "否";
            }
        }
        if(auth()->user()->getPermission(false)->role->level <= 2){
        }
        else if(auth()->user()->getPermission(true, $this->campFullData->id)->level > 2){
            $constraint = auth()->user()->getPermission(true, $this->campFullData->id)->region;
            $batch = Batch::where('camp_id', $this->campFullData->id)->where('name', 'like', '%' . $constraint . '%')->first();
            $applicants = $applicants->filter(function ($applicant) use ($constraint, $batch) {
                if($batch){
                    return $applicant->region == $constraint || $applicant->batch_id == $batch->id;
                }
                return $applicant->region == $constraint;
            });
        }
        // 報名名單不以繳費與否排序
        // $applicants = $applicants->sortByDesc('is_paid');
        if($request->orderByCreatedAtDesc) {
            $applicants = $applicants->sortByDesc('created_at');
        }
        if(isset($request->download)){
            if($applicants){
                // 參加者報到日期
                $checkInDates = CheckIn::select('check_in_date')->whereIn('applicant_id', $applicants->pluck('sn'))->groupBy('check_in_date')->get();
                if($checkInDates){
                    $checkInDates = $checkInDates->toArray();
                }
                else{
                    $checkInDates = array();
                }
                $checkInDates = \Arr::flatten($checkInDates);
                foreach($checkInDates as $key => $checkInDate){
                    unset($checkInDates[$key]);
                    $checkInDates[(string)$checkInDate] = $checkInDate;
                }
                // 各梯次報到日期填充
                $batches = Batch::whereIn('id', $applicants->pluck('batch_id'))->get();
                foreach($batches as $batch){
                    $date = Carbon::createFromFormat('Y-m-d', $batch->batch_start);
                    $endDate = Carbon::createFromFormat('Y-m-d', $batch->batch_end);
                    while(1){
                        if($date > $endDate){
                            break;
                        }
                        $str = $date->format('Y-m-d');                        
                        if(!in_array($str, $checkInDates)){
                            $checkInDates = array_merge($checkInDates, [$str => $str]);
                        }
                        $date->addDay();
                    }
                }
                // 按陣列鍵值升冪排列           
                ksort($checkInDates);
                $checkInData = array();
                // 將每人每日的報到資料按報到日期組合成一個陣列
                foreach($checkInDates as $checkInDate => $v) {
                    $checkInData[(string)$checkInDate] = array();
                    $rawCheckInData = CheckIn::select('applicant_id')->where('check_in_date', $checkInDate)->whereIn('applicant_id', $applicants->pluck('sn'))->get();
                    if($rawCheckInData){
                        $checkInData[(string)$checkInDate] = $rawCheckInData->pluck('applicant_id')->toArray();
                    }
                }
                
                // 簽到退時間
                $signAvailabilities = $this->campFullData->allSignAvailabilities;
                $signData = [];
                $signDateTimesCols = [];
                
                if($signAvailabilities){
                    foreach($signAvailabilities as $signAvailability){
                        $signData[$signAvailability->id] = [
                            'type'       => $signAvailability->type,
                            'date'       => substr($signAvailability->start, 5, 5),
                            'start'      => substr($signAvailability->start, 11, 5),
                            'end'        => substr($signAvailability->end, 11, 5),
                            'applicants' => $signAvailability->applicants->pluck('id')
                        ];
                        $str = $signAvailability->type == "in" ? "簽到時間：" : "簽退時間：";
                        $signDateTimesCols["SIGN_" . $signAvailability->id] = $str . substr($signAvailability->start, 5, 5) . " " . substr($signAvailability->start, 11, 5) . " ~ " . substr($signAvailability->end, 11, 5);
                    }
                }
                else{
                    $signData = array();
                }
            }
            
            $fileName = $this->campFullData->abbreviation . $query . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $callback = function() use($applicants, $checkInDates, $checkInData, $signData, $signDateTimesCols) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                if((!isset($signData) || count($signData) == 0)) {
                    if(!isset($checkInDates)) {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table));
                    }
                    else {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table), $checkInDates);  
                    }  
                }
                else {
                    if(!isset($checkInDates)) {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table), $signDateTimesCols);
                    }
                    else {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table), $checkInDates, $signDateTimesCols);  
                    }  
                }
                // 2022 一般教師營需要
                if($this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                    $pos = 44;                
                    $columns = array_merge(array_slice($columns, 0, $pos), ["lamrim" => "廣論班"], array_slice($columns, $pos));
                }
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach($columns as $key => $v){
                        // 2022 一般教師營需要
                        if($v == "廣論班" && $this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                            $lamrim = \explode("||/", $applicant->blisswisdom_type_complement)[0];
                            if(!$lamrim || $lamrim == ""){                                
                                array_push($rows, '="無"');
                            }
                            else{
                                array_push($rows, '="' . $lamrim . '"');
                            }
                            continue;
                        }
                        // 使用正規表示式抓出日期欄
                        if(preg_match('/\d\d\d\d-\d\d-\d\d/', $key)){
                            if(isset($checkInData)){
                                // 填充報到資料
                                if(in_array($applicant->sn, $checkInData[$key])){
                                    array_push($rows, '="⭕"');
                                }
                                else{
                                    array_push($rows, '="➖"');
                                }
                            }
                        }
                        elseif(str_contains($key, "SIGN_")){
                            // 填充簽到資料
                            if($signData[substr($key, 5)]['applicants']->contains($applicant->sn)){
                                array_push($rows, '="✔️"');
                            }
                            else{
                                array_push($rows, '="❌"');
                            }
                        }
                        else{       
                            array_push($rows, '="' . $applicant[$key] . '"');
                        }
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        $request->flash();
        return view('backend.registration.list')
                ->with('applicants', $applicants)
                ->with('query', $query)
                ->with('batches', $batches);
    }

    public function changeBatchOrRegion(Request $request){
        if ($request->isMethod('POST')) {
            $candidate = Applicant::find($request->id);
            $candidate->batch_id = $request->batch;
            $candidate->region = $request->region;
            $candidate->save();
            $message = "梯次 / 區域修改完成。";      
            $batches = Batch::where('camp_id', $this->campFullData->id)->get();      
            return view('backend.registration.changeBatchOrRegionForm', compact('candidate', 'message', 'batches'));
        }
        else{
            return view("backend.registration.changeBatchOrRegion");
        }
    }

    public function sendAdmittedMail(Request $request){
        if(!$request->sns){
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach($request->sns as $sn){
            \App\Jobs\SendAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "錄取通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function sendNotAdmittedMail(Request $request){
        if(!$request->sns){
            \Session::flash('error', "未選取任何人。");
            return back();
        }
        foreach($request->sns as $sn){
            \App\Jobs\SendNotAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "未錄取通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function sendCheckInMail(Request $request){
        if(!$request->sns){
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach($request->sns as $sn){
            \App\Jobs\SendCheckInMail::dispatch($sn);
        }
        \Session::flash('message', "報到通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function showGroupList() {
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch){
            $batch->regions = Applicant::select('region')->where('batch_id', $batch->id)->where('is_admitted', 1)->whereNotNull('group')->whereNotNull('number')->groupBy('region')->get();
            foreach($batch->regions as &$region){
                $region->groups = Applicant::select('group', \DB::raw('count(*) as count'))->where('batch_id', $batch->id)->where('region', $region->region)->where('is_admitted', 1)->where(function($query){
                    if($this->has_attend_data){
                        $query->where('is_attend', 1);
                    }
                })->whereNotNull('group')->whereNotNull('number')->groupBy('group')->get();
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.registration.groupList')->with('batches', $batches);
    }

    public function showNotAdmitted() {
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch){
            $batch->applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                    ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                    ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                    ->where('batch_id', $batch->id)
                    ->where(function($query){
                            // 只檢查 0
                            $query->where('is_admitted', 0);
                    })                        
                    ->orderBy('applicants.id', 'asc')
                    ->get();
        }
        return view('backend.registration.notAdmitted')->with('batches', $batches);
    }

    public function showGroup(Request $request){
        $batch_id = $request->route()->parameter('batch_id');
        $group = $request->route()->parameter('group');
        $applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
        ->join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->where('batch_id', $batch_id)->where('group', $group)
        ->where(function($query) use ($request){
            if($this->has_attend_data && !$request->showAttend){
                $query->where('is_attend', 1);
            }
        })
        ->orderBy('group', 'asc')
        ->orderBy('number', 'asc')
        ->get();
        foreach($applicants as $applicant){
            if($applicant->fee > 0){
                if($applicant->fee - $applicant->deposit <= 0){
                    $applicant->is_paid = "是";
                }
                else{
                    $applicant->is_paid = "否";
                }
            }
            else{
                $applicant->is_paid = "無費用";
            }
        }
        $applicants = $applicants->sortByDesc('is_paid');
        if(isset($request->download)){
            $fileName = $this->campFullData->abbreviation . $group . "組名單" . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            
            $template = $request->template ?? 0;

            $callback = function() use($applicants, $template) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                if($template){
                    if($this->campFullData->table == 'tcamp'){
                        $columns = ["name" => "姓名", "idno" => "身分證字號", "unit_county" => "服務單位所在縣市", "unit" => "服務單位"];
                    }
                }
                else{
                    $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table));  
                }  
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach($columns as $key => $v){
                        array_push($rows, '="' . $applicant[$key] . '"');
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($request->showAttend){
            return view('backend.in_camp.groupAttend', compact('applicants'));
        }
        return view('backend.registration.group', compact('applicants'));
    }

    public function showGroupAttendList() {
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch){
            $batch->regions = Applicant::select('region')
                ->where('batch_id', $batch->id)
                ->where('is_admitted', 1)
                ->whereNotNull('group')
                ->whereNotNull('number')
                ->groupBy('region')->get();
            foreach($batch->regions as &$region){
                $region->groups = Applicant::select('group', \DB::raw('count(*) as count, SUM(case when is_attend = 1 then 1 else 0 end) as attend_sum, SUM(case when is_attend = 0 then 1 else 0 end) as not_attend_sum'))
                    ->where('batch_id', $batch->id)
                    ->where('region', $region->region)
                    ->where('is_admitted', 1)
                    ->whereNotNull('group')
                    ->whereNotNull('number')
                    ->groupBy('group')->get();
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.in_camp.groupAttendList')->with('batches', $batches);
    }

    public function sendCheckInNotifydMail(Request $request){
        if(!$request->sns){
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach($request->sns as $sn){
            \App\Jobs\SendAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "已將產生之信件排入任務佇列。");
        return back();
    }

    public function showTrafficList() {
        $camp = $this->campFullData->table;
        $batches = $this->campFullData->batchs;
        $batch_ids = $batches->pluck('id');
        $applicants = Applicant::with($camp)->whereIn('batch_id', $batch_ids)->get();
        $trafficData = Traffic::whereIn('applicant_id', $applicants->pluck('id'))->get();
        if(!\Schema::hasColumn($camp, 'traffic_depart') && $trafficData->count() == 0) {
            return "<h1>本次營隊沒有統計交通</h1>";
        }
        $traffic_depart = Applicant::select(
                \DB::raw($camp . '.traffic_depart as traffic_depart, count(*) as count')
            )->join($camp, $camp . '.applicant_id', '=', 'applicants.id')
            ->where('traffic_depart', '<>', '自往')
            ->whereIn('batch_id', $batch_ids)
            ->groupBy($camp . '.traffic_depart')->get();
        $traffic_return = Applicant::select(
            \DB::raw($camp . '.traffic_return as traffic_return, count(*) as count')
            )->join($camp, $camp . '.applicant_id', '=', 'applicants.id')
            ->where('traffic_return', '<>', '自往')
            ->whereIn('batch_id', $batch_ids)
            ->groupBy($camp . '.traffic_return')->get();
        return view('backend.in_camp.traffic_list', compact('batches', 'applicants', 'traffic_depart', 'traffic_return', 'camp'));
    }

    public function showAttendeePhoto() {
        ini_set('max_execution_time', 1200);
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        $query = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)->withTrashed();
        $applicants = $query->get();
        if(auth()->user()->getPermission(false)->role->level <= 2){
        }
        else if(auth()->user()->getPermission(true, $this->campFullData->id)->level > 2){
            $constraint = auth()->user()->getPermission(true, $this->campFullData->id)->region;
            $batch = Batch::where('camp_id', $this->campFullData->id)->where('name', 'like', '%' . $constraint . '%')->first();
            $applicants = $applicants->filter(function ($applicant) use ($constraint, $batch) {
                if($batch){
                    return $applicant->region == $constraint || $applicant->batch_id == $batch->id;
                }
                return $applicant->region == $constraint;
            });
        }
        return view('backend.in_camp.attendeePhoto')
                ->with('applicants', $applicants)
                ->with('batches', $batches);
    }

    /**
     * todo: 下載學員名單 w/ 大頭照：先在主機端 render 好 PDF，再直接吐給客戶端
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Symfony\Component\HttpFoundation\StreamedResponse 
     * @throws \Illuminate\Contracts\Container\BindingResolutionException 
     * @throws \Psr\Container\NotFoundExceptionInterface 
     * @throws \Psr\Container\ContainerExceptionInterface 
     */
    
    public function showAccountingPage() {
        $constraints = function ($query) {
            $query->where('id', $this->camp_id);
        };
        $accountingTable = config('camps_payments.' . $this->campFullData->table . '.accounting_table');
        $accountings = Applicant::select('applicants.batch_id', 'applicants.name as aName', 'applicants.fee as shouldPay', $accountingTable . '.*', 'applicants.mobile')
            ->with(['batch', 'batch.camp' => $constraints])
            ->whereHas('batch.camp', $constraints)
            ->join($accountingTable, $accountingTable . '.accounting_no', '=', 'applicants.bank_second_barcode')
            ->orderBy($accountingTable . '.id', 'desc')->withTrashed()->get();
        $download = $_GET['download'] ?? false;
        if(!$download){
            return view('backend.registration.accounting')->with('accountings', $accountings);
        }
        else{
            $fileName = $this->campFullData->abbreviation . "銷帳資料" . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $callback = function() use($accountings) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                $columns = ["id" => "銷帳流水序號", 
                            "cbname" => "營隊梯次", 
                            "aName" => "姓名", 
                            "shouldPay" => "應繳金額", 
                            "amount" => "實繳金額", 
                            "accounting_sn" => "銷帳流水號", 
                            "accounting_no" => "銷帳編號", 
                            "paid_at" => "繳費日期", 
                            "creditted_at" => "入帳日期", 
                            "name" => "繳費管道",
                            "mobile" => "手機號碼"];    
                fputcsv($file, $columns);

                foreach ($accountings as $accounting) {
                    $rows = array();
                    foreach($columns as $key => $v){
                        if($key == "cbname"){
                            array_push($rows, '="' . $accounting->batch->camp->abbreviation . " - " . $accounting->batch->name . '"');
                        }
                        elseif($key == "shouldPay" || $key == "amount"){
                            array_push($rows, $accounting[$key]);
                        }
                        else{
                            array_push($rows, '="' . $accounting[$key] . '"');
                        }
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
    }

    public function modifyAccounting(Request $request){
        if ($request->isMethod('POST')) {
            $applicant = Applicant::find($request->id);
            $admitted_sn = $applicant->group.$applicant->number;
            if($admitted_sn == $request->double_check || $applicant->id == $request->double_check){
                $applicant->deposit = $applicant->fee;
                $applicant->save();
                $applicant = $this->applicantService->checkPaymentStatus($applicant);
                $message = "繳費完成 / 已繳金額設定完成。";
                return view("backend.modifyAccounting", compact("applicant", "message"));
            }
            else{
                $error = "報名序號錯誤。";
                $applicant = $this->applicantService->checkPaymentStatus($applicant);
                return view("backend.modifyAccounting", compact("applicant", "error"));
            }
        }
        return view("backend.findAccounting");
    }

    public function customMail(Request $request){
        return view("backend.other.customMail");
    }

    public function selectMailTarget(){
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch){
            $batch->regions = Applicant::select('region')->where('batch_id', $batch->id)->where('is_admitted', 1)->groupBy('region')->get();
            foreach($batch->regions as &$region){
                $region->groups = Applicant::select('group', \DB::raw('count(*) as count'))->where('batch_id', $batch->id)->where('region', $region->region)->where('is_admitted', 1)->groupBy('group')->get();
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.other.groupList')->with('batches', $batches);
    }

    public function writeCustomMail(Request $request){
        return view("backend.other.writeMail");
    }

    public function sendCustomMail(Request $request){
        $camp = Camp::find($request->camp_id);
        if($request->target == 'all'){ // 全體錄取人士
            $batch_ids = $camp->batchs()->pluck('id')->toArray();
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->whereNotNull(['group', 'number'])->where([['group', '<>', ''], ['number', '<>', '']])->whereIn('batch_id', $batch_ids)->get();
        }
        else if($request->target == 'batch') { // 梯次錄取人士
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->whereNotNull(['group', 'number'])->where([['group', '<>', ''], ['number', '<>', '']])->where('batch_id', $request->batch_id)->get();
        }
        else if($request->target == 'group') { // 梯次組別錄取人士
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->where('group', '=', $request->group_no)->where('batch_id', $request->batch_id)->get();
        }        
        $files = array();
        for($i  = 0; $i < 3; $i++){
            if ($request->hasFile('attachment' . $i) && $request->file('attachment' . $i)->isValid()) {
                $file = $request->file('attachment' . $i);
                $originalname = $file->getClientOriginalName();
                $fileName = time().$originalname;
                $file->storeAs('attachment', $fileName);
                $files[$i] = $fileName;
            }
        }
        foreach($receivers as $receiver){
            \Mail::to($receiver)->queue(new \App\Mail\CustomMail($request->subject, $request->content, $files, $receiver->batch->camp->variant ?? $receiver->batch->camp->table));
        }
        return view("backend.other.mailSent", ['message' => '已成功將自定郵件送入任務佇列。']);
    }
}
