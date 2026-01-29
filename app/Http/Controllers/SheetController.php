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

            $propertyMap = [
                'bName' => fn ($app) => $app->batch->name,
                'carers' => fn ($app) => $app->carer_names(),
                'room_type' => fn ($app) => $app->lodging?->room_type,
                'lodging_fare' => fn ($app) => $app->lodging?->fare,
                'lodging_fare_currency' => fn ($app) => $app->lodging?->fareCurrency?->code,
                'lodging_deposit' => fn ($app) => $app->lodging?->deposit,
                'lodging_deposit_currency' => fn ($app) => $app->lodging?->depositCurrency?->code,
                'depart_from' => fn ($app) => $app->traffic?->depart_from,
                'back_to' => fn ($app) => $app->traffic?->back_to,
                'traffic_fare' => fn ($app) => $app->traffic?->fare,
                'traffic_fare_currency' => fn ($app) => $app->traffic?->fareCurrency?->code,
                'traffic_deposit' => fn ($app) => $app->traffic?->deposit,
                'traffic_deposit_currency' => fn ($app) => $app->traffic?->depositCurrency?->code,
            ];

            foreach ($columns as $key => $v) {
                $data = null;
                if ($key == "admitted_no") {
                    $data = $applicant->group . $applicant->number;
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
                } elseif (array_key_exists($key, $propertyMap)) {
                    $data = $propertyMap[$key]($applicant) ?? '';
                } else {
                    $data = $applicant->$key;
                }
                $rows[] = '"'. $data .'"';
            }
            $this->gsheetservice->Append($sheet_id, $sheet_name, $rows);
            sleep(1);   //1 second
            usleep(5000);   //5 millisecond
        }
        return;
    }

    /*
        將報到結果(from check_in or sign_in_sign_out 寫到GS)
    */

    public function exportGSCheckIn(Request $request)
    {
        //which camp, which applicants
        $camp = Camp::find($request->camp_id);
        //$camp_table = $camp->table;
        $ids = $camp->applicants->pluck('id');

        //check_in or sign_in_sign_out
        if ($request->export_type == "signIn") {
            //require!! 1st field = id
            $fields = ['id', 'applicant_id', 'updated_at', 'availability_id', 'deleted_at'];
            $purpose = "exportSignIn";
            $table = "sign_in_sign_out";
        } elseif ($request->export_type == "checkIn") {
            //require!! 1st field = id
            $fields = ['id', 'applicant_id', 'updated_at'];
            $purpose = "exportCheckIn";
            $table = "check_in";
        }
        $num_fields = count($fields);

        //which gs link (to write)
        $ds = DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $request->camp_id)
            ->where('urltable_type', 'App\Models\Camp')
            ->where('purpose', $purpose)
            ->first();

        if ($ds == null) {
            echo "sheet not found\n";
            exit(1);
        }

        $sheet_id = $ds->spreadsheet_id;
        $sheet_name = $ds->sheet_name;
        $sheets = $this->gsheetservice->Get($sheet_id, $sheet_name);

        //read 1st row, should be "init_update_time"
        $row_0 = $sheets[0];
        $num_rows = count($sheets);
        $init_updated_time = \Carbon\Carbon::parse($sheets[0][0])->format('Y-m-d 00:00:00');

        //renew or update
        if ($request->renew == 1 || $num_rows == 1) {
            $this->gsheetservice->Clear($sheet_id, $sheet_name);
            $this->gsheetservice->Append($sheet_id, $sheet_name, $row_0);
            //renew
            $entries2write = \DB::table($table)
                ->where('updated_at', '>', $init_updated_time)
                ->whereIn('applicant_id', $ids)
                ->orderBy('id', 'asc')
                ->get();
        } else {
            //update
            $row_last_id = $sheets[$num_rows - $num_fields];
            $last_id = max($row_last_id);

            $entries2write = \DB::table($table)
                ->where('id', '>', $last_id)
                ->whereIn('applicant_id', $ids)
                ->orderBy('id', 'asc')
                ->get();
        }
        //dd($ids);
        $rows = []; //2D array
        for ($j = 0; $j < $num_fields; $j = $j + 1) {
            $rows[$j] = [];
        }
        $num_entries2write = count($entries2write);
        echo "num_entries2write: " . $num_entries2write . "\n";
        if ($num_entries2write > 0) {
            foreach ($entries2write as $entry) {
                $arrayEntry = json_decode(json_encode($entry), true);
                for ($j = 0; $j < $num_fields; $j = $j + 1) {
                    //field[$j] -> rows[$j]
                    array_push($rows[$j], ($arrayEntry[$fields[$j]] ?? null));
                }
            }
            for ($j = 0; $j < $num_fields; $j = $j + 1) {
                $this->gsheetservice->Append($sheet_id, $sheet_name, $rows[$j]);
            }
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
