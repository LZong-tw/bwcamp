<?php
namespace App\Services;

use App\Models\Camp;
use App\Models\Batch;
use Carbon\Carbon;
use App;
use App\Models\Region;
use Illuminate\Support\Str;

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

        //從梯次取得正行開始及結束日期
        $batch_data = Batch::find($batch_id) ?? [];
        if (!$batch_data) {
            return "<h1>查無營隊資料</h1>";
        }
        // 正行開始及結束資料轉換 (取得星期字串)
        $batch_start = $batch_data->batch_start ? Carbon::createFromFormat('Y-m-d', $batch_data->batch_start) : null;
        $batch_start_Weekday = $batch_start ? Carbon::create($batch_start)->locale(App::getLocale())->isoFormat("dddd") : null;
        $batch_end = $batch_data->batch_end ? Carbon::createFromFormat('Y-m-d', $batch_data->batch_end) : null;
        $batch_end_Weekday = $batch_end ? Carbon::create($batch_end)->locale(App::getLocale())->isoFormat("dddd") : null;

        $camp_data->batch_start =  $batch_data->batch_start;
        $camp_data->batch_start_Weekday =  $batch_start_Weekday;
        $camp_data->batch_end =  $batch_data->batch_end;
        $camp_data->batch_end_Weekday =  $batch_end_Weekday;
        $camp_data->locationName =  $batch_data->locationName;
        $camp_data->location =  $batch_data->location;

        return [
            'camp_data' => $camp_data,
            'admission_announcing_date_Weekday' => $admission_announcing_date_Weekday,
            'admission_confirming_end_Weekday' => $admission_confirming_end_Weekday,
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
        if(isset($request->stay_dates)) {  //evcamp
            $request->merge([
                'stay_dates' => implode("||/", $request->stay_dates)
            ]);
        }
        if(isset($request->motivation)) {
            $request->merge([
                'motivation' => implode("||/", $request->motivation)
            ]);
        }
        //居住地址
        if(isset($request->address)) {
            if ($request->subarea == "000") {
                $request->merge([
                    'subarea' => $request->address
                ]);
            }
            elseif ($request->subarea == "999") {
                $request->merge([
                    'subarea' => $request->address
                ]);
            }
            else {
                $request->merge([
                    'subarea' => \Str::substr($request->address, 3)
                ]);
            }
        }
        //工作地址
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
        //上課地址,acamp only
        if(isset($request->class_address)) {
            if ($request->class_subarea == "000") { //其它
                $request->merge([
                    'class_subarea' => $request->class_address
                ]);
            }
            elseif ($request->unit_subarea == "999") {  //海外
                $request->merge([
                    'class_subarea' => $request->class_address
                ]);
            }
            else {
                $request->merge([
                    //'calss_subarea' => \Str::substr($request->class_address, 3)
                    'class_subarea' => $request->class_subarea_text
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
        if(($camp == "ycamp") || ($camp == "ycamp")){
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
                if ($camp == "utcamp") {
                    if ($formData["unit_county"] == $value1 [$i]) {
                        $formData["region"] = $value3 [$i];
                    }
                } else {
                    if ($formData["school_location"] == $value1 [$i]) {
                        $formData["region"] = $value3 [$i];
                    }
                }
            }

            if ($formData["school"] == '長庚大學' or $formData["school"] == '長庚科技大學林口校區' or $formData["school"] == '長庚科大' or $formData["school"] == '國立體育大學') {
                $formData["region"] = '台北';
            }

            /*if ($formData["school"] == '國立臺南藝術大學' or $formData["school"] == '台灣首府大學' or $formData["school"] == '南榮技術學院' or $formData["school"] == '敏惠醫護管理專校' or $formData["school"] == '真理大學麻豆校區') {
                $formData["region"] = '雲嘉';
            }*/
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
        else if($camp == ("tcamp") && isset($formData["unit_county"])){
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

            if (isset($formData["class_location"])) {
                //用「後續課程地點」來決定分區的參考地點; 「皆可」則使用「上班附近」
                if ($formData["class_location"] == "住家附近") {
                    $addr = $formData["address"];
                }
                else {
                    $addr = $formData["unit_address"];
                }
            } else {
                $addr = $formData["class_county"].$formData["class_subarea"];
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
            if (isset($formData["is_manager"]) && isset($formData["is_cadre"]) && isset($formData["is_technical_staff"])) {
                if(($region == "北區") &&
                    (($formData["is_manager"] == 1) || ($formData["is_cadre"] == 1) || ($formData["is_technical_staff"] == 1))) {
                    $region = "北苑";
                }
            }

            if($region == "") { $region = "其他"; }

            $formData["region"] = $region;
        }
        else if($camp == "ecamp"){
            $pairs = array (
                "臺北市" => "台北",
                "新北市" => "台北",
                "基隆市" => "台北",
                "宜蘭縣" => "台北",
                "花蓮縣" => "台北",
                "金門縣" => "台北",
                "連江縣" => "台北",
                "桃園市" => "桃園",
                "桃園縣" => "桃園", // 保留舊縣名以防萬一
                "新竹市" => "新竹",
                "新竹縣" => "新竹",
                "苗栗縣頭份鎮" => "新竹",
                "苗栗縣竹南鎮" => "新竹",
                "苗栗縣" => "中區",
                "臺中市" => "中區",
                "南投縣" => "中區",
                "彰化縣" => "中區",
                "雲林縣" => "雲嘉",
                "嘉義市" => "雲嘉",
                "嘉義縣" => "雲嘉",
                "臺東縣" => "高區",
                "臺南市" => "台南",
                "高雄市" => "高區",
                "屏東縣" => "屏東",
                "澎湖縣" => "高區",
                "上海地區" => "中區",
                "港澳深圳" => "中區",
                "南海諸島" => "中區",
                "大陸其它區" => "中區",
                "星馬地區" => "中區",
                "其它海外" => "中區"
            );

            $pairKeys = array_keys($pairs);
            // 將鍵按長度由長到短排序，確保優先配對更具體的地址
            usort($pairKeys, fn($a, $b) => strlen($b) <=> strlen($a));

            $determinedRegion = null;
            $regionFound = false;

            // 情況 1: unit_county 已設定
            if (isset($formData["unit_county"]) && $formData["unit_county"] != "") {
                // 優先處理苗栗縣頭份鎮/竹南鎮的特殊規則
                if ($formData["unit_county"] == "苗栗縣" && isset($formData["unit_subarea"]) && ($formData["unit_subarea"] == "頭份鎮" || $formData["unit_subarea"] == "竹南鎮")) {
                    $determinedRegion = "新竹";
                    $regionFound = true;
                } else {
                    // 嘗試直接匹配 unit_county
                    if (isset($pairs[$formData["unit_county"]])) {
                         $determinedRegion = $pairs[$formData["unit_county"]];
                         $regionFound = true;
                    }
                    // 如果直接匹配失敗，再嘗試用 unit_county 開頭匹配 (較少見，但作為備用)
                    if (!$regionFound) {
                        foreach ($pairKeys as $key) {
                            if (Str::startsWith($formData["unit_county"], $key)) { // 使用 Str::startsWith
                                $determinedRegion = $pairs[$key];
                                $regionFound = true;
                                break;
                            }
                        }
                    }
                }
            }
            // 情況 2: unit_county 未設定，嘗試使用 unit_location
            else if (isset($formData["unit_location"]) && $formData["unit_location"] != "") {
                foreach ($pairKeys as $key) {
                    // 檢查 unit_location 是否以 key 開頭
                    if (Str::startsWith($formData["unit_location"], $key)) { // 使用 Str::startsWith
                        $determinedRegion = $pairs[$key];
                        $regionFound = true;
                        break; // 找到第一個（最長）配對
                    }
                }
            }

            // 如果沒有找到任何配對，設定預設值
            if (!$regionFound) {
                $determinedRegion = "其他";
            }

            $formData["region"] = $determinedRegion;
        }

        // 在所有營隊邏輯之後，統一處理 region_id
        if (isset($formData["region"]) && (!isset($formData["region_id"]) || $formData["region_id"] == '')) {
            $regionModel = Region::query()->where('name', $formData["region"])->first(); // 使用 $regionModel 避免變數衝突
            $formData["region_id"] = $regionModel->id ?? null; // 如果找不到對應的 Region，設為 null
        }

        return $formData;
    }
}
