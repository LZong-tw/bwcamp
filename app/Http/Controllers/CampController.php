<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\CampDataService;
use Illuminate\Support\Arr;
use View;

class CampController extends Controller
{
    protected $campDataService, $batch_id, $camp_data, $admission_announcing_date_Weekday, $admission_confirming_end_Weekday;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService,  Request $request)
    {
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function campIndex()
    {
        dd($this->camp_data);
        return ;
    }

    public function campRegistration()
    {
        return view($this->camp_data->table . '.form');
    }


    public function campRegistrationFormSubmitted(Request $request)
    {
        // 檢查電子郵件是否一致
        if($request->email != $request->emailConfirm){
            return view("errorPage")->with('error', '電子郵件不一致，請檢查是否輸入錯誤。');
        }
        // 修改資料
        if(isset($request->applicant_id)){
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
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
            return view($this->camp_data->table . '.modifyingSuccessful', ['applicant' => $applicant]);
        }
        // 營隊報名
        else{
            $applicant = Applicant::select('applicants.*')->join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')->where('name', $request->name)->where('email', $request->email)->first();
            if($applicant){
                return view($this->camp_data->table . '.success',
                    ['isRepeat' => "已成功報名，請勿重複送出報名資料。",
                    'applicant' => $applicant]);
            }
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
            $formData['batch_id'] = $this->batch_id;
            // 報名資料開始寫入資料庫，使用 transaction 確保可以同時將資料寫入不同的表，
            // 或確保若其中一個步驟失敗，不會留下任何殘餘、未完整的資料（屍體）
            // $applicant 為最終報名資料
            $applicant = \DB::transaction(function () use ($formData) {
                $applicant = Applicant::create($formData);
                $formData['applicant_id'] = $applicant->id;
                $model = '\\App\\Models\\' . ucfirst($this->camp_data->table);
                $model::create($formData);
                return $applicant;
            });
        }
        
        return view($this->camp_data->table . '.success')->with('applicant', $applicant);
    }

    public function campQueryRegistrationDataPage(){
        return view($this->camp_data->table . '.query');
    }

    public function campViewRegistrationData(Request $request){
        $applicant = null;
        $isModify = false;
        $campTable = $this->camp_data->table;
        if($request->name != null && $request->sn != null){
            $applicant = Applicant::select('applicants.*', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)
                ->where('name', $request->name)->first();
        }
        // 只使用報名 ID（報名序號）查詢資料，儘開放有限的存取
        //（因會有個資洩漏的疑慮，故只在檢視報名資料及報名資料送出後的畫面允許使用）
        // 唯三允許進入修改資料的來源：兩個地方（報名、報名資料修改）的報名資料送出後
        //                        及檢視報名資料頁面所進來的請求
        else if(request()->headers->get('referer') == route('formSubmit', $this->batch_id) ||
                request()->headers->get('referer') == route('queryupdate', $this->batch_id) ||
                request()->headers->get('referer') == route('queryview', $this->batch_id)){
            $applicant = Applicant::select('applicants.*', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)->first();
        }
        if($request->isModify){
            $isModify = true;
        }
        if($applicant){
            return view($campTable . '.form')
                ->with('applicant_id', $applicant->applicant_id)
                ->with('applicant_batch_id', $applicant->batch_id)
                ->with('applicant_data', $applicant->toJson())
                ->with('isModify', $isModify);
        }
        else{
            return back()->withInput()->withErrors(['找不到報名資料，請再次確認是否填寫錯誤。']);
        }
    }

    public function campGetApplicantSN(Request $request){
        $campTable = $this->camp_data->table;
        $applicant = Applicant::select('applicants.id', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.name', $request->name)
                ->where('birthyear', $request->birthyear)
                ->where('birthmonth', $request->birthmonth)
                ->where('birthday', $request->birthday)
                ->first();
        if($applicant){
            return view($campTable . '.getSN')
                ->with('applicant_id', $applicant->id);
        }
        else{
            return view($campTable . '.getSN')
                ->with('error', "找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。");
        }
    }

    public function campViewAdmission(){
        return view($this->camp_data->table . ".queryadmission");
    }

    public function campQueryAdmission(Request $request){
        $campTable = $this->camp_data->table;
        $applicant = null;
        if($request->name != null && $request->sn != null){
            $applicant = Applicant::select('applicants.*', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)
                ->where('name', $request->name)->first();
        }
        if($applicant){
            return view($campTable . ".admissionResult")->with('applicant', $applicant);
        }
        else{
            return back()->withInput()->with('error', "找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。");
        }
    }
}
