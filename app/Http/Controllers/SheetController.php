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

    public function importGSApplicants(Request $request)
    {
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

    /**
     * 匯出申請者資料到 Google Sheets
     *
     * @param Request $request 包含 camp_id 和 app_id 參數
     * @return void
     */
    public function exportGSApplicants(Request $request)
    {
        // 取得營隊相關資訊
        $camp = Camp::find($request->camp_id);
        $table = $camp->table;
        $mainCampId = $this->getMainCampId($camp, $request->camp_id);

        // 取得 Google Sheets 設定
        $sheetConfig = $this->getApplicantSheetConfig($request->camp_id);
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

    /**
     * 取得主營隊 ID
     */
    private function getMainCampId(Camp $camp, int $campId): ?int
    {
        if ($camp->is_vcamp()) {
            $vcamp = Vcamp::find($campId);
            return $vcamp->mainCamp->id;
        }
        return null;
    }

    /**
     * 取得申請者匯出的 Google Sheets 設定
     */
    private function getApplicantSheetConfig(int $campId): ?object
    {
        return DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $campId)
            ->where('urltable_type', 'App\Models\Camp')
            ->where('purpose', 'exportApplicants')
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

    /**
     * 準備單個申請者的資料列
     */
    private function prepareApplicantDataRow($applicant, array $columns, ?int $mainCampId): array
    {
        $applicant->id = $applicant->applicant_id;
        $dataRow = [];

        foreach ($columns as $key => $label) {
            $value = $this->getApplicantFieldValue($applicant, $key, $mainCampId);
            $dataRow[] = '"' . $value . '"';
        }

        return $dataRow;
    }

    /**
     * 取得申請者特定欄位的值
     */
    private function getApplicantFieldValue($applicant, string $fieldKey, ?int $mainCampId): string
    {
        switch ($fieldKey) {
            case "admitted_no":
                return $applicant->group . $applicant->number;

            case "bName":
                return $applicant->batch->name;

            case "carers":
                return $applicant->carer_names();

            case "is_attend":
                return $this->getAttendanceStatus($applicant->is_attend);

            case "camporg_section":
                return $this->getCampOrgSection($applicant, $mainCampId);

            case "camporg_position":
                return $this->getCampOrgPosition($applicant, $mainCampId);

            case "fare":
                return ($applicant->lodging?->fare) ?? "";

            case "deposit":
                return ($applicant->lodging?->deposit) ?? "";

            default:
                return $applicant->$fieldKey ?? "";
        }
    }

    /**
     * 取得參加狀態的文字描述
     */
    private function getAttendanceStatus(?int $status): string
    {
        return match ($status) {
            0 => "不參加",
            1 => "參加",
            2 => "尚未決定",
            3 => "聯絡不上",
            4 => "無法全程",
            default => "尚未聯絡"
        };
    }

    /**
     * 取得營隊組織部門
     */
    private function getCampOrgSection($applicant, ?int $mainCampId): string
    {
        $user = $applicant->user ?? null;
        $roles = $user?->roles?->where('camp_id', $mainCampId) ?? null;
        return $roles ? $roles->flatten()->pluck('section')->implode(',') : "";
    }

    /**
     * 取得營隊組織職位
     */
    private function getCampOrgPosition($applicant, ?int $mainCampId): string
    {
        $user = $applicant->user ?? null;
        $roles = $user?->roles?->where('camp_id', $mainCampId) ?? null;
        return $roles ? $roles->flatten()->pluck('position')->implode(',') : "";
    }
    /**
     * 將報名報到結果匯出至 Google Sheets
     *
     * @param Request $request 包含 camp_id 和 renew 參數
     * @return void
     */
    public function exportGSCheckIn(Request $request)
    {
        // 取得營隊相關資料
        $camp = Camp::find($request->camp_id);
        $campApplicantIds = $camp->applicants->pluck('id');

        // 取得 Google Sheets 設定
        $sheetConfig = $this->getCheckInSheetConfig($request->camp_id);
        if (!$sheetConfig) {
            $this->outputError("sheet not found");
            return;
        }

        // 讀取現有的 Google Sheets 資料
        $existingData = $this->gsheetservice->Get(
            $sheetConfig->spreadsheet_id,
            $sheetConfig->sheet_name
        );

        // 取得需要匯出的報到資料
        $checkInData = $this->getCheckInDataToExport(
            $request,
            $existingData,
            $campApplicantIds
        );

        // 匯出資料到 Google Sheets
        $this->exportCheckInToSheet(
            $sheetConfig,
            $existingData,
            $checkInData,
            $request->renew == 1
        );

        echo "done\n";
    }

    /**
     * 取得報到匯出的 Google Sheets 設定
     */
    private function getCheckInSheetConfig(int $campId): ?object
    {
        return DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $campId)
            ->where('urltable_type', 'App\Models\Camp')
            ->where('purpose', 'exportCheckIn')
            ->first();
    }

    /**
     * 取得需要匯出的報到資料
     */
    private function getCheckInDataToExport(
        Request $request,
        array $existingData,
        $campApplicantIds
    ): \Illuminate\Support\Collection {
        $numRows = count($existingData);

        // 取得初始更新時間（從第一列第一欄取得）
        $initUpdatedTime = \Carbon\Carbon::parse($existingData[0][0])
            ->format('Y-m-d 00:00:00');

        // 判斷是否需要重新匯出所有資料
        if ($request->renew == 1 || $numRows == 1) {
            return $this->getAllCheckInData($initUpdatedTime, $campApplicantIds);
        }

        // 只取得新增的報到資料
        return $this->getNewCheckInData($existingData, $numRows, $campApplicantIds);
    }

    /**
     * 取得所有報到資料（用於重新匯出）
     */
    private function getAllCheckInData(string $initUpdatedTime, $campApplicantIds)
    {
        return \DB::table('check_in')
            ->where('updated_at', '>', $initUpdatedTime)
            ->whereIn('applicant_id', $campApplicantIds)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * 取得新增的報到資料（增量更新）
     */
    private function getNewCheckInData(array $existingData, int $numRows, $campApplicantIds)
    {
        // 從倒數第三列取得最後的 ID（資料結構：第一列標題，最後兩列為資料）
        $lastIdRow = $existingData[$numRows - 3];
        $lastId = max($lastIdRow);

        return \DB::table('check_in')
            ->where('id', '>', $lastId)
            ->whereIn('applicant_id', $campApplicantIds)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * 匯出報到資料到 Google Sheets
     */
    private function exportCheckInToSheet(
        object $sheetConfig,
        array $existingData,
        \Illuminate\Support\Collection $checkInData,
        bool $shouldRenew
    ): void {
        $numCheckIn = count($checkInData);
        echo "num_checkin_new: " . $numCheckIn . "\n";

        if ($numCheckIn === 0) {
            return;
        }

        // 如果需要重新匯出，先清空並寫入標題
        if ($shouldRenew || count($existingData) == 1) {
            $this->gsheetservice->Clear($sheetConfig->spreadsheet_id, $sheetConfig->sheet_name);
            $this->gsheetservice->Append(
                $sheetConfig->spreadsheet_id,
                $sheetConfig->sheet_name,
                $existingData[0]  // 標題列
            );
        }

        // 準備資料列
        $dataRows = $this->prepareCheckInDataRows($checkInData);

        // 批次寫入資料
        foreach ($dataRows as $row) {
            $this->gsheetservice->Append(
                $sheetConfig->spreadsheet_id,
                $sheetConfig->sheet_name,
                $row
            );
        }
    }

    /**
     * 準備報到資料列（依照 ID、申請者ID、更新時間分列）
     */
    private function prepareCheckInDataRows(\Illuminate\Support\Collection $checkInData): array
    {
        $rows = [
            'ids' => [],
            'applicant_ids' => [],
            'updated_at' => []
        ];

        foreach ($checkInData as $checkIn) {
            $rows['ids'][] = $checkIn->id;
            $rows['applicant_ids'][] = $checkIn->applicant_id;
            $rows['updated_at'][] = $checkIn->updated_at;
        }

        return [
            $rows['ids'],
            $rows['applicant_ids'],
            $rows['updated_at']
        ];
    }

    /**
     * 輸出錯誤訊息並結束程式
     */
    private function outputError(string $message): void
    {
        echo $message . "\n";
        exit(1);
    }

    /**
     * 從 Google Sheets 匯入申請者狀態
     *
     * @param Request $request 包含 camp_id 參數
     * @return int 更新的記錄數
     */
    public function importGSStatus(Request $request)
    {
        // 取得營隊資訊
        $camp = Camp::find($request->camp_id);
        $table = $camp->table;

        // 取得所有相關的 Google Sheets 設定
        $sheetConfigs = $this->getImportFormSheetConfigs($request->camp_id);
        if ($sheetConfigs->isEmpty()) {
            $this->outputError("sheet not found");
            return 0;
        }

        $totalUpdateCount = 0;

        // 處理每個 Google Sheet
        foreach ($sheetConfigs as $sheetConfig) {
            $updateCount = $this->processSheetImport($sheetConfig, $table);
            $totalUpdateCount += $updateCount;
        }

        return $totalUpdateCount;
    }

    /**
     * 取得匯入表單的 Google Sheets 設定
     */
    private function getImportFormSheetConfigs(int $campId)
    {
        return DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $campId)
            ->where('urltable_type', 'App\Models\Camp')
            ->where('purpose', 'importForm')
            ->get();
    }

    /**
     * 處理單個 Google Sheet 的匯入
     */
    private function processSheetImport(object $sheetConfig, string $table): int
    {
        // 讀取 Google Sheet 資料
        $sheets = $this->gsheetservice->Get(
            $sheetConfig->spreadsheet_id,
            $sheetConfig->sheet_name
        );

        if (empty($sheets)) {
            return 0;
        }

        // 根據不同的營隊類型處理匯入
        return match ($table) {
            'ceocamp' => $this->importCeocampStatus($sheets),
            'utcamp' => $this->importUtcampStatus($sheets),
            default => 0
        };
    }

    /**
     * 匯入 CEO Camp 狀態
     */
    private function importCeocampStatus(array $sheets): int
    {
        $titles = $sheets[0];
        $titleTargets = ['id', '參加', '房型'];

        // 找出欄位索引
        $columnIndexes = $this->findColumnIndexes($titles, $titleTargets);
        if (in_array(-1, $columnIndexes)) {
            echo "Missing required columns\n";
            return 0;
        }

        // 收集需要更新的資料
        $updateData = $this->collectCeocampUpdateData($sheets, $columnIndexes);

        // 執行更新
        return $this->updateCeocampApplicants($updateData);
    }

    /**
     * 匯入 UT Camp 狀態
     */
    private function importUtcampStatus(array $sheets): int
    {
        $titles = $sheets[0];
        $titleTargets = ['報名序號', null, null, '已繳金額'];

        // 找出欄位索引
        $columnIndexes = $this->findColumnIndexes($titles, $titleTargets);
        if ($columnIndexes[0] == -1 || $columnIndexes[3] == -1) {
            echo "Missing required columns\n";
            return 0;
        }

        // 收集需要更新的資料
        $updateData = $this->collectUtcampUpdateData($sheets, $columnIndexes);

        // 執行更新
        return $this->updateUtcampApplicants($updateData);
    }

    /**
     * 尋找欄位索引
     */
    private function findColumnIndexes(array $titles, array $targets): array
    {
        $indexes = array_fill(0, count($targets), -1);

        foreach ($titles as $colIndex => $title) {
            foreach ($targets as $targetIndex => $target) {
                if ($target !== null && str_contains($title, $target)) {
                    $indexes[$targetIndex] = $colIndex;
                }
            }
        }

        return $indexes;
    }

    /**
     * 收集 CEO Camp 更新資料
     */
    private function collectCeocampUpdateData(array $sheets, array $columnIndexes): array
    {
        $data = [
            'ids' => [],
            'is_attends' => [],
            'room_types' => []
        ];

        $numRows = count($sheets);

        for ($rowIndex = 1; $rowIndex < $numRows; $rowIndex++) {
            $row = $sheets[$rowIndex];

            if (count($row) <= 2) {
                continue;
            }

            $id = $row[$columnIndexes[0]];
            $data['ids'][] = $id;

            // 處理參加狀態
            if (isset($row[$columnIndexes[1]])) {
                $data['is_attends'][$id] = $this->parseAttendanceStatus($row[$columnIndexes[1]]);
            }

            // 處理房型
            $data['room_types'][$id] = $row[$columnIndexes[2]] ?? "";
        }

        return $data;
    }

    /**
     * 收集 UT Camp 更新資料
     */
    private function collectUtcampUpdateData(array $sheets, array $columnIndexes): array
    {
        $data = [
            'ids' => [],
            'deposits' => []
        ];

        $numRows = count($sheets);
        $numCols = count($sheets[0]);

        for ($rowIndex = 1; $rowIndex < $numRows; $rowIndex++) {
            $row = $sheets[$rowIndex];

            if (count($row) < $numCols) {
                break;
            }

            $appId = preg_replace("/[^0-9]/", "", $row[$columnIndexes[0]]);
            $deposit = preg_replace("/[^0-9.]/", "", $row[$columnIndexes[3]]);

            if ($appId === "") {
                continue;
            }

            $data['ids'][] = $appId;
            $data['deposits'][$appId] = $deposit;
        }

        return $data;
    }

    /**
     * 解析參加狀態
     */
    private function parseAttendanceStatus(string $status): int
    {
        return match ($status) {
            "是" => 1,
            "否" => 0,
            default => 2
        };
    }

    /**
     * 更新 CEO Camp 申請者資料
     */
    private function updateCeocampApplicants(array $updateData): int
    {
        $updateCount = 0;
        $applicants = Applicant::whereIn('id', $updateData['ids'])->get();
        $fareRoom = config('camps_payments.fare_room.ceocamp') ?? [];

        try {
            foreach ($applicants as $applicant) {
                $applicantId = $applicant->id;

                // 更新參加狀態
                $applicant->is_attend = $updateData['is_attends'][$applicantId] ?? null;

                // 處理房型更新
                $roomType = $updateData['room_types'][$applicantId];
                if ($roomType !== "") {
                    $this->updateApplicantLodging($applicant, $roomType, $fareRoom);
                }

                $applicant->save();
                $updateCount++;
            }
        } catch (\Exception $e) {
            logger($e);
        }

        return $updateCount;
    }

    /**
     * 更新 UT Camp 申請者資料
     */
    private function updateUtcampApplicants(array $updateData): int
    {
        $updateCount = 0;
        $applicants = Applicant::whereIn('id', $updateData['ids'])->get();

        try {
            foreach ($applicants as $applicant) {
                $applicant->deposit = $updateData['deposits'][$applicant->id];
                $applicant->save();
                $updateCount++;
            }
        } catch (\Exception $e) {
            logger($e);
        }

        return $updateCount;
    }

    /**
     * 更新申請者住宿資訊
     */
    private function updateApplicantLodging(Applicant $applicant, string $roomType, array $fareRoom): void
    {
        $lodging = $applicant->lodging;

        // 如果沒有住宿記錄，建立新的
        if (!$lodging) {
            $lodging = new Lodging();
            $lodging->applicant_id = $applicant->id;
        }

        // 更新房型、天數及應繳車資
        $roomTypeName = key(array_filter($fareRoom, fn ($v, $k) => $v[0] == $roomType, ARRAY_FILTER_USE_BOTH));

        if ($roomTypeName) {
            $lodging->room_type = $roomTypeName;
            $lodging->days = $fareRoom[$roomTypeName][1];
            $lodging->fare = $fareRoom[$roomTypeName][2];
            $lodging->save();

            // 更新付款資料
            $this->applicantService->fillPaymentData($applicant);
        }
    }
}
