<?php

namespace App\Http\Controllers;

use App\Applicant as AppApplicant;
use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Ycamp;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
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
        //營隊基本資料
        $camp_data = Camp::getCampWithBatch($batch_id);
        // 錄取日期、確認參加日期資料轉換 (取得星期字串)
        $admission_announcing_date = \Carbon\Carbon::createFromFormat('Y-m-d', $camp_data->admission_announcing_date);
        $admission_announcing_date_Weekday = \Carbon\Carbon::create($admission_announcing_date->format('N'))->locale(\App::getLocale())->dayName;
        $admission_confirming_end = \Carbon\Carbon::createFromFormat('Y-m-d', $camp_data->admission_confirming_end);
        $admission_confirming_end_Weekday = \Carbon\Carbon::create($admission_confirming_end->format('N'))->locale(\App::getLocale())->dayName;

        return view($camp_data->table . '.registration',
            ['batch_id' => $batch_id,
            'camp_data' => $camp_data,
            'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
            'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday]);
    }

    // public function campRegistrationPreview(Request $request)
    // {
    //     //營隊基本資料
    //     $camp_data = Camp::getCampWithBatch($request->batch_id);
    //     // 錄取日期、確認參加日期資料轉換 (取得星期字串)
    //     $admission_announcing_date = \Carbon\Carbon::createFromFormat('Y-m-d', $camp_data->admission_announcing_date);
    //     $admission_announcing_date_Weekday = \Carbon\Carbon::create($admission_announcing_date->format('N'))->locale(\App::getLocale())->dayName;
    //     $admission_confirming_end = \Carbon\Carbon::createFromFormat('Y-m-d', $camp_data->admission_confirming_end);
    //     $admission_confirming_end_Weekday = \Carbon\Carbon::create($admission_confirming_end->format('N'))->locale(\App::getLocale())->dayName;

    //     return view($camp_data->table . '.registration',
    //         ['batch_id' => $request->batch_id,
    //         'camp_data' => $camp_data,
    //         'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
    //         'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday,
    //         'registration_data' => $request]);
    // }

    public function campRegistrationFormSubmitted(Request $request, $batch_id)
    {
        //修改資料
        if(isset($request->applicant_id)){

        }
        //營隊報名
        else{
            $count = Applicant::where('name', $request->name)->where('email', $request->email)->count();
            if($count > 0){
                return "<h1>重複報名。</h1>";
            }
            $request->merge([
                'blisswisdom_type' => implode(', ', $request->blisswisdom_type)
            ]);
            $formData = $request->toArray();
            $formData['batch_id'] = $batch_id;
            \DB::transaction(function () use ($formData) {
                $applicant = Applicant::create($formData);
                $formData['applicant_id'] = $applicant->id;
                Ycamp::create($formData);
            });
        }
        return "<h1>報名完成。</h1>";
    }
}
