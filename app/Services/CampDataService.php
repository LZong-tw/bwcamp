<?php
namespace App\Services;

use App\Models\Camp;
use App\Models\Batch;
use Carbon\Carbon;
use App;

class CampDataService
{
    public function getCampData($batch_id) {
        //營隊基本資料
        $camp_data = Batch::find($batch_id)->camp;
        // 錄取日期、確認參加日期資料轉換 (取得星期字串)
        $admission_announcing_date = Carbon::createFromFormat('Y-m-d', $camp_data->admission_announcing_date);
        $admission_announcing_date_Weekday = Carbon::create($admission_announcing_date->format('N'))->locale(App::getLocale())->dayName;
        $admission_confirming_end = $camp_data->admission_confirming_end ? Carbon::createFromFormat('Y-m-d', $camp_data->admission_confirming_end) : null;
        $admission_confirming_end_Weekday = $admission_confirming_end ? Carbon::create($admission_confirming_end->format('N'))->locale(App::getLocale())->dayName : null;

        return [
            'camp_data' => $camp_data,
            'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
            'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday
        ];
    }

    public function checkBoxToArray($request) {
        // 各營隊客製化欄位特殊處理
        // 大專營：參加過的福智活動
        if(isset($request->blisswisdom_type)) {
            $request->merge([
                'blisswisdom_type' => implode("||/", $request->blisswisdom_type)
            ]);
        }
        if(isset($request->blisswisdom_type_complement)) {
            $request->merge([
                'blisswisdom_type_complement' => implode("||/", $request->blisswisdom_type_complement)
            ]);
        }
        if(isset($request->is_child_blisswisdommed)) {
            $request->merge([
                'is_child_blisswisdommed' => implode("||/", $request->is_child_blisswisdommed)
            ]);
        }
        if(isset($request->favored_event)) {
            $request->merge([
                'favored_event' => implode("||/", $request->favored_event)
            ]);
        }
        if(isset($request->after_camp_available_day)) {
            $request->merge([
                'after_camp_available_day' => implode("||/", $request->after_camp_available_day)
            ]);
        }
        if(isset($request->motivation)) {
            $request->merge([
                'motivation' => implode("||/", $request->motivation)
            ]);
        }
        if(isset($request->unit_address)) {
            $request->merge([
                'unit_subarea' => \Str::substr($request->unit_address, 3)
            ]);
        }

        return $request;
    }

    /**
     * 取得該使用者擁有權限存取的營隊資料，與 \App\Http\Middleware\Permitted 功能類似
     *
     * @return \App\Models\Camp
     */
    public function getAvailableCamps($permission) {
        $camps = array();
        foreach($permission as $p){
            if($p->level == 1) {
                $camps = Camp::all();
                break;
            }
            else if($p->level >= 2 && $p->level <= 4) {
                array_push($camps, Camp::where('id', $p->camp_id)->first());
            }
        }
        return $camps;
    }

    public function handelRegion($formData, $camp, $camp_id = null){
        // 報名者分區
        if($camp == "ycamp"){
            // 大專營
            $value1 = array (
                    "",
                    "臺北市",
                    "新北市",
                    "基隆市",
                    "宜蘭縣",
                    "花蓮縣",
                    "桃園市",
                    "新竹市",
                    "新竹縣",
                    "苗栗縣",
                    "臺中市",
                    "彰化縣",
                    "南投縣",
                    "雲林縣",
                    "嘉義市",
                    "嘉義縣",
                    "臺南市",
                    "高雄市",
                    "屏東縣",
                    "臺東縣",
                    "澎湖縣",
                    "金門縣",
                    "海外"
            );
            
            $value2 = array (
                    "請選擇",
                    "臺北市",
                    "新北市",
                    "基隆市",
                    "宜蘭縣",
                    "花蓮縣",
                    "桃園市",
                    "新竹市",
                    "新竹縣",
                    "苗栗縣",
                    "臺中市",
                    "彰化縣",
                    "南投縣",
                    "雲林縣",
                    "嘉義市",
                    "嘉義縣",
                    "臺南市",
                    "高雄市",
                    "屏東縣",
                    "臺東縣",
                    "澎湖縣",
                    "金門縣",
                    "海外"
            );
            
            $value3 = array (
                    "",
                    "台北",
                    "台北",
                    "台北",
                    "台北",
                    "台北",
                    "桃園",
                    "新竹",
                    "新竹",
                    "台中",
                    "台中",
                    "台中",
                    "台中",
                    "雲嘉",
                    "雲嘉",
                    "雲嘉",
                    "台南",
                    "高雄",
                    "高雄",
                    "高雄",
                    "高雄",
                    "台北",
                    "海外"
            );
            
            for($i = 1; $i < count ( $value1 ); $i ++) {
                if ($formData["school_location"] == $value1 [$i]) {
                    $formData["region"] = $value3 [$i];
                }
            }
            
            if ($formData["school"] == '長庚大學' or $formData["school"] == '長庚科技大學林口校區' or $formData["school"] == '長庚科大' or $formData["school"] == '國立體育大學') {
                $formData["region"] = '台北';
            }
            
            if ($formData["school"] == '國立臺南藝術大學' or $formData["school"] == '台灣首府大學' or $formData["school"] == '南榮技術學院' or $formData["school"] == '敏惠醫護管理專校' or $formData["school"] == '真理大學麻豆校區') {
                $formData["region"] = '雲嘉';
            }
            // 2021 年特殊需求：梯次即學校區域
            if(Carbon::now()->year == 2021 && $formData["region"] != "海外") {
                $formData["batch_id"] = Batch::where('camp_id', $camp_id)->where('name', $formData["region"])->first()->id;
            }
        }
        else if($camp == "tcamp" && isset($formData["unit_county"])){
            $region = "";
            $north = array ("臺北市", "基隆市", "新北市", "宜蘭縣", "花蓮縣", "金門縣", "連江縣");
            $central = array ("臺中市", "彰化縣", "南投縣");
            $chiayi = array ("嘉義縣", "嘉義市", "雲林縣");
            $south = array ("高雄市", "屏東縣", "澎湖縣", "臺東縣", "南海諸島");

            foreach($north as $ele){
                if(strpos($formData["unit_county"], $ele) !== false) $region = "台北";
            }

            for($k = 0; $k < Count($central); $k++){
                if(strpos($formData["unit_county"], $central[$k]) !== false) $region = "台中";
            }

            for($l = 0; $l < Count($chiayi); $l++){
                if($formData["unit_county"] == $chiayi[$l]) $region = "雲嘉";
            }

            for($m = 0; $m < Count($south); $m++){
                if(strpos($formData["unit_county"], $south[$m]) !== false)	$region = "高雄";
            }

            if($formData["unit_county"] == "苗栗縣"){
                if(isset($formData["unit_district"]) && ($formData["unit_district"] == "頭份鎮" || $formData["unit_district"] == "竹南鎮")) {
                    $region = "新竹";
                }
                else{
                    $region = "台中";
                }
            }

            if($formData["unit_county"] == "臺南市"){
                $region = "台南";
            }
            if($formData["unit_county"] == "桃園市"){
                $region = "桃園";
            }
            if($formData["unit_county"] == "新竹縣" || $formData["unit_county"] == "新竹市"){
                $region = "新竹";
            }

            if($region == "") $region = "其他";

            $formData["region"] = $region;
        }
        else if($camp == "hcamp" && isset($formData["county"])){
            $region = "";
            $north = ["臺北市", "基隆市", "新北市", "宜蘭縣", "桃園市", "新竹縣", "新竹市"];
            $central = ["臺中市", "苗栗縣", "彰化縣", "南投縣", "雲林縣"];
            $east = ["花蓮縣", "臺東縣"];
            $south = ["高雄市", "屏東縣", "臺南市", "澎湖縣", "嘉義縣", "嘉義市", "南海諸島"];
            $kimma = ["連江縣", "金門縣"];

            foreach($north as $ele){
                if(strpos($formData["county"], $ele) !== false) { $region = "北部"; }
            }

            foreach($central as $ele){
                if(strpos($formData["county"], $ele) !== false) { $region = "中部"; }
            }

            foreach($east as $ele){
                if(strpos($formData["county"], $ele) !== false) { $region = "東部"; }
            }

            foreach($south as $ele){
                if(strpos($formData["county"], $ele) !== false) { $region = "南部"; }
            }

            foreach($kimma as $ele){
                if(strpos($formData["county"], $ele) !== false) { $region = "金馬"; }
            }

            if($region == "") { $region = "其他"; }

            $formData["region"] = $region;
        }
        // else if($camp == "ecamp"){

        // }
        return $formData;
    }
}
