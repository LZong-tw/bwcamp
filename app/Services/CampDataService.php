<?
namespace App\Services;

use App\Models\Camp;
use Carbon\Carbon;
use App;

class CampDataService
{
    public function getCampData($batch_id){
        //營隊基本資料
        $camp_data = Camp::getCampWithBatch($batch_id);
        // 錄取日期、確認參加日期資料轉換 (取得星期字串)
        $admission_announcing_date = Carbon::createFromFormat('Y-m-d', $camp_data->admission_announcing_date);
        $admission_announcing_date_Weekday = Carbon::create($admission_announcing_date->format('N'))->locale(App::getLocale())->dayName;
        $admission_confirming_end = Carbon::createFromFormat('Y-m-d', $camp_data->admission_confirming_end);
        $admission_confirming_end_Weekday = Carbon::create($admission_confirming_end->format('N'))->locale(App::getLocale())->dayName;

        return [
            'camp_data' => $camp_data,
            'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
            'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday
        ];
    }

    public function checkBoxToArray($request){
        // 各營隊客製化欄位特殊處理
        // 大專營：參加過的福智活動
        if(isset($request->blisswisdom_type)){
            $request->merge([
                'blisswisdom_type' => implode(',', $request->blisswisdom_type)
            ]);
        }

        return $request;
    }

    public function getAvailableCamps($permission){
        $camps = null;
        if($permission == 1){
            $camps = Camp::all();
        }
        return $camps;
    }
}