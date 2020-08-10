<?
namespace App\Services;

use App\Models\Camp;
use Carbon\Carbon;
use App;

class CampDataService
{
    public function getCampData($batch_id)
    {
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
}