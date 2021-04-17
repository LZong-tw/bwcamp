<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Batch;
use App\Models\Applicant;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ApplicantMail;
use View;
use App\Traits\EmailConfiguration;

class CampController extends Controller
{
    use EmailConfiguration;

    protected $campDataService, $applicantService, $batch_id, $camp_data, $admission_announcing_date_Weekday, $admission_confirming_end_Weekday;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService, ApplicantService $applicantService,  Request $request) {
        $this->applicantService = $applicantService;
        $this->campDataService = $campDataService;
        // 營隊資料，存入 view 全域
        $this->batch_id = $request->route()->parameter('batch_id');
        $this->camp_data = $this->campDataService->getCampData($this->batch_id);
        $admission_announcing_date_Weekday = $this->camp_data['admission_announcing_date_Weekday'];
        $admission_confirming_end_Weekday = $this->camp_data['admission_confirming_end_Weekday'];
        $this->camp_data = $this->camp_data['camp_data'];
        View::share('batch_id', $this->batch_id);
        View::share('camp_data', $this->camp_data);
        View::share('admission_announcing_date_Weekday', $admission_announcing_date_Weekday);
        View::share('admission_confirming_end_Weekday', $admission_confirming_end_Weekday);
        // 動態載入電子郵件設定
        $this->setEmail($this->camp_data->table);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function campIndex() {
        if($this->camp_data->site_url){
            return redirect()->to($this->camp_data->site_url);
        }
        $now = Carbon::now();
        $registration_start = Carbon::createFromFormat('Y-m-d', $this->camp_data->registration_start);
        if($now->lt($registration_start)){
            return '<div style="margin: atuo;">距離開始報名日，還有 <br><img src="http://s.mmgo.io/t/B7Aj" alt="motionmailapp.com" /></div>';
        }
        
    }

    public function campRegistration(Request $request) {        
        $today = \Carbon\Carbon::today();
        if($this->camp_data->is_late_registration_end){
            $registration_end = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $this->camp_data->late_registration_end . "23:59:59");
        }
        else{
            $registration_end = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $this->camp_data->registration_end . "23:59:59");
        }  
        $final_registration_end = $this->camp_data->final_registration_end ? \Carbon\Carbon::createFromFormat("Y-m-d", $this->camp_data->final_registration_end)->endOfDay() : \Carbon\Carbon::today();
        if($today > $registration_end && !isset($request->isBackend)){
            return view('camps.' . $this->camp_data->table . '.outdated');
        }
        elseif(isset($request->isBackend) && $today > $final_registration_end){
            return view('camps.' . $this->camp_data->table . '.outdated')->with('isBackend', '超出最終報名日。');
        }
        else{
            return view('camps.' . $this->camp_data->table . '.form')
                    ->with('isBackend', $request->isBackend)
                    ->with('batch', Batch::find($request->batch_id));
        }
    }


    public function campRegistrationFormSubmitted(Request $request) {
        // 檢查電子郵件是否一致
        if($request->email != $request->emailConfirm){
            return view("errorPage")->with('error', '電子郵件不一致，請檢查是否輸入錯誤。');
        }
        // 修改資料
        if(isset($request->applicant_id)){
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
            $formData = $this->campDataService->handelRegion($formData, $this->camp_data->table);
            $applicant = \DB::transaction(function () use ($formData) {
                $applicant = Applicant::where('id', $formData['applicant_id'])->first();
                $model = '\\App\\Models\\' . ucfirst($this->camp_data->table);
                $camp = $model::where('applicant_id', $formData['applicant_id'])->first();
                $applicantFillable = $applicant->getFillable();
                $campFillable = $camp->getFillable();
                $applicantData = array();
                $campData = array();
                foreach($formData as $key => $value){
                    if(in_array($key, $applicantFillable)){
                        $applicantData[$key] = $value;
                    }
                    if(in_array($key, $campFillable)){
                        $campData[$key] = $value;
                    }
                }
                $applicant->where('id', $formData['applicant_id'])->update($applicantData);
                $applicant->save();
                $camp->where('applicant_id', $formData['applicant_id'])->update($campData);
                $camp->save();

                return $applicant;
            });
            return view('camps.' . $this->camp_data->table . '.modifyingSuccessful', ['applicant' => $applicant]);
        }
        // 營隊報名
        else{
            $applicant = Applicant::select('applicants.*')
                ->join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')
                ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                ->where('camps.id', $this->camp_data->id)
                ->where('batch_id', $this->batch_id)
                ->where('applicants.name', $request->name)
                ->where('email', $request->email)
                ->withTrashed()->first();
            if($applicant){
                if($applicant->trashed()){
                    $applicant->restore();
                }
                return view('camps.' . $this->camp_data->table . '.success',
                    ['isRepeat' => "已成功報名，請勿重複送出報名資料。",
                    'applicant' => $applicant]);
            }
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
            $formData['batch_id'] = $this->batch_id;
            $formData = $this->campDataService->handelRegion($formData, $this->camp_data->table);
            // 報名資料開始寫入資料庫，使用 transaction 確保可以同時將資料寫入不同的表，
            // 或確保若其中一個步驟失敗，不會留下任何殘餘、未完整的資料（屍體）
            // $applicant 為最終報名資料
            $controller = $this;
            $applicant = \DB::transaction(function () use ($formData, $controller) {
                $applicant = Applicant::create($formData);
                $formData['applicant_id'] = $applicant->id;
                $model = '\\App\\Models\\' . ucfirst($this->camp_data->table);
                $model::create($formData);       
                if($controller->camp_data->table == 'hcamp'){
                    $applicant = $controller->applicantService->fillPaymentData($applicant);
                    $applicant->save();
                }
                return $applicant;
            });     
            // 寄送報名資料
            Mail::to($applicant)->send(new ApplicantMail($applicant, $this->camp_data));
        }
        
        return view('camps.' . $this->camp_data->table . '.success')->with('applicant', $applicant);
    }

    public function campQueryRegistrationDataPage() {
        return view('camps.' . $this->camp_data->table . '.query');
    }

    /**
     * 查詢/修改報名資料
     * 如果從 query 頁選擇查詢報名資料，則可跨梯次查詢資料，但無法再按下修改資料
     * 如果從 query 頁選擇修改報名資料，則可跨梯次修改資料
     * 
     */
    public function campViewRegistrationData(Request $request) {
        $applicant = null;
        $isModify = false;
        $campTable = $this->camp_data->table;
        if($request->name != null && $request->sn != null) {
            $applicant = Applicant::select('applicants.*', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)
                ->where('name', $request->name)->withTrashed()->first();
        }
        // 只使用報名 ID（報名序號）查詢資料，儘開放有限的存取
        //（因會有個資洩漏的疑慮，故只在檢視報名資料及報名資料送出後的畫面允許使用）
        // 唯三允許進入修改資料的來源：兩個地方（報名、報名資料修改）的報名資料送出後
        //                        及檢視報名資料頁面所進來的請求
        else if(Str::contains(request()->headers->get('referer'), 'submit') ||
                Str::contains(request()->headers->get('referer'), 'queryupdate') ||
                Str::contains(request()->headers->get('referer'), 'queryview')){
            $applicant = Applicant::select('applicants.*', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)->withTrashed()->first();
        }
        if($request->isModify) {
            $isModify = true;
        }
        if($applicant) {
            // 取得報名者梯次資料
            $camp = $applicant->batch->camp;
            $applicant_data = $applicant->toJson();
            $applicant_data = str_replace("\\r", "", $applicant_data);
            $applicant_data = str_replace("\\n", "", $applicant_data);
            $applicant_data = str_replace("\\t", "", $applicant_data);
            if($camp->modifying_deadline){
                $modifying_deadline = Carbon::createFromFormat('Y-m-d', $camp->modifying_deadline);
            }
            else{
                $modifying_deadline = Carbon::now();
            }
            if($isModify && $modifying_deadline->lt(Carbon::today())){
                if(!Str::contains(request()->headers->get('referer'), 'queryview')){
                    return back()->withInput()->withErrors(['很抱歉，報名資料修改期限已過。']);
                }
                else{
                    return view('camps.' . $campTable . '.form')
                            ->with('applicant_id', $applicant->applicant_id)
                            ->with('applicant_batch_id', $applicant->batch_id)
                            ->with('applicant_data', $applicant_data)
                            ->with('applicant_raw_data', $applicant)
                            ->with('isModify', false)
                            ->with('isBackend', $request->isBackend)
                            ->with('batch', Batch::find($request->batch_id))
                            ->with('camp_data', $camp)
                            ->withErrors(['很抱歉，報名資料修改期限已過。']);
                }
            }
            return view('camps.' . $campTable . '.form')
                ->with('applicant_id', $applicant->applicant_id)
                ->with('applicant_batch_id', $applicant->batch_id)
                ->with('applicant_data', $applicant_data)
                ->with('applicant_raw_data', $applicant)
                ->with('isModify', $isModify)
                ->with('isBackend', $request->isBackend)
                ->with('batch', Batch::find($request->batch_id))
                ->with('camp_data', $camp);
        }
        else{
            return back()->withInput()->withErrors(['找不到報名資料，請再次確認是否填寫錯誤。']);
        }
    }

    public function campGetApplicantSN(Request $request) {
        $campTable = $this->camp_data->table;
        $applicant = Applicant::select('applicants.id', 'applicants.email', 'applicants.name', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.name', $request->name)
                ->where('birthyear', ltrim($request->birthyear, '0'))
                ->where('birthmonth', ltrim($request->birthmonth, '0'));
        if($campTable == 'acamp'){
            $applicant = $applicant->withTrashed()->first();
        }
        else{
            $applicant = $applicant->where('birthday', ltrim($request->birthday, '0'))
            ->withTrashed()->first();
        }
        if($applicant) {
            // 寄送報名序號
            Mail::to($applicant)->send(new ApplicantMail($applicant, $this->camp_data, true));
            return view('camps.' . $campTable . '.getSN')
                ->with('applicant', $applicant);
        }
        else{
            return view('camps.' . $campTable . '.getSN')
                ->with('error', "找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。");
        }
    }

    public function campViewAdmission() {
        return view('camps.' . $this->camp_data->table . ".queryadmission");
    }

    public function campConfirmCancel(Request $request) {
        $applicant = Applicant::where('id', $request->sn)
                        ->where('name', $request->name)
                        ->where('idno', $request->idno)
                        ->withTrashed()
                        ->first();
        if($applicant) {
            return view('camps.' . $this->camp_data->table . '.confirm_cancel', compact('applicant'));
        }
        else{
            return back()->withInput()->withErrors(["找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。"]);
        }
    }

    public function campCancellation(Request $request) {
        if(Applicant::find($request->sn)->delete()){
            return view('camps.' . $this->camp_data->table . '.cancel_successful');
        }
        return "<h2>取消時發生未預期錯誤，請向主辦方回報。</h2>";
    }

    public function restoreCancellation(Request $request) {
        if(Applicant::withTrashed()->find($request->sn)->restore()){
            $applicant = Applicant::find($request->sn);
            return view('camps.' . $this->camp_data->table . '.restore_successful', compact('applicant'));
        }
        return "<h2>回復時發生未預期錯誤，請向主辦方回報。</h2>";
    }

    public function campQueryAdmission(Request $request) {
        $campTable = $this->camp_data->table;
        $applicant = null;
        if($request->name != null && $request->sn != null) {
            $applicant = Applicant::select('applicants.*', $campTable . '.*', 'applicants.id as applicant_id')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)
                ->where('name', $request->name)
                ->withTrashed()->first();
        }
        if($applicant) {
            $applicant = $this->applicantService->checkPaymentStatus($applicant);
            return view('camps.' . $campTable . ".admissionResult")->with('applicant', $applicant);
        }
        else{
            return back()->withInput()->withErrors(["找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。"]);
        }
    }

    public function showDownloads() {
        return view('camps.' . $this->camp_data->table . '.downloads');
    }

    public function downloadPaymentForm(Request $request) {
        ini_set('memory_limit', -1);        
        $applicant = Applicant::find($request->applicant_id);
        $this->applicantService->checkIfPaidEarlyBird($applicant);
        return \PDF::loadView('camps.' . $this->camp_data->table . '.paymentFormPDF', compact('applicant'))->download(\Carbon\Carbon::now()->format('YmdHis') . $this->camp_data->table . $applicant->id . '繳費聯.pdf');
    }

    public function downloadCheckInNotification(Request $request) {
        $applicant = Applicant::find($request->applicant_id);
        return \PDF::loadView('camps.' . $this->camp_data->table . '.checkInMail', compact('applicant'))->download(\Carbon\Carbon::now()->format('YmdHis') . $this->camp_data->table . $applicant->id . '報到通知單.pdf');
    }

    public function downloadCheckInQRcode(Request $request) {
        $applicant = Applicant::find($request->applicant_id);
        $qr_code = \DNS2D::getBarcodePNG('{"applicant_id":' . $applicant->id . '}', 'QRCODE');
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($applicant->batch->camp->fullName . ' QR code 報到單<br>梯次：' . $applicant->batch->name . '<br>錄取序號：' . $applicant->group . $applicant->number . '<br>姓名：' . $applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
        return $pdf->download(\Carbon\Carbon::now()->format('YmdHis') . $this->camp_data->table . $applicant->id . 'QR Code 報到單.pdf');
    }
}
