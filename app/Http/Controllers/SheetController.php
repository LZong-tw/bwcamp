<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApplicantService;
use App\Services\BackendService;
use App\Services\GSheetService;
use App\Models\Applicant;
use App\Models\Camp;
use App\Models\CheckIn;
use App\Models\DynamicStat;
use App\Models\Vcamp;
use App\Models\Lodging;

class SheetController extends Controller
{
    protected $gsheetservice;
    protected $applicantService;
    protected $backendService;
    protected $request;
    /**
     * 建構子
     */
    public function __construct(
        GSheetService $gsheetservice,
        ApplicantService $applicantService,
        BackendService $backendService,
        //Request $request
    ) {
        $this->gsheetservice = $gsheetservice;
        $this->applicantService = $applicantService;
        $this->backendService = $backendService;
        //$this->request = $request;
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
        if ($request->day == 1) {
            config([
                'google.post_spreadsheet_id' => '1Bdnv5ehYLCYv_8RYhtbqVS2rseOuoxdaVvP2Ehi6egM',
                'google.post_sheet_id' => '表單回應 1',
            ]);
        } elseif ($request->day == 2) {
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

        foreach ($keys as $key) {
            $i = 0;
            foreach ($sheets as $row) {
                $names[$i] = $row[$key];
                $i = $i + 1;
            }
            $key1 = array_search($name_tg, $names);
            if ($key1 <> false) {
                break;
            }
        }

        if ($key1 == false) {
            $contents = null;
            $content_count = 0;
        } else {
            //to deal with content_count < title_count
            $contents = $sheets[$key1];
            $content_count = count($contents);
        }

        return view('backend.in_camp.gsFeedback', compact('titles', 'contents', 'content_count'));
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

        foreach ($keys as $key) {
            $i = 0;
            foreach ($sheets as $row) {
                $names[$i] = $row[$key];
                $i = $i + 1;
            }
            $key1 = array_search($name_tg, $names);
            if ($key1 <> false) {
                break;
            }
        }

        if ($key1 == false) {
            $contents = null;
            $content_count = 0;
        } else {
            //to deal with content_count < title_count
            $contents = $sheets[$key1];
            $content_count = count($contents);
        }

        return view('backend.in_camp.gsFeedback', compact('titles', 'contents', 'content_count'));
    }
    /*
        public function importGSApplicants(Request $request)
        {
            // 取得營隊相關資訊
            $camp = Camp::find($request->camp_id);
            $table = $camp->table;
            $mainCampId = $this->getMainCampId($camp, $request->camp_id);

            // 取得 Google Sheets 設定
            $type = '\App\Models\Camp';
            $purpose = 'exportApplicants';
            $sheetConfig = $this->getApplicantSheetConfig($request->camp_id, $type, $purpose);
            if (!$sheetConfig) {
                $this->outputError("sheet not found");
                return;
            }

            // 取得申請者資料
            $applicants = $this->getApplicantsForExport($request->camp_id, $table);
            // 取得匯出欄位設定
            $columns = config('camps_fields.export4stat.' . $table) ?? [];

            // 準備並匯出資料
            $this->exportApplicantsToSheet(
                $sheetConfig,
                $applicants,
                $columns,
                $mainCampId,
                $request->app_id
            );
        }
    */
    /**
     * 取得主營隊 ID
     */
    private function getMainCampId(Camp $camp, int $campId): ?int
    {
        if ($camp->is_vcamp()) {
            $vcamp = Vcamp::find($campId);
            return $vcamp->mainCamp->id;
        } else {
            return $campId;
        }
    }

    /**
     * 取得申請者匯出的 Google Sheets 設定
     */
    private function getApplicantSheetConfig(int $id, string $type, string $purpose): ?object
    {
        return DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $id)
            ->where('urltable_type', $type)
            ->where('purpose', $purpose)
            ->first();
    }

    /**
     * 取得需要匯出的申請者資料
     */
    private function getApplicantsForExport(int $campId, string $table)
    {
        return Applicant::select('applicants.*', $table . '.*')
            ->join($table, 'applicants.id', '=', $table . '.applicant_id')
            ->join('batchs', 'applicants.batch_id', '=', 'batchs.id')
            ->join('camps', 'batchs.camp_id', '=', 'camps.id')
            ->where('camps.id', $campId)
            ->orderBy('applicants.id')
            ->get();
    }


    /**
     * 匯出申請者資料到 Google Sheets
     */
    private function exportApplicantsToSheet(
        object $sheetConfig,
        $applicants,
        array $columns,
        ?int $mainCampId,
        int $startAppId
    ): void {
        // 準備標題列
        $headerRow = array_values($columns);

        // 如果是從頭開始，先清空並寫入標題
        if ($startAppId == 0) {
            $this->gsheetservice->Clear($sheetConfig->spreadsheet_id, $sheetConfig->sheet_name);
            $this->gsheetservice->Append(
                $sheetConfig->spreadsheet_id,
                $sheetConfig->sheet_name,
                $headerRow
            );
        }

        // 匯出每位申請者的資料
        foreach ($applicants as $applicant) {
            if ($applicant->applicant_id <= $startAppId) {
                continue;
            }
            $dataRow = $this->prepareApplicantDataRow(
                $applicant,
                $columns,
                $mainCampId
            );

            $this->gsheetservice->Append(
                $sheetConfig->spreadsheet_id,
                $sheetConfig->sheet_name,
                $dataRow
            );

            // 避免超過 API 配額限制
            sleep(1);
            usleep(5000);
        }
    }
    /*
            =======
            //camp_id = 78 & ds_id = 384
            //php artisan import:Applicant 78 384 --is_org=1
            $camp = Camp::find($request->camp_id);
            $table = $camp->table;

            $ds = DynamicStat::find($request->ds_id);
            $ss_id = $ds->spreadsheet_id;
            $sheet_name = $ds->sheet_name;

            $sheets = $this->gsheetservice->Get($ss_id, $sheet_name);
            $titles = $sheets[0];
            $num_cols = count($titles);
            $num_rows = count($sheets);

            $create_count = 0;
            $update_count = 0;
            for ($i = 1; $i < $num_rows; $i++) {
                $data = $sheets[$i];
                for ($j = 0; $j < $num_cols; $j++) {
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
                    $applicant->update($title_data);
                    $applicant->save();
                    $model = '\\App\\Models\\' . ucfirst($table);
                    //extended data
                    $xcamp = $model::select($table.'.*')
                        ->where('applicant_id', $applicant->id)
                        ->first();
                    $xcamp->update($title_data);
                    $xcamp->save();
                    $update_count++;
                } else {            //create new
                    $applicant = \DB::transaction(function () use ($title_data, $table) {
                        $applicant = Applicant::create($title_data);
                        $title_data['applicant_id'] = $applicant->id;
                        $model = '\\App\\Models\\' . ucfirst($table);
                        $model::create($title_data);
                        return $applicant;
                    });
                    $create_count++;
                }
                //dd($applicant);
                /*if ($applicant->email <> null) {
                    $user = \App\Models\User::where('name', 'like', "%". $applicant->name . "%")
                    ->orWhere('email', 'like', "%". $applicant->email . "%")
                    //->orWhere('mobile', 'like', "%". $applicant->mobile . "%")
                    ->orderByDesc('id')->first();
                } else {
                    $user = \App\Models\User::where('name', 'like', "%". $applicant->name . "%")
                    //->orWhere('mobile', 'like', "%". $applicant->mobile . "%")
                    ->orderByDesc('id')->first();
                }*/
    /*
                if ($request->is_org) {
                    $candidates = array();
                    $candidates[0]["type"] = "applicant";
                    $candidates[0]["id"] = $applicant->id;
                    $candidates[0]["uses_user_id"] = "generation_needed";
                    $org_id = $title_data['org_id'];
                    //dd($candidates);
                    $this->backendService->setGroupOrg($candidates, $org_id);
                }
                if ($i % 500 == 0) {
                    sleep(5);
                    //dd($fail_count);
                }
            }
            $stat['create'] = $create_count;
            $stat['update'] = $update_count;
            dd($stat);
            //return view('backend.in_camp.gsFeedback', compact('titles','contents','content_count'));
        }
    */
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

        $ds = DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $request->camp_id)
            ->where('urltable_type', 'App\Models\Camp')
            ->where('purpose', 'exportApplicants')
            ->first();

        if ($ds == null) {
            echo "sheet not found\n";
            exit(1);
        }

        $sheet_id = $ds->spreadsheet_id;
        $sheet_name = $ds->sheet_name;
        $sheets = $this->gsheetservice->Get($sheet_id, $sheet_name);

        $applicants = Applicant::select('applicants.*', $table . '.*')
        ->join($table, 'applicants.id', '=', $table . '.applicant_id')
        ->join('batchs', 'applicants.batch_id', '=', 'batchs.id')
        ->join('camps', 'batchs.camp_id', '=', 'camps.id')
        ->where('camps.id', $request->camp_id)
        ->orderBy('applicants.id')
        ->get();

        $columns = config('camps_fields.export4stat.' . $table) ?? [];
        foreach ($columns as $key => $v) {
            $rows[] = $v;
        }

        if ($request->app_id == 0) {
            $this->gsheetservice->Clear($sheet_id, $sheet_name);
            $this->gsheetservice->Append($sheet_id, $sheet_name, $rows);
        }

        foreach ($applicants as $applicant) {
            if ($applicant->applicant_id <= $request->app_id) {
                continue;
            }
            $applicant->id = $applicant->applicant_id;
            $rows = array();
            foreach ($columns as $key => $v) {
                $data = null;
                if ($key == "admitted_no") {
                    $data = $applicant->group . $applicant->number;
                } elseif ($key == "bName") {
                    $data = $applicant->batch->name;
                } elseif ($key == "carers") {
                    $data = $applicant->carer_names();
                } elseif ($key == "is_attend") {
                    match ($applicant->is_attend) {
                        0 => $data = "不參加",
                        1 => $data = "參加",
                        2 => $data = "尚未決定",
                        3 => $data = "聯絡不上",
                        4 => $data = "無法全程",
                        default => $data = "尚未聯絡"
                    };
                } elseif ($key == "camporg_section") {
                    $user = ($applicant->user ?? null);
                    //$roles = ($user)? $user->roles->where('camp_id', $main_camp_id) : null;
                    $roles = $user?->roles?->where('camp_id', $main_camp_id) ?? null;
                    $data = ($roles) ? $roles->flatten()->pluck('section')->implode(',') : "";
                } elseif ($key == "camporg_position") {
                    $user = ($applicant->user ?? null);
                    $roles = ($user) ? $user->roles->where('camp_id', $main_camp_id) : null;
                    $data = ($roles) ? $roles->flatten()->pluck('position')->implode(',') : "";
                } elseif ($key == "fare") {
                    $data = ($applicant->lodging?->fare) ?? "";
                } elseif ($key == "deposit") {
                    $data = ($applicant->lodging?->deposit) ?? "";
                } else {
                    $data = $applicant->$key;
                }
                $rows[] = '"'. $data .'"';
            }
            $this->gsheetservice->Append($sheet_id, $sheet_name, $rows);
            sleep(1);   //1 second
            usleep(5000);   //5 millisecond
        }
    }
    /*
        public function exportGSCheckIn(Request $request)
        {
            //將報名報到結果寫回GS
            $camp = Camp::find($request->camp_id);
            $table = $camp->table;
            $ids = $camp->applicants->pluck('id');
            //dd($ids);
            $row = array();
            $row1 = array();

            //ds_id = 387
            $ds = DynamicStat::select('dynamic_stats.*')
                ->where('urltable_id',$request->camp_id)
                ->where('urltable_type','App\Models\Camp')
                ->where('purpose','exportCheckIn')
                ->first();

            if ($ds == null) {
                echo "sheet not found\n";
                exit(1);
            }

            $sheet_id = $ds->spreadsheet_id;
            $sheet_name = $ds->sheet_name;
            $sheets = $this->gsheetservice->Get($sheet_id, $sheet_name);

            //dd($sheets);
            $titles = $sheets[0];
            $dummy = $sheets[1];
            $num_cols = count($titles);
            $num_rows = count($sheets);

            $colidx1 = -1;
            $colidx2 = -1;
            $colidx3 = -1;
            $colidx4 = -1;

            //find title
            for ($i=0; $i<$num_cols; $i++) {
                if ($titles[$i] == "id") {
                    $colidx1 = $i;
                } else if ($titles[$i] == "applicant_id") {
                    $colidx2 = $i;
                } else if ($titles[$i] == "updated_at") {
                    $colidx3 = $i;
                } else if ($titles[$i] == "status") {
                    $colidx4 = $i;
                }
            }

            if ($colidx1 == -1)
            {   echo "missing column id\n"; exit(1);}
            else if ($colidx2 == -1)
            {   echo "missing column applicant_id\n"; exit(1);}
            else if ($colidx3 == -1)
            {   echo "missing column updated_at\n"; exit(1);}
            else if ($colidx4 == -1)
            {   echo "missing column status\n"; exit(1);}

            //row=0: titles
            //row=1: a dummy to set the first_updated_time first_id (use 0)
            $regex = '/^(\d{4}[-\/]\d{1,2}[-\/]\d{1,2}([T ]\d{1,2}:\d{1,2}(:\d{1,2})?(\.\d+)?(([+-]\d{2}:\d{2})|Z)?)?|\d{1,2}[-\/]\d{1,2}[-\/]\d{4}([T ]\d{1,2}:\d{1,2}(:\d{1,2})?(\.\d+)?(([+-]\d{2}:\d{2})|Z)?)?)$/';
            if ($sheets[1][$colidx3] && preg_match($regex, $sheets[1][$colidx3])) {
                $init_updated_time = \Carbon\Carbon::parse($sheets[$num_rows-1][$colidx3]);
            }
            else {
                $init_updated_time = today()->format('Y-m-d 00:00:00');
            }

            if ($sheets[$num_rows-1][$colidx3] && preg_match($regex, $sheets[$num_rows-1][$colidx3])) {
                //columns: applicant_id, updated_at, status, id
                $last_updated_time = \Carbon\Carbon::parse($sheets[$num_rows-1][$colidx3]);
                $last_id = $sheets[$num_rows-1][$colidx1];
            }
            else {
                $last_updated_time = today()->format('Y-m-d 00:00:00');
                $last_id = 0;   //dummy
            }

            if ($request->renew == 1) {
                $this->gsheetservice->Clear($sheet_id, $sheet_name);
                $this->gsheetservice->Append($sheet_id, $sheet_name, $titles);
                $this->gsheetservice->Append($sheet_id, $sheet_name, $dummy);
                $checkin_renew = \DB::table('check_in')
                    ->where('updated_at', '>', $init_updated_time)
                    ->whereIn('applicant_id', $ids)
                    ->orderBy('updated_at','asc')->get();
                echo "num_checkin_renew: " . count($checkin_renew) . "\n";
                $chunked_checkin_renew = array_chunk($checkin_renew->toArray(), 60);
                $backoff = 1;
                $max_backoff = 65;
                foreach($chunked_checkin_renew as $k => $chunk) {
                    // Exponential backoff algorithm
                    $processed_indices = [];
                    while (count($processed_indices) < count($chunk)) {
                        try {
                            for ($i = 0; $i < count($chunk); $i++) {
                                if (!in_array($i, $processed_indices)) {
                                    $checkin = $chunk[$i];
                                    echo "writing applicant_id: " . $checkin->applicant_id . "\n";
                                    $row[$colidx1] = $checkin->id;
                                    $row[$colidx2] = $checkin->applicant_id;
                                    $row[$colidx3] = $checkin->updated_at;
                                    $row[$colidx4] = 1;
                                    $this->gsheetservice->Append($sheet_id, $sheet_name, $row);
                                    $processed_indices[] = $i;
                                }
                            }
                            $backoff = 1;  // Reset backoff on success
                        } catch (\Exception $e) {
                            if (strpos($e->getMessage(), 'Quota exceeded') !== false) {
                                echo "Quota exceeded. Backing off for {$backoff} seconds.\n";
                                for ($i = $backoff; $i > 0; $i--) {
                                    echo $i . " ";
                                    sleep(1);
                                    if (ob_get_level() > 0) {
                                        ob_flush();
                                    }
                                    flush();
                                }
                                $backoff = min($backoff * 2, $max_backoff);  // Exponential backoff
                            } else {
                                throw $e;  // Re-throw if it's not a quota error
                            }
                        }
                    }
                    echo $k + 1 . " chunk done, total chunks: " . count($chunked_checkin_renew) . "\n";
                }
            }
            else {
                $checkin_new = \DB::table('check_in')
                    ->where('id', '>', $last_id)
                    ->where('updated_at', '>=', $last_updated_time) //同時間可以有很多筆
                    ->whereIn('applicant_id', $ids)
                    ->orderBy('id','asc')->get();
                echo "num_checkin_new: " . count($checkin_new) . "\n";
                //dd($checkin_new);
                $i=0;
                foreach($checkin_new as $checkin) {
                    if ($i==60) break;
                    $row[$colidx1] = $checkin->id;
                    $row[$colidx2] = $checkin->applicant_id;
                    $row[$colidx3] = $checkin->updated_at;
                    $row[$colidx4] = 1;
                    $this->gsheetservice->Append($sheet_id, $sheet_name, $row);
                    $i = $i+1;
                }
            }
            echo "done" . "\n";
            return;
        }
    */

    public function exportGSCheckIn(Request $request)
    {
        //將報名報到結果寫回GS
        $camp = Camp::find($request->camp_id);
        $table = $camp->table;
        $ids = $camp->applicants->pluck('id');
        //dd($ids);
        $row_id = array();
        $row_applicant_id = array();
        $row_updated_at = array();

        //ds_id = 387
        $ds = DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $request->camp_id)
            ->where('urltable_type', 'App\Models\Camp')
            ->where('purpose', 'exportCheckIn')
            ->first();

        if ($ds == null) {
            echo "sheet not found\n";
            exit(1);
        }

        $sheet_id = $ds->spreadsheet_id;
        $sheet_name = $ds->sheet_name;
        $sheets = $this->gsheetservice->Get($sheet_id, $sheet_name);

        //dd($sheets);
        $row_0 = $sheets[0];
        $num_rows = count($sheets);
        $init_updated_time = \Carbon\Carbon::parse($sheets[0][0])->format('Y-m-d 00:00:00');

        if ($request->renew == 1 || $num_rows == 1) {
            $this->gsheetservice->Clear($sheet_id, $sheet_name);
            $this->gsheetservice->Append($sheet_id, $sheet_name, $row_0);
            $checkin_new = \DB::table('check_in')
                ->where('updated_at', '>', $init_updated_time)
                ->whereIn('applicant_id', $ids)
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $row_last_id = $sheets[$num_rows - 3];
            $last_id = max($row_last_id);

            $checkin_new = \DB::table('check_in')
                ->where('id', '>', $last_id)
                ->whereIn('applicant_id', $ids)
                ->orderBy('id', 'asc')
                ->get();
        }
        $num_checkin_new = count($checkin_new);
        echo "num_checkin_new: " . $num_checkin_new . "\n";
        if ($num_checkin_new > 0) {
            foreach ($checkin_new as $checkin) {
                array_push($row_id, $checkin->id);
                array_push($row_applicant_id, $checkin->applicant_id);
                array_push($row_updated_at, $checkin->updated_at);
            }
            $this->gsheetservice->Append($sheet_id, $sheet_name, $row_id);
            $this->gsheetservice->Append($sheet_id, $sheet_name, $row_applicant_id);
            $this->gsheetservice->Append($sheet_id, $sheet_name, $row_updated_at);
        }
        echo "done" . "\n";
        return;
    }

    public function importGSStatus(Request $request)
    {
        $camp = Camp::find($request->camp_id);
        $table = $camp->table;
        $fare_room = config('camps_payments.fare_room.' . $table) ?? [];

        //maybe more than one
        $dss = DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $request->camp_id)
            ->where('urltable_type', 'App\Models\Camp')
            ->where('purpose', 'importForm')
            ->get();

        if ($dss == null) {
            echo "sheet not found\n";
            exit(1);
        }

        foreach ($dss as $ds) {
            $sheet_id = $ds->spreadsheet_id;
            $sheet_name = $ds->sheet_name;
            $sheets = $this->gsheetservice->Get($sheet_id, $sheet_name);

            $titles = $sheets[0];
            $num_cols = count($titles);
            $num_rows = count($sheets);

            $title_tg = array("報名序號", "是否參加營隊", "住宿房型", "繳費");
            $colidx = array(-1, -1, -1, -1);
            $jcnt = count($title_tg);
            //find title
            for ($i = 0; $i < $num_cols; $i++) {
                for ($j = 0; $j < $jcnt; $j++) {
                    if (str_contains($titles[$i], $title_tg[$j])) {
                        $colidx[$j] = $i;
                        continue;
                    }
                }
            }

            if ($table == 'ceocamp') {
                //$success_count = 0;
                //$fail_count = 0;
                $ids = array();
                $is_attends = array();
                $room_types = array();
                for ($j = 1; $j < $num_rows; $j++) {
                    $data = $sheets[$j];
                    if (count($data) > 2) { //已調查
                        array_push($ids, $data[$colidx[0]]);
                        //$is_attends[$data[$colidx1]] = ($data[$colidx2]?? "");
                        if (isset($data[$colidx[1]])) {
                            if ($data[$colidx[1]] == "是") {
                                $is_attends[$data[$colidx[0]]] = 1;
                            } elseif ($data[$colidx[1]] == "否") {
                                $is_attends[$data[$colidx[0]]] = 0;
                            } else {
                                $is_attends[$data[$colidx[0]]] = 2;
                            }
                        }
                        $room_types[$data[$colidx[0]]] = ($data[$colidx[2]] ?? "");
                    }
                }

                $applicants = Applicant::select('applicants.*')
                    ->whereIn('id', $ids)->get();
                try {
                    foreach ($applicants as $applicant) {
                        //dd($applicant->id);
                        $applicant->is_attend = ($is_attends[$applicant->id] ?? null);
                        if ($room_types[$applicant->id] == "") {
                            $applicant->save();
                        } else {
                            $lodging = $applicant->lodging;
                            //尚未登記，建新的Lodging
                            if (!isset($lodging)) {
                                $lodging = new Lodging();
                                $lodging->applicant_id = $applicant->id;
                            }
                            //更新房型、天數及應繳車資
                            $lodging->room_type = $room_types[$applicant->id];
                            $lodging->nights = 1;
                            $lodging->fare = ($fare_room[$lodging->room_type] ?? 0) * ($lodging->nights ?? 0);
                            $lodging->save();
                            //dd($lodging);
                            //update barcode
                            $applicant = $this->applicantService->fillPaymentData($applicant);
                            $applicant->save();
                        }
                    }
                } catch (\Exception $e) {
                    logger($e);
                }
            } elseif ($table == 'utcamp') {
                $ids = array();
                $deposits = array();
                for ($j = 1; $j < $num_rows; $j++) {
                    $data = $sheets[$j];
                    if (count($data) < $num_cols) {
                        break;
                    }
                    $app_id = preg_replace("/[^0-9]/", "", $data[$colidx[0]]);
                    $deposit = preg_replace("/[^0-9.]/", "", $data[$colidx[3]]);
                    if ($app_id == "") {
                        continue;
                    }
                    array_push($ids, $app_id);
                    $deposits[$app_id] = $deposit;
                }
                $applicants = Applicant::select('applicants.*')
                    ->whereIn('id', $ids)->get();

                $update_count = 0;
                try {
                    foreach ($applicants as $applicant) {
                        $applicant->deposit = $deposits[$applicant->id];
                        $applicant->save();
                        $update_count++;
                    }
                } catch (\Exception $e) {
                    logger($e);
                }
            }
        }
        return($update_count);
    }
}
