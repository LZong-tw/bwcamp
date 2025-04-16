<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Traits\EmailConfiguration;

class StatController extends BackendController
{
    use EmailConfiguration;

    public function ageRangeStat(){
        //0-9,10-19 ...
        $applicants = Applicant::select(\DB::raw('CONCAT(FLOOR((YEAR(CURDATE()) - birthyear)/10)*10,"-",FLOOR((YEAR(CURDATE()) - birthyear)/10)*10+9) as agerange, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
        ->groupBy('agerange')->orderBy('agerange')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();
        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'agerange','label'=>'年齡級距','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => ($record['agerange'] == null) ? '其他' : $record['agerange']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }

        $GChartData = json_encode($GChartData);

        return view('backend.statistics.agerange', compact('GChartData',  'total'));
    }

    public function appliedDateStat() {
        $applicants = Applicant::select(\DB::raw('DATE_FORMAT(applicants.created_at, "%Y-%m-%d") as date, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
        ->groupBy('date')->withTrashed()->get();
        $rows = count($applicants);
        $array = $applicants->toArray();
        
        $i = 0 ;
        $total = 0 ;
        $GChartData = array(
                        'cols'=> array(
                            array('id'=>'date','label'=>'日期','type'=>'date'),
                            array('id'=>'people','label'=>'人數','type'=>'number'),
                            array('id'=>'annotation','role'=>'annotation','type'=>'number')
                        ),
                        'rows' => array()
                    );
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

    public function favoredEventStat(){
        $applicants = Applicant::select(\DB::raw('ecamp.favored_event as event, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join('ecamp', 'ecamp.applicant_id', '=', 'applicants.id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
        ->groupBy('event')->get();        
        $rows = count($applicants);
        $array = $applicants->toArray();

        $GChartData = array('cols'=> array(
                        array('id'=>'way','label'=>'管道','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        //split            
        $events_all = array();
        $k = 0;
        for($i = 0; $i < $rows; $i++) {
            $record = $array[$i];
            if ($record['event'] == null) continue;
            $events_split = explode("||/",$record['event']);
            $events_split_cnt = count($events_split);
            for ($j = 0; $j < $events_split_cnt; $j++) {
                $events_all[$k]['event'] = $events_split[$j];
                $events_all[$k]['total'] = $record['total'];
                $k++;
            }
        }
       
        //combined
        sort($events_all);
        $events_all_cnt = count($events_all);

        $events_list = array();
        $events_list[0] = $events_all[0];
        $j = 0;
        for($i = 1; $i < $events_all_cnt; $i++) {
            $record = $events_all[$i];
            if ($events_list[$j]['event'] == $record['event']) {   //if same, add total
                $events_list[$j]['total'] += $record['total'];
            } else {    //if diff, create item
                $j++;
                $events_list[$j] = $record;
            }
        }

        $events_list_cnt = count($events_list);
        $total = 0 ;
        for($i = 0; $i < $events_list_cnt; $i ++) {
            $record = $events_list[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['event']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }

        $GChartData = json_encode($GChartData);

        return view('backend.statistics.favoredEvent', compact('GChartData','total','rows'));
    }

    public function genderStat() {
        $applicants = Applicant::select(\DB::raw('applicants.gender, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
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
        if ($this->campFullData->table == 'acamp' && $this->campFullData->year >= 2025) {
            $applicants = Applicant::select(\DB::raw('acamp.class_county as county, count(*) as total'))
            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $this->campFullData->id)
            ->whereNull('applicants.deleted_at')
            ->groupBy('county')->get();
        } else {
            $applicants = Applicant::select(\DB::raw('SUBSTRING(applicants.address, 1, 3) as county, count(*) as total'))
            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $this->campFullData->id)
            ->whereNull('applicants.deleted_at')
            ->groupBy('county')->get();
        }
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
        ->whereNull('applicants.deleted_at')
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
        ->whereNull('applicants.deleted_at')
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

    public function regionStat(){
        $applicants = Applicant::select(\DB::raw('applicants.region, count(*) as total'))
        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
        ->groupBy('applicants.region')->get();
        $rows = count($applicants);
        foreach($applicants as $applicant){
            $applicant = $this->applicantService->Mandarization($applicant);
        }
        $array = $applicants->toArray();

        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'region','label'=>'區域','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['region']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.region', compact('GChartData',  'total'));
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

        return view('backend.statistics.schoolOrCourse', compact('GChartData',  'total'));
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
        $batches = \App\Models\Batch::where('camp_id', $this->campFullData->id)->get();
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
        if($this->campFullData->table == 'ycamp'){
            $str = 'system';
        }
        $applicants = Applicant::select(\DB::raw($this->campFullData->table . '.' . $str . ' as education, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join($this->campFullData->table, $this->campFullData->table . '.applicant_id', '=', 'applicants.id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
        ->groupBy('education')->get();
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
        } else {
            $GChartDataM = json_encode($GChartData);
            $GChartDataF = json_encode($GChartData);
        }

        return view('backend.statistics.education', compact('GChartDataAll', 'GChartDataM', 'GChartDataF', 'total'));
    }

    public function wayStat(){
        $applicants = Applicant::select(\DB::raw($table.'.way as way, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join($table, $table.'.applicant_id', '=', 'applicants.id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
        ->groupBy('ycamp.way')->get();
        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'way','label'=>'管道','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['way'] == null ? '其他' : $record['way']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.way', compact('GChartData',  'total'));
    }

    public function industryStat(){
        $table = $this->campFullData->table;

        $applicants = Applicant::select(\DB::raw($table.'.industry as industry, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join($table, $table.'.applicant_id', '=', 'applicants.id')
        ->where('camps.id', $this->campFullData->id)
        ->whereNull('applicants.deleted_at')
        ->groupBy($table.'.industry')->get();

        $rows = count($applicants);
        $array = $applicants->toArray();
        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'industry','label'=>'產業別','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['industry'] == null ? '其他' : $record['industry']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.industry', compact('GChartData',  'total'));
    }

    public function jobPropertyStat(){
        $table = $this->campFullData->table;

        $applicants = Applicant::select(\DB::raw($table.'.job_property as job_property, count(*) as total'))
        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
        ->join($table, $table.'.applicant_id', '=', 'applicants.id')
        ->where('camps.id', $this->campFullData->id)
        ->groupBy($table.'.job_property')->get();

        $rows = count($applicants);
        $array = $applicants->toArray();

        $i = 0 ;
        $total = 0 ;
        $GChartData = array('cols'=> array(
                        array('id'=>'job_property','label'=>'工作屬性','type'=>'string'),
                        array('id'=>'people','label'=>'人數','type'=>'number'),
                        array('id'=>'annotation','role'=>'annotation','type'=>'number')
                    ),
                    'rows' => array());
        for($i = 0; $i < $rows; $i ++) {
            $record = $array[$i];
            array_push($GChartData['rows'], array('c' => array(
                array('v' => $record['job_property'] == null ? '其他' : $record['job_property']),
                array('v' => intval($record['total'])),
                array('v' => intval($record['total']))
            )));
            $total = $total + $record['total'];
        }
        $GChartData = json_encode($GChartData);

        return view('backend.statistics.jobProperty', compact('GChartData',  'total'));
    }
}
