<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class BWNSchoolStat extends BackendController
{
    protected $ntu = array(
        "國立臺灣大學",
        "國立臺灣科技大學",
        "臺北醫學大學"
    );
    protected $shifu = array(
        "國立臺灣師範大學",
        "國立臺北教育大學",
        "臺北市立大學",
        "佛光大學",
        "國立宜蘭大學",
        "國立金門大學",
        "輔仁大學",
        "國立臺灣藝術大學",
        "亞東技術學院",
        "致理科技大學",
        "醒吾科技大學",
        "明志科技大學",
        "宏國德霖科技大學",
        "長庚大學",
        "長庚科技大學",
        "國立體育大學",
        "跨區越南生"
    );
    protected $peitam = array(
        "淡江大學",
        "國立臺北科技大學",
        "國立臺北護理健康大學",
        "中國文化大學",
        "國立臺北大學",
        "國防大學管理學院",
        "國立臺北藝術大學",
        "真理大學",
        "國立陽明交通大學臺北校區",
        //"馬偕醫護管理專科學校",
        "馬偕醫學院",
        "馬偕醫護管理專科學校",
        "聖約翰科技大學",
        "國立臺北商業大學",
        "臺北城市科技大學",
        "台北海洋科技大學",
        "耕莘健康管理專科學校"
    );
    protected $chengdong = array(
        "國立政治大學",
        "東吳大學",
        "國立臺灣海洋大學",
        "銘傳大學臺北校區",
        "中國科技大學",
        "世新大學",
        "大同大學",
        "實踐大學臺北校區",
        "中華科技大學",
        "景文科技大學",
        "東南科技大學",
        "康寧大學",
        "德明財經科技大學",
        "臺灣警察專科學校",
        "經國管理暨健康學院",
        "國立臺灣戲曲學院",
        "華梵大學"
    );
    protected $hualien = array(
        "國立東華大學",
        "慈濟大學",
        "慈濟科技大學"
    );
    protected $other = array(
        "海外",
        "高中",
        "其他"
    );

    protected $groups = array();

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        $this->groups = array(
            "台大" => $this->ntu,
            "師輔" => $this->shifu,
            "北淡" => $this->peitam,
            "政東" => $this->chengdong,
            "花蓮" => $this->hualien,
            "其他" => $this->other,
        );

        $bwclubbersBySchool = Applicant::select(\DB::raw('school, count(*) as total'))
                                ->join('ycamp', 'applicants.id', '=', 'ycamp.applicant_id')
                                ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                                ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                                ->where('camps.id', $request->camp_id)
                                ->groupBy('school')->get();

        $totals = array();
        foreach ($bwclubbersBySchool as $school) {
            $totals[$school->school] = $school->total;
        }

        foreach ($this->groups as $groupname => $group) {
            $total = 0;
            foreach ($group as $school) {
                if (isset($totals[$school])) {
                    $total += $totals[$school];
                } else {
                    $totals[$school] = 0;
                }
            }
            $totals[$groupname] = $total;
        }
        $title1 = "北區學校統計";
        $title2 = "校群";
        return view('backend.statistics.bwclubschool', [
            'title1' => $title1,
            'title2' => $title2,
            'groups' => $this->groups,
            'totals' => $totals
        ]);
    }
}
