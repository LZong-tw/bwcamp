<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\CampDataService;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService)
    {
        $this->campDataService = $campDataService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function campIndex($batch_id = 1)
    {
        $camp_data = Camp::getCampWithBatch($batch_id);
        dd($camp_data);
        return ;
    }

    public function campRegistration($batch_id = 1)
    {
        // 營隊基本資料
        $camp_data = $this->campDataService->getCampData($batch_id);
        $admission_announcing_date_Weekday = $camp_data['admission_announcing_date_Weekday'];
        $admission_confirming_end_Weekday = $camp_data['admission_confirming_end_Weekday'];
        $camp_data = $camp_data['camp_data'];

        return view($camp_data->table . '.registration',
            ['batch_id' => $batch_id,
            'camp_data' => $camp_data,
            'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
            'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday]);
    }


    public function campRegistrationFormSubmitted(Request $request, $batch_id)
    {
        //修改資料
        if(isset($request->applicant_id)){

        }
        //營隊報名
        else{
            $applicant = Applicant::where('name', $request->name)->where('email', $request->email)->first();
            if($applicant){
                // 營隊基本資料
                $camp_data = $this->campDataService->getCampData($batch_id);
                $admission_announcing_date_Weekday = $camp_data['admission_announcing_date_Weekday'];
                $admission_confirming_end_Weekday = $camp_data['admission_confirming_end_Weekday'];
                $camp_data = $camp_data['camp_data'];

                return view($camp_data->table . '.success',
                    ['isRepeat' => true,
                    'applicant' => $applicant,
                    'camp_data' => $camp_data,
                    'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
                    'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday]);
            }
            $request->merge([
                'blisswisdom_type' => implode(', ', $request->blisswisdom_type)
            ]);
            $formData = $request->toArray();
            $formData['batch_id'] = $batch_id;
            // 報名程序，使用 transaction 確保可以同時將資料寫入不同的表，或確保若其中一個步驟失敗，不會留下任何敗餘的資料
            // $applicant 為最終報名資料
            $applicant = \DB::transaction(function () use ($formData) {
                $applicant = Applicant::create($formData);
                $formData['applicant_id'] = $applicant->id;
                $camp = Camp::getCampTable($applicant->batch_id);
                $table = '\\App\\Models\\' . ucfirst($camp);
                $table::create($formData);

                return $applicant;
            });
        }

        // 營隊基本資料
        $camp_data = $this->campDataService->getCampData($batch_id);
        $admission_announcing_date_Weekday = $camp_data['admission_announcing_date_Weekday'];
        $admission_confirming_end_Weekday = $camp_data['admission_confirming_end_Weekday'];
        $camp_data = $camp_data['camp_data'];
        
        return view($camp_data->table . '.success',
            ['applicant' => $applicant,
            'camp_data' => $camp_data,
            'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
            'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday]);
    }
}
