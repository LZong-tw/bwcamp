<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Traits\EmailConfiguration;

class StatController extends BackendController
{
    use EmailConfiguration;

    public function appliedDateStat() {
        $applicants = Applicant::select(\DB::raw('DATE_FORMAT(applicants.created_at, "%Y-%m-%d") as date, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('date')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();
        
        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'date','label'=>'日期','type'=>'date'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            $year = (int) substr($record['date'], 0, 4);
            $month = ((int) substr($record['date'], 5, 2)) - 1;
            $day = (int) substr($record['date'], -2);
            array_push($GChartData['rows'], array('c' => array(
                array('v' => "Date($year, $month, $day)"),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);
        
        return view('backend.statistics.appliedDate', compact('GChartData',  'total'));
    }

    public function genderStat() {
        $applicants = Applicant::select(\DB::raw('applicants.gender, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('applicants.gender')->get();
        $rows = count($applicants);
        foreach($applicants as $applicant){
            $applicant = $this->applicantService->Mandarization($applicant);
        }
        $array = $applicants->toArray();

        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'gender','label'=>'性別','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['gender']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.gender', compact('GChartData',  'total'));
    }

    public function countyStat() {
        $applicants = Applicant::select(\DB::raw('SUBSTRING(applicants.address, 1, 3) as county, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('county')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();
        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'city','label'=>'縣市','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['county'] == null ? '其他' : $record['county']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.county', compact('GChartData',  'total'));
    }

    public function birthyearStat(){
        $applicants = Applicant::select(\DB::raw('CONCAT(birthyear, "(", YEAR(CURDATE()) - birthyear, "歲)") as birthyear, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('birthyear')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();
        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'birthyear','label'=>'年次(歲)','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['birthyear'] == null ? '其他' : $record['birthyear']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.birthyear', compact('GChartData',  'total'));
    }

    public function batchesStat(){
        $applicants = Applicant::select(\DB::raw('batchs.name as batch, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('batchs.name')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'batch','label'=>'梯次','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['batch'] == null ? '其他' : $record['batch']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.batches', compact('GChartData',  'total'));
    }

    public function schoolOrCourseStat(){
        $applicants = Applicant::select(\DB::raw('tcamp.school_or_course as school_or_course, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join('tcamp', 'tcamp.applicant_id', '=', 'applicants.id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy('tcamp.school_or_course')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'school_or_course','label'=>'學程','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['school_or_course'] == null ? '其他' : $record['school_or_course']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.schoolOrCourseStat', compact('GChartData',  'total'));
    }

    public function admissionStat(){
        $applicants = Applicant::select(\DB::raw('batchs.name, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->where('is_admitted', 1)
        ->groupBy('batchs.name')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'name','label'=>'梯次','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['name'] == null ? '其他' : $record['name']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.admission', compact('GChartData',  'total'));
    }

    public function checkinStat(){
        $applicants = Applicant::select(\DB::raw('check_in.check_in_date, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join('check_in', 'applicants.id', '=', 'check_in.applicant_id')
        ->where('camps.id', $this->campFullData->id)
        ->where('is_admitted', 1)
        ->groupBy('check_in_date')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'check_in_date','label'=>'日期','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['check_in_date'] == null ? '其他' : $record['check_in_date']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);
        $batches = Batch::where('camp_id', $this->campFullData->id)->get();
        foreach($batches as $batch){
            $batch_applicants = Applicant::select(\DB::raw('check_in.check_in_date, count(*) as total'))
            ->join('check_in', 'applicants.id', '=', 'check_in.applicant_id')
            ->where('batch_id', $batch->id)
            ->where('is_admitted', 1)
            ->groupBy('check_in_date')->get();
            $rows = count($batch_applicants);
            $array = $batch_applicants->toArray();

            $i = 0 ;
            $batch->total = 0 ;
            $batch_GChartData = array('cols'=> array(
                            array('id'=>'check_in_date','label'=>'日期','type'=>'string'),
                            array('id'=>'people','label'=>'人數','type'=>'number'),
                            array('id'=>'annotation','role'=>'annotation','type'=>'number')
                        ),
                        'rows' => array());
            for($i = 0; $i < $rows; $i ++) {
                $record = $array[$i];
                array_push($batch_GChartData['rows'], array('c' => array(
                    array('v' => $record['check_in_date'] == null ? '其他' : $record['check_in_date']),
                    array('v' => intval($record['total'])),
                    array('v' => intval($record['total']))
                )));
                $batch->total = $batch->total + $record['total'];
            }
            $batch->GChartData = json_encode($batch_GChartData);
        }

        return view('backend.statistics.checkin', compact('GChartData',  'total', 'batches'));
    }


    public function educationStat(){
        $str = 'education';
        if($this->campFullData == 'ycamp'){
            $str = 'system';
        }
        $applicants = Applicant::select(\DB::raw($this->campFullData->table . '.' . $str . ' as education, count(*) as total'))
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
