<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApplicantService;
use App\Services\GSheetService;
use App\Models\Applicant;
use App\Models\Camp;
use App\Models\CheckIn;
use App\Models\Vcamp;
use App\Models\Lodging;


class SheetController extends Controller
{
    protected $gsheetservice;
    protected $applicantService;
    /**
     * 建構子
     */
    public function __construct(
        GSheetService $gsheetservice,
        ApplicantService $applicantService
    ) {
        $this->gsheetservice = $gsheetservice;
        $this->applicantService = $applicantService;
    }

    public function Sheet()
    {
        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        dd($sheets);
    }

    public function AddSheet()
    {
        $data = ['2020-4-19', '3000', '2000', '', 'test'];
        $sheets = $this->gsheetservice->Append(config('google.post_spreadsheet_id'), config('google.post_sheet_id'), $data);
        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        dd($sheets);
    }

    public function showGSFeedback(Request $request)
    {
        if ($request->day==1) {
            config([
                'google.post_spreadsheet_id' => '1Bdnv5ehYLCYv_8RYhtbqVS2rseOuoxdaVvP2Ehi6egM',
                'google.post_sheet_id' => '表單回應 1',
            ]);
        } elseif ($request->day==2) {
            config([
                'google.post_spreadsheet_id' => '1JCeg9KBNM4jQXDjPP-Zi0kcuJogkYc9CajK-53IQCeU',
                'google.post_sheet_id' => '表單回應 1',
            ]);
        } else {
            config([
                'google.post_spreadsheet_id' => '10QLfLM2nbJcfB58mJ2TscRHtiJetYx47vi8bEETYens',
                'google.post_sheet_id' => '表單回應 1',
            ]);
        }

        $applicant = Applicant::find($request->applicant_id);
        $name_tg = $applicant->name;    //target name

        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        $titles = $sheets[0];

        //multiple name columns
        $keys = array_keys($titles, '姓名');
        
        foreach($keys as $key) {
            $i = 0;
            foreach ($sheets as $row) {
                $names[$i] = $row[$key];
                $i = $i+1;
            }
            $key1 = array_search($name_tg, $names);
            if ($key1 <> false) break;
        }
        
        if ($key1 == false) {
            $contents = null;
            $content_count = 0;
        }
        else {
            //to deal with content_count < title_count
            $contents = $sheets[$key1];
            $content_count = count($contents);
        }

        return view('backend.in_camp.gsFeedback', compact('titles','contents','content_count'));
    }

    public function showGSDynamic(Request $request)
    {
        config([
            'google.post_spreadsheet_id' => '19lb0N_TQXtlTp4wzj_E2Q9maYIZppU23dceA_GAPMKQ',
            'google.post_sheet_id' => 'URLs',
        ]);

        $applicant_id = $request->applicant_id;

        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        $titles = $sheets[0];
        dd($sheets);

        //multiple name columns
        $keys = array_keys($titles, '姓名');
        
        foreach($keys as $key) {
            $i = 0;
            foreach ($sheets as $row) {
                $names[$i] = $row[$key];
                $i = $i+1;
            }
            $key1 = array_search($name_tg, $names);
            if ($key1 <> false) break;
        }
        
        if ($key1 == false) {
            $contents = null;
            $content_count = 0;
        }
        else {
            //to deal with content_count < title_count
            $contents = $sheets[$key1];
            $content_count = count($contents);
        }

        return view('backend.in_camp.gsFeedback', compact('titles','contents','content_count'));
    }

    public function importGSApplicants(Request $request)
    {
        config([
            //'google.post_spreadsheet_id' => '1g6gvbuLeEXz8W4QtMLPGhMpZ_u_Mu73OfmR3ems_9SI',
            //'google.post_sheet_id' => '0821',
            'google.post_spreadsheet_id' => '1qYHSDQWz4tBfgB-clCFnbbyMA67EJnK6rVpKZbvDPDY',
            'google.post_sheet_id' => 'ABTeam',
        ]);
        $camp = Camp::find($request->camp_id);
        $table = $camp->table;

        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        $titles = $sheets[0];
        $num_cols = count($titles);
        $num_rows = count($sheets);

        $success_count = 0;
        $fail_count = 0;
        for ($i=1; $i<$num_rows; $i++) {
            $data = $sheets[$i];
            for ($j=0; $j<$num_cols; $j++) {
                $title_data[$titles[$j]] = $data[$j];
            }
            $applicant = Applicant::select('applicants.*')
                ->where('batch_id', $title_data['batch_id'])
                ->where('name', $title_data['name'])
                //->where('email', $title_data['email'])
                ->first();

            if ($applicant) {   //if exist, update
                //$applicant->group_id = $title_data['group_id'];
                //$applicant->region = $title_data['region'];
                //$applicant->save();
                $fail_count++;
            } else {            //create new
                $applicant = \DB::transaction(function () use ($title_data,$table) {
                    $applicant = Applicant::create($title_data);
                    $title_data['applicant_id'] = $applicant->id;
                    $model = '\\App\\Models\\' . ucfirst($table);
                    $model::create($title_data);
                    return $applicant;
                });
                $success_count++;
            }
            if ($i % 500 == 0) {
                sleep(5);
                //dd($fail_count);
            }
        }
        $stat['success'] = $success_count;
        $stat['fail'] = $fail_count;
        dd($stat);
        //return view('backend.in_camp.gsFeedback', compact('titles','contents','content_count'));
    }

    public function exportGSApplicants(Request $request)
    {
        $camp = Camp::find($request->camp_id);
        $table = $camp->table;
        if ($camp->is_vcamp()) {
            $vcamp = Vcamp::find($request->camp_id);
            $main_camp_id = $vcamp->mainCamp->id;
        } else {
            $main_camp_id = null;
        }

        if ($table == 'ecamp') {
            config([
                //ecamp
                'google.post_spreadsheet_id' => '1ihb-bcwwW8JItIyH692YniCJ03yyuqonXOseObExlvc',
                'google.post_sheet_id' => 'ecamp',
            ]);
        } else if ($table == 'evcamp') {
            config([
                //evcamp
                'google.post_spreadsheet_id' => '1ihb-bcwwW8JItIyH692YniCJ03yyuqonXOseObExlvc',
                'google.post_sheet_id' => 'evcamp',
            ]);
        } else if ($table == 'ceocamp') {
            config([
                //evcamp
                'google.post_spreadsheet_id' => '1GUvMO-GDdbfq3gVDHUMt_HTcEsj3dNFir5dO5KlnAGQ',
                'google.post_sheet_id' => 'ceocamp',
            ]);
        } else if ($table == 'ceovcamp'){
            config([
                //ceocamp
                'google.post_spreadsheet_id' => '1GUvMO-GDdbfq3gVDHUMt_HTcEsj3dNFir5dO5KlnAGQ',
                'google.post_sheet_id' => 'ceovcamp',
            ]);
        } else {
            exit(1);
        }
        
        //$sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));

        $applicants = Applicant::select('applicants.*', $table . '.*')
        ->join($table, 'applicants.id', '=', $table . '.applicant_id')
        ->join('batchs','applicants.batch_id', '=', 'batchs.id')
        ->join('camps', 'batchs.camp_id', '=', 'camps.id')
        ->where('camps.id', $request->camp_id)
        ->orderBy('applicants.id')
        ->get();

        $columns = config('camps_fields.export4stat.' . $table) ?? [];
        foreach($columns as $key => $v) {
            $rows[] = $v;
        }

        if($request->app_id==0) {
            $this->gsheetservice->Clear(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
            $this->gsheetservice->Append(config('google.post_spreadsheet_id'), config('google.post_sheet_id'), $rows);  
        }

        foreach ($applicants as $applicant) {
            if($applicant->applicant_id <= $request->app_id) {
                continue;
            }
            $applicant->id = $applicant->applicant_id;
            $rows = array();
            foreach($columns as $key => $v) {
                $data = null;
                if($key == "admitted_no") {
                    $data = $applicant->group . $applicant->number;
                } else if($key == "bName") {
                    $data = $applicant->batch->name;
                } else if($key == "carers") {
                    $data = $applicant->carer_names();
                } else if($key == "is_attend") {
                    match ($applicant->is_attend) {
                        0 => $data = "不參加",
                        1 => $data = "參加",
                        2 => $data = "尚未決定",
                        3 => $data = "聯絡不上",
                        4 => $data = "無法全程",
                        default => $data = "尚未聯絡"
                    };
                } else if($key == "camporg_section") {
                    $user = ($applicant->user ?? null);
                    //$roles = ($user)? $user->roles->where('camp_id', $main_camp_id) : null;
                    $roles = $user?->roles?->where('camp_id', $main_camp_id) ?? null;
                    $data = ($roles)? $roles->flatten()->pluck('section')->implode(','): "";
                } else if($key == "camporg_position") {
                    $user = ($applicant->user ?? null);
                    $roles = ($user)? $user->roles->where('camp_id', $main_camp_id): null;
                    $data = ($roles)? $roles->flatten()->pluck('position')->implode(','): "";
                } else if($key == "fare") {
                    $data = ($applicant->lodging?->fare) ?? "";
                } else if($key == "deposit") {
                    $data = ($applicant->lodging?->deposit) ?? "";
                } else {
                    $data = $applicant->$key;
                }
                $rows[] = '"'. $data .'"';
            }
            $this->gsheetservice->Append(config('google.post_spreadsheet_id'), config('google.post_sheet_id'), $rows);
            sleep(1);   //1 second
            usleep(5000);   //5 millisecond
        }
    }
    
    public function exportGSCheckIn(Request $request)
    {   
        //將報名報到結果寫回GS
        $camp = Camp::find($request->camp_id);
        $table = $camp->table;
        $ids = $camp->applicants->pluck('id');
        //dd($ids);
        $row = array();

        if ($table == 'ecamp') {
            config([
                //ecamp
                'google.post_spreadsheet_id' => '1ihb-bcwwW8JItIyH692YniCJ03yyuqonXOseObExlvc',
                'google.post_sheet_id' => 'checkin',
            ]);
        } else if ($table == 'ceocamp') {
            config([
                //evcamp
                'google.post_spreadsheet_id' => '1GUvMO-GDdbfq3gVDHUMt_HTcEsj3dNFir5dO5KlnAGQ',
                'google.post_sheet_id' => 'checkin',
            ]);
        } else {
            exit(1);
        }
        
        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        $titles = $sheets[0];
        //$num_cols = count($titles);
        $num_rows = count($sheets);

        //columns: applicant_id, updated_at
        $last_updated_time = \Carbon\Carbon::parse($sheets[$num_rows-1][1]);
        //dd($last_updated_time);
        $check_in_new = \DB::table('check_in')
            ->whereDate('updated_at', '>', $last_updated_time)
            ->whereIn('applicant_id', $ids)
            ->orderBy('updated_at','asc')->get();
        //dd($check_in_new);
        foreach($check_in_new as $check_in) {
            $row[0] = $check_in->applicant_id;
            $row[1] = $check_in->updated_at;
            $this->gsheetservice->Append(config('google.post_spreadsheet_id'), config('google.post_sheet_id'), $row);
        }
        return;
    }

    public function importGSStatus(Request $request)
    {
        config([
            //ceocamp
            'google.post_spreadsheet_id' => '1nTogm6qganBoxUmYnaw1BQwGnJs3yiy6vDFxJ1M7V58',
            'google.post_sheet_id' => '表單回應 2',
        ]);

        $camp = Camp::find($request->camp_id);
        $table = $camp->table;
        $fare_room = config('camps_payments.fare_room.'.$table) ?? [];

        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        $titles = $sheets[0];
        $num_cols = count($titles);
        $num_rows = count($sheets);
        //dd($titles);

        $title_tg1 = "報名序號";
        $title_tg2 = "是否參加營隊";
        $title_tg3 = "住宿房型";
        $colidx1 = 0;
        $colidx2 = 0;
        $colidx3 = 0;

        //find title
        for ($i=1; $i<$num_cols; $i++) {
            if (str_contains($titles[$i], $title_tg1)) {
                $colidx1 = $i;
            } else if (str_contains($titles[$i], $title_tg2)) {
                $colidx2 = $i;
            } else if (str_contains($titles[$i], $title_tg3)) {
                $colidx3 = $i;
            }
        }

        //$success_count = 0;
        //$fail_count = 0;
        $ids = array();
        $is_attends = array();
        $room_types = array();
        for ($j=1; $j<$num_rows; $j++) {
            $data = $sheets[$j];
            if (count($data) > 2) { //已調查
                array_push($ids, $data[$colidx1]);
                //$is_attends[$data[$colidx1]] = ($data[$colidx2]?? "");
                if (isset($data[$colidx2])) {
                    if ($data[$colidx2] == "是")
                        $is_attends[$data[$colidx1]] = 1;
                    elseif ($data[$colidx2] == "否")
                        $is_attends[$data[$colidx1]] = 0;
                    elseif ($data[$colidx2] == "不確定")
                        $is_attends[$data[$colidx1]] = 2;
                }
                $room_types[$data[$colidx1]] = ($data[$colidx3]?? "");
            }
        }
        $applicants = Applicant::select('applicants.*')
            ->whereIn('id', $ids)->get();
        
        //try {
            foreach ($applicants as $applicant) {
                $applicant->is_attend = ($is_attends[$applicant->id]?? null);
                if ($room_types[$applicant->id] == "") {
                    $applicant->save();
                } else {             
                    $lodging = $applicant->lodging;
                    //尚未登記，建新的Lodging
                    if (!isset($lodging)) {
                        $lodging = new Lodging;
                        $lodging->applicant_id = $applicant->id;
                    }
                    //更新房型、天數及應繳車資
                    $lodging->room_type = $room_types[$applicant->id];
                    $lodging->nights = 1;
                    $lodging->fare = ($fare_room[$lodging->room_type] ?? 0) * ($lodging->nights ?? 0);
                    $lodging->save();
                    //update barcode
                    $applicant = $this->applicantService->fillPaymentData($applicant);
                    $applicant->save();
                }
            }
        //}
        //catch(\Exception $e){
        //    logger($e);
        //} 
        return;
    }
}
