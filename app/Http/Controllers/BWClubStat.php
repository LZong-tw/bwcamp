<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class BWClubStat extends BackendController{
    protected $taipei = array (
		"國立臺灣大學",
		"國立臺灣科技大學",
		"臺北醫學大學",
		"國立政治大學",
		"國立臺灣師範大學",
		"國立臺北教育大學",
		"臺北市立大學",
		"淡江大學",
		"國立臺北科技大學",
		"國立臺北護理健康大學",
		"長庚大學",
		"輔仁大學",
		"東吳大學",
		"國立東華大學",
		"國立臺灣海洋大學",
		"銘傳大學",
		"中國文化大學",
		"國立陽明交通大學陽明校區"
    );
    protected $taoyuan = array (
            "國立中央大學",
            "開南大學",
            "銘傳大學桃園校區",
            "中原大學",
            "中央警察大學"
    );
    protected $hsinchu = array (
            "國立陽明交通大學",
            "國立清華大學",
            "國立清華大學南大校區"
    );
    protected $taichung = array (
            "中國醫藥大學",
            "國立中興大學",
            "逢甲大學",
            "國立彰化師範大學",
            "東海大學",
            "國立臺中教育大學",
            "朝陽科技大學",
            "大葉大學",
            "中山醫學大學"
    );
    protected $yunchia = array (
            "國立雲林科技大學",
            "國立虎尾科技大學",
            "南華大學",
            "國立中正大學",
            "國立嘉義大學"
    );
    protected $tainan = array (
            "國立臺南大學",
            "國立成功大學",
            "嘉南藥理大學",
            "長榮大學"
    );
    protected $kaohsiung = array (
            // "國立高雄科技大學(原第一科大)",
            // "國立高雄科技大學(原海洋科大)",
            // "國立高雄科技大學(原應用科大)",
            "國立高雄科技大學第一校區",
            "國立高雄科技大學楠梓校區",
            "國立高雄科技大學建工校區",
            "高雄醫學大學",
            "國立高雄師範大學",
            "大仁科技大學",
            "國立屏東大學" 
    );

    protected $groups = array ();
    
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
		    "臺北" => $this->taipei,
            "桃園" => $this->taoyuan,
            "新竹" => $this->hsinchu,
            "臺中" => $this->taichung,
            "雲嘉" => $this->yunchia,
            "臺南" => $this->tainan,
            "高雄" => $this->kaohsiung
        );

        $bwclubbersBySchool = Applicant::select(\DB::raw('school, count(*) as total'))
                                ->join('ycamp', 'applicants.id', '=', 'ycamp.applicant_id')
                                ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                                ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                                ->where('camps.id', $request->camp_id)
                                ->groupBy('school')->get();

        $totals = array ();
        foreach($bwclubbersBySchool as $school){
            $totals[$school->school] = $school->total;
        }

        foreach($this->groups as $groupname => $group){
            $total = 0;
            foreach($group as $school){
                if(isset($totals[$school])){
                    $total += $totals[$school];
                }
                else{
                    $totals[$school] = 0;
                }
            }
            $totals[$groupname] = $total;
        }

        return view('backend.statistics.bwclub', [
            'groups' => $this->groups,
            'totals' => $totals
            ]);
    }
}
