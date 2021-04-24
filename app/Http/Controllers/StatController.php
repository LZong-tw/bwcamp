<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdmittedMail;
use App\Models\CheckIn;
use Carbon\Carbon;
use View;
use App\Traits\EmailConfiguration;

class StatController extends BackendController
{
    use EmailConfiguration;
    public function educationStat(){
        $applicants = Applicant::select(\DB::raw($this->campFullData->table . '.education as education, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join($this->campFullData->table, $this->campFullData->table . '.applicant_id', '=', 'applicants.id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy($this->campFullData->table . '.education')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'education','label'=>'學程','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['education'] == null ? '其他' : $record['education']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartDataAll = json_encode($GChartData);

        if($this->campFullData->table == "hcamp"){
            $applicants = Applicant::select(\DB::raw($this->campFullData->table . '.education as education, count(*) as total'))
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->join($this->campFullData->table, $this->campFullData->table . '.applicant_id', '=', 'applicants.id')
            ->where('camps.id', $this->campFullData->id)
            ->where('applicants.gender', 'M')
            ->groupBy($this->campFullData->table . '.education')->get();
            $rows = count($applicants);
            $array = $applicants->toArray();

            $i = 0 ;
            $total = 0 ;
            $GChartData = array('cols'=> array(
                            array('id'=>'education','label'=>'學程','type'=>'string'),
                            array('id'=>'people','label'=>'人數','type'=>'number'),
                            array('id'=>'annotation','role'=>'annotation','type'=>'number')
                        ),
                        'rows' => array());
            for($i = 0; $i < $rows; $i ++) {
                $record = $array[$i];
                array_push($GChartData['rows'], array('c' => array(
                    array('v' => $record['education'] == null ? '其他' : $record['education']),
                    array('v' => intval($record['total'])),
                    array('v' => intval($record['total']))
                )));
                $total = $total + $record['total'];
            }
            $GChartDataM = json_encode($GChartData);

            $applicants = Applicant::select(\DB::raw($this->campFullData->table . '.education as education, count(*) as total'))
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->join($this->campFullData->table, $this->campFullData->table . '.applicant_id', '=', 'applicants.id')
            ->where('camps.id', $this->campFullData->id)
            ->where('applicants.gender', 'M')
            ->groupBy($this->campFullData->table . '.education')->get();
            $rows = count($applicants);
            $array = $applicants->toArray();

            $i = 0 ;
            $total = 0 ;
            $GChartData = array('cols'=> array(
                            array('id'=>'education','label'=>'學程','type'=>'string'),
                            array('id'=>'people','label'=>'人數','type'=>'number'),
                            array('id'=>'annotation','role'=>'annotation','type'=>'number')
                        ),
                        'rows' => array());
            for($i = 0; $i < $rows; $i ++) {
                $record = $array[$i];
                array_push($GChartData['rows'], array('c' => array(
                    array('v' => $record['education'] == null ? '其他' : $record['education']),
                    array('v' => intval($record['total'])),
                    array('v' => intval($record['total']))
                )));
                $total = $total + $record['total'];
            }
            $GChartDataF = json_encode($GChartData);
        }

        return view('backend.statistics.education', compact('GChartDataAll', 'GChartDataM', 'GChartDataF', 'total'));
    }
}
