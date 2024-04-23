<?
$pairs = [
    "臺北市" => "台北",
    "新北市" => "台北",
    "基隆市" => "台北",
    "宜蘭縣" => "台北",
    "花蓮縣" => "台北",
    "金門縣" => "台北",
    "連江縣" => "台北",
    "桃園市" => "桃園",
    "桃園縣" => "桃園",
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
    "臺南市" => "高區",
    "高雄市" => "高區",
    "屏東縣" => "高區",
    "澎湖縣" => "高區",
    "上海地區" => "中區",
    "港澳深圳" => "中區",
    "南海諸島" => "中區",
    "大陸其它區" => "中區",
    "其它海外" => "中區"
];
$applicants = \App\Models\Applicant::whereIn("batch_id", [165, 166])->whereNull("region")->whereNull("region_id")->get();
$applicantsEcamp = \App\Models\Ecamp::whereIn(
    "applicant_id",
    $applicants->pluck("id")
);
$regions = \App\Models\Region::all();
\DB::transaction(function () use (
    $applicants,
    $applicantsEcamp,
    $pairs,
    $regions
) {
    foreach ($applicantsEcamp as $E) {
        $applicant = $applicants->where('id', $E->applicant_id)->first();
        $str_len = str_len($E->unit_location);
        if ($str_len != 4) {
            if (!(str_contains($E->unit_location, "頭份鎮") || str_contains($E->unit_location, "竹南鎮"))) {
                $applicant->region = $pairs[$E->unit_county];
            } else {
                $applicant->region = "新竹";
            }
        }
        else {
            $applicant->region = "中區";
        }
        $applicant->save();
        $applicant->refresh();
        $applicant->region_id = $regions->where("name", $applicant->region)->first()?->id ?? null;
        $applicant->save();
    }
});
