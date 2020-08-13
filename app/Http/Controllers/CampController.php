<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\CampDataService;
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
        return view($this->camp_data->table . '.registration');
    }


    public function campRegistrationFormSubmitted(Request $request)
    {
        //修改資料
        if(isset($request->applicant_id)){

        }
        //營隊報名
        else{
            $applicant = Applicant::join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')->where('name', $request->name)->where('email', $request->email)->first();
            if($applicant){
                return view($this->camp_data->table . '.success',
                    ['isRepeat' => true,
                    'applicant' => $applicant]);
            }
            $request->merge([
                'blisswisdom_type' => implode(', ', $request->blisswisdom_type)
            ]);
            $formData = $request->toArray();
            $formData['batch_id'] = $this->batch_id;
            // 報名程序，使用 transaction 確保可以同時將資料寫入不同的表，或確保若其中一個步驟失敗，不會留下任何敗餘的資料
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
        $applicant = Applicant::join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')->where('applicants.id', $request->sn)->where('name', $request->name)->first();
        if($applicant){
            return view($this->camp_data->table . '.registration')
                ->with('applicant_id', $applicant->id)
                ->with('applicant', $applicant->toJson());
        }
        else{
            return back()->withErrors(['找不到報名資料，請再次確認是否填寫錯誤。']);
        }
        return view();
    }
}
