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
        $camp_data = Batch::find($batch_id)?->camp;
        if (!$camp_data) {
            return "<h1>查無營隊資料</h1>";
        }
        // 錄取日期、確認參加日期資料轉換 (取得星期字串)
        $admission_announcing_date = Carbon::createFromFormat('Y-m-d', $camp_data->admission_announcing_date);
        $admission_announcing_date_Weekday = Carbon::create($admission_announcing_date)->locale(App::getLocale())->isoFormat("dddd");
        $admission_confirming_end = $camp_data->admission_confirming_end ? Carbon::createFromFormat('Y-m-d', $camp_data->admission_confirming_end) : null;
        $admission_confirming_end_Weekday = $admission_confirming_end ? Carbon::create($admission_confirming_end)->locale(App::getLocale())->isoFormat("dddd") : null;

        return [
            'camp_data' => $camp_data,
            'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
            'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday
        ];
    }

    public function checkBoxToArray($request) {
        // 各營隊客製化欄位特殊處理
        // 大專營：參加過的福智活動
        // 企業營：有興趣參加活動的類別、方便參加的時段
        // 菁英營：適合聯絡時段
        // 菁英營義工：交通方式、語言
        // 教師營：得知管道、參加過的福智活動(選項)、有興趣的主題(選項)、營隊後方便參加時間
        if(isset($request->blisswisdom_type) && is_array($request->blisswisdom_type)) {
            $request->merge([
                'blisswisdom_type' => implode("||/", $request->blisswisdom_type)
            ]);
        }
        /*
        if(isset($request->blisswisdom_type_complement)) {
            $request->merge([
                'blisswisdom_type_complement' => implode("||/", $request->blisswisdom_type_complement)
            ]);
        }*/
        if(isset($request->is_child_blisswisdommed)) {
            $request->merge([
                'is_child_blisswisdommed' => implode("||/", $request->is_child_blisswisdommed)
            ]);
        }
        if(isset($request->contact_time)) {
            $request->merge([
                'contact_time' => implode("||/", $request->contact_time)
            ]);
        }
        if(isset($request->transport)) {
            $request->merge([
                'transport' => implode("||/", $request->transport)
            ]);
        }
        if(isset($request->expertise)) {
            $request->merge([
                'expertise' => implode("||/", $request->expertise)
            ]);
        }
        if(isset($request->language)) {
            $request->merge([
                'language' => implode("||/", $request->language)
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
        if(isset($request->participation_dates)) {  //evcamp
            $request->merge([
                'participation_dates' => implode("||/", $request->participation_dates)
            ]);
        }
        if(isset($request->motivation)) {
            $request->merge([
                'motivation' => implode("||/", $request->motivation)
            ]);
        }
        if(isset($request->unit_address)) {
            if ($request->unit_subarea == "000") {
                $request->merge([
                    'unit_subarea' => $request->unit_address
                ]);
            }
            elseif ($request->unit_subarea == "999") {
                $request->merge([
                    'unit_subarea' => $request->unit_address
                ]);
            }
            else {
                $request->merge([
                    'unit_subarea' => \Str::substr($request->unit_address, 3)
                ]);
            }
        }
        if(isset($request->info_source)) {
            $request->merge([
                'info_source' => implode("||/", $request->info_source)
            ]);
        }
        if(isset($request->interesting)) {
            $request->merge([
                'interesting' => implode("||/", $request->interesting)
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
                $camps = Camp::all()->reverse();
                break;
            }
            else if($p->level >= 2 && $p->level <= 4) {
                array_push($camps, Camp::where('id', $p->camp_id)->first());
            }
        }
        return $camps;
    }

    public function handleRegion($formData, $camp, $camp_id = null){
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
            // 2022 年特殊需求：梯次即學校區域
            $special_years = [2021, 2022];
            if(in_array(Carbon::now()->year, $special_years)) {
                if($formData["region"] != "海外") {
                    $formData["batch_id"] = Batch::where('camp_id', $camp_id)->where('name', $formData["region"])->first()?->id;
                }
                else {
                    $formData["batch_id"] = Batch::where('camp_id', $camp_id)->where('name', '台北')->first()?->id;
                }
            }
        }
        else if($camp == "tcamp" && isset($formData["unit_county"])){
            $region = "";
            $north = array ("臺北市", "基隆市", "新北市", "宜蘭縣", "花蓮縣", "金門縣", "連江縣");
            $central = array ("臺中市", "彰化縣", "南投縣");
            $chiayi = array ("嘉義縣", "嘉義市", "雲林縣");
            $south = array ("高雄市", "屏東縣", "澎湖縣", "臺東縣", "南海諸島");
            $MiauLiInHsinChu = collect(["興華國中", "興華高中", "信義國小", "蟠桃國小", "建國國小", "信德國小", "新興國小", "后庄國小", "斗煥國小", "僑善國小", "尖山國小", "永貞國小", "六合國小", "頭份國小", "建國國中", "文英國中", "頭份國中", "大同高中", "君毅高中", "山佳國小", "新南國小", "竹興國小", "海口國小", "頂埔國小", "大埔國小", "照南國小", "竹南國小", "照南國中", "竹南國中", "大同國中", "君毅國中"]);

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
                elseif($MiauLiInHsinChu->first(function ($item) use ($formData) {
                    return str_contains($formData["unit"], $item);
                })) {
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
        else if($camp == "acamp"){
            $region = "";
            $taipei = array ("臺北市", "新北市", "宜蘭縣", "花蓮縣", "金門縣", "連江縣");
            $keelung = array ("基隆市", "新北市汐止區", "新北市瑞芳區", "新北市平溪區", "新北市貢寮區", "新北市雙溪區");
            $taoyuan = array ("桃園市");
            $hsinchu = array ("新竹市", "新竹縣");
            $taichung = array ("苗栗縣","臺中市", "彰化縣", "南投縣");
            $yunchia = array ("雲林縣", "嘉義市", "嘉義縣");
            $tainan = array ("臺南市");
            $kaohsiung = array ("高雄市", "屏東縣", "澎湖縣", "臺東縣", "南海諸島");

            //用「後續課程地點」來決定分區的參考地點; 「皆可」則使用「上班附近」
            if ($formData["class_location"] == "住家附近") {
                $addr = $formData["address"];
            }
            else {
                $addr = $formData["unit_address"];
            }

            //先做區域大分區
            foreach($taipei as $ele){
                if(strpos($addr, $ele) !== false) { $region = "北區"; }
            }
            //基隆要在北區後面，因為新北市有幾個區需override成基隆
            foreach($keelung as $ele){
                if(strpos($addr, $ele) !== false) { $region = "基隆"; }
            }
            foreach($taoyuan as $ele){
                if(strpos($addr, $ele) !== false) { $region = "桃區"; }
            }
            foreach($hsinchu as $ele){
                if(strpos($addr, $ele) !== false) { $region = "竹區"; }
            }
            foreach($taichung as $ele){
                if(strpos($addr, $ele) !== false) { $region = "中區"; }
            }
            foreach($yunchia as $ele){
                if(strpos($addr, $ele) !== false) { $region = "雲嘉"; }
            }
            foreach($tainan as $ele){
                if(strpos($addr, $ele) !== false) { $region = "台南"; }
            }
            foreach($kaohsiung as $ele){
                if(strpos($addr, $ele) !== false) { $region = "高屏"; }
            }

            //「北區」裡的主管/儲訓幹部/專門技術人員改成「北苑」
            if(($region == "北區") &&
                (($formData["is_manager"] == 1) || ($formData["is_cadre"] == 1) || ($formData["is_technical_staff"] == 1))) {
                $region = "北苑";
            }

            if($region == "") { $region = "其他"; }

            $formData["region"] = $region;
        }

        return $formData;
    }
}
