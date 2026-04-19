<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Batch;
use App\Models\Applicant;
use App\Models\Traffic;
use App\Models\Lodging;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Services\LodgingService;
use App\Services\TrafficService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Mail\ApplicantMail;
use App\Mail\QueuedApplicantMail;
use App\Jobs\SendApplicantMail;
use App\Traits\EmailConfiguration;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CampController extends Controller
{
    use EmailConfiguration;

    protected $campDataService;
    protected $applicantService;
    protected $batch_id;
    protected $camp_info;
    protected $camp_data;
    //protected $admission_announcing_date_Weekday;
    //protected $admission_confirming_end_Weekday;
    protected $lodgingService;
    protected $trafficService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService, ApplicantService $applicantService, Request $request, LodgingService $lodgingService, TrafficService $trafficService)
    {
        $this->applicantService = $applicantService;
        $this->campDataService = $campDataService;
        $this->lodgingService = $lodgingService;
        $this->trafficService = $trafficService;
        // 營隊資料，存入 view 全域
        $this->batch_id = $request->route()->parameter('batch_id');
        $this->camp_info = $this->campDataService->getCampBatchInfo($this->batch_id);

        if (is_null($this->camp_info)) {
            // halt if no camp data found
            echo "查無營隊資料，請確認網址是否正確。" . "<br>";
            die();
        }
        //$this->camp_info = $this->camp_info['camp_info']; //no need?

        // 動態載入電子郵件設定
        $this->setEmail($this->camp_info->table, $this->camp_info->variant);

        //backward compatible
        $this->camp_data = $this->camp_info;

        View::share('batch_id', $this->batch_id);
        View::share('camp_info', $this->camp_info);
        View::share('camp_data', $this->camp_data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function campIndex()
    {
        if ($this->camp_data->site_url) {
            return redirect()->to($this->camp_data->site_url);
        }
        $now = Carbon::now();
        $registration_start = $this->camp_data->registration_start->startOfDay();
        if ($now->lt($registration_start)) {
            return '<div style="margin: atuo;">距離開始報名日，還有 <br><img src="http://s.mmgo.io/t/B7Aj" alt="motionmailapp.com" /></div>';
        }
    }

    public function campRegistration(Request $request)
    {
        $today = \Carbon\Carbon::today();
        if ($request->isBackend == "目前為後台報名狀態。") {
            $batch = Batch::find($request->batch_id);
        } else {
            $batch = Batch::find($this->batch_id);
        }
        if ($batch->is_late_registration_end) {
            $registration_end = $batch->late_registration_end->endOfDay();
        } else {
            $registration_end = $this->camp_data->registration_end->endOfDay();
        }
        $registration_start = $this->camp_data->registration_start->startOfDay();
        $final_registration_end = $this->camp_data->final_registration_end?->endOfDay() ?? \Carbon\Carbon::today();

        $fare_room = $this->lodgingService->getLodgingFare($this->camp_data, Carbon::today());
        [$fare_depart_from, $fare_back_to] = $this->trafficService->getTrafficFare($this->camp_data);

        if ($today > $registration_end && !isset($request->isBackend)) {
            //超過前台報名期限
            return view('camps.' . $this->camp_data->table . '.outdated')->with('outdatedMessage', '報名期限已過，敬請見諒。');
        } elseif (isset($request->isBackend) && $today > $final_registration_end) {
            //超過後台最終報名期限
            return view('camps.' . $this->camp_data->table . '.outdated')
            ->with('isBackend', '超出最終報名日。')
            ->with('outdatedMessage', '報名期限已過，敬請見諒。');
        } elseif ($today < $registration_start && !isset($request->isBackend)) {
            //尚未開放
            return view('camps.' . $this->camp_data->table . '.outdated')->with('outdatedMessage', '尚未開放報名。');
        } else {
            //prepare last_year_camps. may be null
            $last_year = $today->subYear(1)->year;
            $last_year_camps = Camp::select('camps.*')->with('batchs')
                    ->where('year', $last_year)
                    ->where('table', $this->camp_info->table)
                    ->get();

            return view('camps.' . $this->camp_info->table . '.form')
                ->with('isBackend', $request->isBackend)
                ->with('batch', Batch::find($request->batch_id))
                ->with('fare_room', $fare_room)
                ->with('fare_depart_from', $fare_depart_from)
                ->with('fare_back_to', $fare_back_to)
                ->with('last_year', $last_year)
                ->with('last_year_camps', $last_year_camps);
        }
    }

    public function campRegistrationMockUp(Request $request)
    {
        $today = \Carbon\Carbon::today();
        if ($request->isBackend == "目前為後台報名狀態。") {
            $batch = Batch::find($request->batch_id);
        } else {
            $batch = Batch::find($this->batch_id);
        }
        if ($batch->is_late_registration_end) {
            $registration_end = $batch->late_registration_end->endOfDay();
        } else {
            $registration_end = $this->camp_data->registration_end->endOfDay();
        }
        $registration_start = $this->camp_info->registration_start->startOfDay();
        $final_registration_end = $this->camp_info->final_registration_end?->endOfDay() ?? \Carbon\Carbon::today();
        $fare_room = $this->lodgingService->getLodgingFare($this->camp_info, Carbon::today());
        [$fare_depart_from, $fare_back_to] = $this->trafficService->getTrafficFare($this->camp_info);

        return view('camps.' . $this->camp_data->table . '.form_mockup')
            ->with('isBackend', $request->isBackend)
            ->with('batch', Batch::find($request->batch_id))
            ->with('fare_room', $fare_room)
            ->with('fare_depart_from', $fare_depart_from)
            ->with('fare_back_to', $fare_back_to);
    }

    public function campRegistrationFormSubmitted(Request $request)
    {
        // 檢查電子郵件是否一致
        if (isset($request->emailConfirm) && ($request->email != $request->emailConfirm)) {
            return view("errorPage")->with('error', '電子郵件不一致，請檢查是否輸入錯誤。');
        }

        if (!file_exists(storage_path("avatars"))) {
            mkdir(storage_path("avatars"), 777, true);
        }

        if ($request->birthdate != "") {
            // $request->birthdate: YYYY-MM-DD
            $formatted = Carbon::parse($request->birthdate);
            $request->request->add(['birthyear' => $formatted->year]);
            $request->request->add(['birthmonth' => $formatted->month]);
            $request->request->add(['birthday' => $formatted->day]);
        }

        if (!$request->region_id || $request->region_id == '') {
            if ($request->region != '') {
                $region = $this->camp_data->regions->where('name', $request->region)->first();
                if ($region) {
                    $request->request->add(['region_id' => $region->id]);
                }
            }
        }

        // 修改資料
        if (isset($request->applicant_id) && !isset($request->useOldData2Register)) {
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
            $formData = $this->campDataService->handleRegion($formData, $this->camp_data->table, $this->camp_data->id);

            try {
                $disk = \Storage::disk('local');
                $path = 'avatars/';
                if (request()->hasFile('avatar')) {
                    $file = request()->file('avatar');
                    $name = $file->hashName();
                }
                if (request()->hasFile('avatar_re')) {
                    $file = request()->file('avatar_re');
                    $name = $file->hashName();
                }

                if ($file ?? false) {
                    $disk->put($path, $file);
                    $image = Image::make(storage_path($path . $name))->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save(storage_path($path . $name));
                    $formData['avatar'] = $path . $name;
                }
            } catch (\Throwable $e) {
                logger($e);
            }

            $applicant = \DB::transaction(function () use ($formData) {
                if (isset($formData['is_educating'])) {
                    if ($formData['is_educating'] == 0) {
                        $formData['school_or_course'] = '';
                        $formData['subject_teaches'] = '';
                    }
                }
                $applicant = Applicant::where('id', $formData['applicant_id'])->first();
                $model = '\\App\\Models\\' . ucfirst($this->camp_info->table);
                $camp = $model::where('applicant_id', $formData['applicant_id'])->first();
                $applicantFillable = $applicant->getFillable();
                $campFillable = $camp->getFillable();
                $applicantData = array();
                $campData = array();
                foreach ($formData as $key => $value) {
                    if (in_array($key, $applicantFillable)) {
                        $applicantData[$key] = $value;
                    }
                    if (in_array($key, $campFillable)) {
                        $campData[$key] = $value;
                    }
                }
                $applicant->where('id', $formData['applicant_id'])->update($applicantData);
                $applicant->save();
                $camp->where('applicant_id', $formData['applicant_id'])->update($campData);
                $camp->save();

                return $applicant;
            });
            if (isset($formData['depart_from']) || isset($formData['back_to'])) {
                $this->trafficService->updateApplicantTraffic($applicant, $this->camp_info, $formData['depart_from'] ?? null, $formData['back_to'] ?? null);
            }
            if (isset($formData['room_type'])) {
                $this->lodgingService->updateApplicantLodging($applicant, $this->camp_info, $formData['room_type'], ($formData['nights'] ?? 1));
            }
            $applicant = $this->applicantService->fillPaymentData($applicant);
            $applicant->save();

            $isModify = 1;
            return view('camps.' . $this->camp_info->table . '.success', compact('applicant', 'isModify'));
        }
        // 營隊報名
        else {
            $applicant = Applicant::select('applicants.*')
                ->join($this->camp_info->table, 'applicants.id', '=', $this->camp_info->table . '.applicant_id')
                ->join('batchs', 'applicants.batch_id', '=', 'batchs.id')
                //->join('batchs', function($query) {
                //    $query->on('batchs.camp_id', '=', 'camps.id')
                //            ->on('batchs.id', '=', 'applicants.batch_id');
                //})
                //->join('camps', 'camps.id', '=', 'batchs.camp_id')
                ->where('batchs.camp_id', $this->camp_info->id)
                ->where('applicants.name', $request->name)
                ->where('email', $request->email)
                ->withTrashed()->first();
            if ($applicant) {
                if ($applicant->trashed()) {
                    $applicant->restore();
                }
                return view(
                    'camps.' . $this->camp_info->table . '.success',
                    //['isRepeat' => "已成功報名，請勿重複送出報名資料。",
                    ['isRepeat' => "您已報名過，請勿重複報名。底下顯示為您之前的報名序號。",
                    'applicant' => $applicant]
                );
            }
            if ($request->required_name || $request->required_filename) {
                \Sentry::captureMessage('異常報名資料');
                return response()->json([
                    'status' => 'success'
                ])->setStatusCode(200);
            } else {
                \Sentry::captureMessage('Registration from camp ID: ' . $this->camp_info->id);
                $request = $this->campDataService->checkBoxToArray($request);
                $formData = $request->toArray();
                $formData['batch_id'] = isset($formData["set_batch_id"]) ? $formData["set_batch_id"] : $this->batch_id;
                $formData = $this->campDataService->handleRegion($formData, $this->camp_info->table, $this->camp_info->id);

                try {
                    $disk = \Storage::disk('local');
                    $path = 'avatars/';
                    if (request()->hasFile('avatar')) {
                        $file = request()->file('avatar');
                        $name = $file->hashName();
                        $result = $disk->put($path, $file);
                        $image = Image::make(storage_path($path . $name))->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save(storage_path($path . $name));
                        $formData['avatar'] = $path . $name;
                    }
                } catch (\Throwable $e) {
                    logger($e);
                }

                // 報名資料開始寫入資料庫，使用 transaction 確保可以同時將資料寫入不同的表，
                // 或確保若其中一個步驟失敗，不會留下任何殘餘、未完整的資料（屍體）
                // $applicant 為最終報名資料
                $controller = $this;
                $applicant = \DB::transaction(function () use ($formData, $controller) {
                    DB::statement("SET SESSION sql_mode = '';");
                    $applicant = Applicant::create($formData);
                    $formData['applicant_id'] = $applicant->id;
                    $model = '\\App\\Models\\' . ucfirst($this->camp_info->table);
                    $model::create($formData);
                    if (isset($formData['depart_from']) || isset($formData['back_to'])) {
                        $this->trafficService->updateApplicantTraffic($applicant, $this->camp_info, $formData['depart_from'] ?? null, $formData['back_to'] ?? null);
                    }
                    if (isset($formData['room_type'])) {
                        $this->lodgingService->updateApplicantLodging($applicant, $this->camp_info, $formData['room_type'], $formData['nights'] ?? null);
                    }
                    if ($controller->camp_info->table == 'hcamp') {
                        $applicant = $controller->applicantService->fillPaymentData($applicant);
                        $applicant->save();
                    }
                    return $applicant;
                });
                // 寄送報名資料
                try {
                    // Mail::to($applicant)->send(new ApplicantMail($applicant, $this->camp_info));
                    SendApplicantMail::dispatch($applicant->id, $this->camp_info, false);
                } catch (\Exception $e) {
                    logger($e);
                }
            }
        }

        return view('camps.' . $this->camp_info->table . '.success')->with('applicant', $applicant);
    }

    public function campRegistrationFormCopy(Request $request)
    {
        //Ori：原本的
        //Copy：要複製去的
        $formData = $request->toArray();
        $batchOri = Batch::find($formData['batch_id_ori']);
        $campOri = $batchOri->camp;
        $modelOri = '\\App\\Models\\' . ucfirst($campOri->table);
        $campTableOri = $campOri->table;

        $batchCopy = Batch::find($formData['batch_id_copy']);
        $campCopy = $batchCopy->camp;
        $modelCopy = '\\App\\Models\\' . ucfirst($campCopy->table);

        $applicantIdOri = $formData['applicant_id_ori'];
        $applicantOri = Applicant::select('applicants.*', $campTableOri . '.*')
        ->join($campTableOri, 'applicants.id', '=', $campTableOri . '.applicant_id')
        ->where('applicants.id', $applicantIdOri)->withTrashed()->first();

        View::share('camp_info', $campCopy);    //replace camp_info
        $applicant_data = $applicantOri->toJson();
        $applicant_data = str_replace("\\r", "", $applicant_data);
        $applicant_data = str_replace("\\n", "", $applicant_data);
        $applicant_data = str_replace("\\t", "", $applicant_data);

        //先不複製，是把資料填到"campCopy"表中顯示，由user自己按報名。
        return view('camps.' . $campCopy->table . '.form')
        //->with('applicant_id', $applicantOri->applicant_id)
        //->with('batch_id', $applicantOri->batch_id)   //??
        ->with('applicant_data', $applicant_data)      //處理過一些空白字元的版本
        ->with('applicant', $applicantOri)             //保留這個:資料庫抓出的原始資料,已join
        ->with('applicant_raw_data', $applicantOri)    //之後刪除，但先保留以免其他地方用到
        ->with('isModify', true)
        ->with('useOldData2Register', true)                     //新增：使用舊資料報名
        ->with('batch', $batchCopy)
        ->with('camp_info', $campCopy)
        ->with('camp_data', $campCopy);
    }

    public function campQueryRegistrationDataPage(Request $request)
    {
        //$request->batch_id_from can be null
        return view('camps.' . $this->camp_info->table . '.query')
            ->with('batch_id_from', $request->batch_id_from);
    }

    /**
     * 查詢/修改報名資料
     * 如果從 query 頁選擇查詢報名資料，則可跨梯次查詢資料，但無法再按下修改資料
     * 如果從 query 頁選擇修改報名資料，則可跨梯次修改資料
     *
     */
    public function campViewRegistrationData(Request $request)
    {
        $campTable = $this->camp_info->table;
        $formPath = "camps.{$campTable}" . (in_array($campTable, ['ecamp', 'ceocamp']) ? '.form_bak' : '.form');

        // 1. 取得報名者資料 (封裝邏輯以減少重複的 try-catch)
        try {
            [$applicant, $applicant_data] = $this->getApplicantByRequest($request, $campTable);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['找不到報名資料，請確認查詢欄位或序號是否正確。']);
        }

        // 2. 驗證營隊所屬權限
        if (!$applicant || $applicant->batch->camp_id != $this->camp_info->id) {
            return redirect()->back()->withErrors(['找不到報名資料，請確認查詢欄位及查詢營隊是否正確。']);
        }
        // 如果需排除特定欄位
        // return back()->withInput($request->except('password'));

        // 3. 準備基礎資料與費率計算
        $isModify = (bool)$request->isModify;
        $batch = Batch::find($request->batch_id);
        $fare_room = $this->lodgingService->getLodgingFare($this->camp_info, $applicant->created_at);
        [$fare_depart_from, $fare_back_to] = $this->trafficService->getTrafficFare($this->camp_info);

        // 4. 檢查修改期限
        //如果沒有設定修改期限，則預設為當天，讓使用者無法修改資料，因為用lt而非lte
        $deadline = $this->camp_info->modifying_deadline ?? Carbon::now();
        $isExpired = $isModify && $deadline->lt(Carbon::today());
        $errorMsg = null;

        if ($isExpired) {
            $isModify = false; // 強制關閉修改權限
            $errorMsg = '很抱歉，報名資料修改期限已過。';
            // 若非來自查詢頁面，直接擋掉
            if (!Str::contains(request()->headers->get('referer'), 'queryview')) {
                return back()->withInput()->withErrors([$errorMsg]);
            }
        }

        // 5. 彙整要傳送到 View 的變數
        $viewData = [
            'applicant_id'       => $applicant->id,
            'applicant_data'     => $applicant_data,
            'applicant'          => $applicant, //保留這個
            'applicant_raw_data' => $applicant, //之後刪除，但先保留以免其他地方用到
            'batch_id'           => $applicant->batch_id,
            'batch'              => $batch,
            //'camp_info'          => $this->camp_info, //已在建構子用 View::share 全域傳了，這邊就不需要了
            //'camp_data'          => $this->camp_data, //己在建構子用 View::share 全域傳了，這邊就不需要了
            'isModify'           => $isModify,
            'isBackend'          => $request->isBackend,
            'fare_room'          => $fare_room,
            'fare_depart_from'   => $fare_depart_from,
            'fare_back_to'       => $fare_back_to,
        ];

        // 處理特殊來源營隊 (如轉載或跨營隊資訊)
        if ($request->batch_id_from) {
            $batchFrom = Batch::find($request->batch_id_from);
            $campFrom = $batchFrom->camp;
            $viewData['camp_info'] = $campFrom;
            $viewData['camp_data'] = $campFrom;
            $viewData['batch_id_from'] = $request->batch_id_from;
            $viewData['camp_abbr_from'] = $campFrom->abbreviation;
        }

        $view = view($formPath, $viewData);
        return $errorMsg ? $view->withErrors([$errorMsg]) : $view;
    }

    /**
     * 輔助方法：根據請求來源判定查詢方式
     */
    private function getApplicantByRequest($request, $campTable)
    {
        $referer = request()->headers->get('referer');
        $isSafeSource = Str::contains($referer, ['submit', 'queryupdate', 'queryview']);

        if ($request->name && $request->sn) {
            return $this->applicantService->getApplicantData($request->sn, $campTable, $request->name);
        } elseif ($isSafeSource && $request->sn) {
            return $this->applicantService->getApplicantData($request->sn, $campTable);
        }

        throw new ModelNotFoundException();
    }

    public function campGetApplicantSN(Request $request)
    {
        $campTable = $this->camp_info->table;
        $applicant = Applicant::select('applicants.id', 'applicants.email', 'applicants.name')
                    ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                    ->where('batch_id', $this->batch_id)
                    ->where('applicants.name', $request->name);
        if ($request->mobile) {
            //姓名＋手機
            $applicant = $applicant->where('mobile', $request->mobile)
                ->withTrashed()->first();
        } else {
            $applicant = $applicant->where('birthyear', ltrim($request->birthyear, '0'))
            ->where('birthmonth', ltrim($request->birthmonth, '0'));
            if ($campTable == 'acamp' || $campTable == 'ceocamp') {
                //姓名＋出生年月
                $applicant = $applicant->withTrashed()->first();
            } else {
                //姓名＋出生年月日
                $applicant = $applicant->where('birthday', ltrim($request->birthday, '0'))
                ->withTrashed()->first();
            }
        }

        $viewPathCamp = 'camps.' . $campTable . '.getSN';
        $viewPathGeneral = 'components.general.getSN';
        $viewPath = View::exists($viewPathCamp) ? $viewPathCamp : $viewPathGeneral;

        if ($applicant) {
            // 寄送報名序號
            // Mail::to($applicant)->send(new ApplicantMail($applicant, $this->camp_info, true));
            SendApplicantMail::dispatch($applicant->id, $this->camp_info, true);    //isGetSN=true
            return view($viewPath, compact('applicant'));
        } else {
            return view($viewPath)
                ->with('error', "找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。");
        }
    }

    public function campViewAdmission()
    {
        $camp = $this->camp_info;
        return view('camps.' . $this->camp_info->table . ".queryadmission", compact('camp'));
    }

    public function campConfirmCancel(Request $request)
    {
        $applicant = Applicant::where('id', $request->sn)
                        ->where('name', $request->name)
                        ->where('idno', $request->idno)
                        ->withTrashed()
                        ->first();
        if ($applicant) {
            return view('camps.' . $this->camp_info->table . '.confirm_cancel', compact('applicant'));
        } else {
            return back()->withInput()->withErrors(["找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。"]);
        }
    }

    public function campCancellation(Request $request)
    {
        try {
            if (Applicant::find($request->sn)->delete()) {
                return view('camps.' . $this->camp_info->table . '.cancel_successful');
            }
        } catch (\Exception $e) {
            logger($e);
            return "<h2>取消時發生未預期錯誤，請確認報名資料是否正確，或向主辦方回報。</h2>";
        }
    }

    public function restoreCancellation(Request $request)
    {
        if (Applicant::withTrashed()->find($request->sn)->restore()) {
            $applicant = Applicant::find($request->sn);
            return view('camps.' . $this->camp_info->table . '.restore_successful', compact('applicant'));
        }
        return "<h2>回復時發生未預期錯誤，請向主辦方回報。</h2>";
    }

    public function campQueryAdmission(Request $request)
    {
        $campTable = $this->camp_info->table;

        $request->validate([
            'name' => 'required',
            'sn' => 'required|integer'
        ]);

        if ($request->name != null && $request->sn != null) {
            try {
                [$applicant, $applicant_data] = $this->applicantService->getApplicantData($request->sn, $campTable, $request->name);
            } catch (ModelNotFoundException $e) {
                return redirect()->back()->withErrors(['找不到報名資料，請確認查詢欄位是否填寫正確，或者是否已成功報名。']);
            }
        }

        // try-catch已處理applicant是否存在
        // 但仍需確認找到的applicant是否報名本營隊
        if ($applicant && $applicant->batch->camp_id == $this->camp_info->id) {
            //使用報名者的報名日期來計算費率，避免修改資料後費率變動的問題
            $fare_room = $this->lodgingService->getLodgingFare($this->camp_info, $applicant->created_at);
            [$fare_depart_from, $fare_back_to] = $this->trafficService->getTrafficFare($this->camp_info);

            if (is_null($applicant->second_bank_barcode)) {
                $applicant = $this->applicantService->fillPaymentData($applicant);
            }
            $applicant = $this->applicantService->checkPaymentStatus($applicant);
            $this->camp_info->content_link_chn = $this->camp_info->dynamic_stats?->where('purpose', 'admittedMail_chn')?->first()?->google_sheet_url ?? [];
            return view(
                'camps.' . $campTable . ".admissionResult",
                compact('applicant', 'applicant_data', 'fare_room', 'fare_depart_from', 'fare_back_to')
            );
        } else {
            return back()->withInput()->withErrors(["找不到報名資料，請確認查詢欄位是否填寫正確，或者是否已成功報名。"]);
        }
    }

    public function showDownloads()
    {
        return view('camps.' . $this->camp_info->table . '.downloads');
    }

    public function downloadPaymentForm(Request $request)
    {
        ini_set('memory_limit', -1);
        $applicant = Applicant::find($request->applicant_id);
        $applicant = $this->applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->save();
        return \PDF::loadView('camps.' . $this->camp_info->table . '.paymentFormPDF', compact('applicant'))->setPaper('a3')->download('Payment_' . \Carbon\Carbon::now()->format('YmdHis') . $applicant->id . '.pdf');
    }

    public function downloadCheckInNotification(Request $request)
    {
        $applicant = Applicant::find($request->applicant_id);
        return \PDF::loadView('camps.' . $this->camp_info->table . '.checkInMail', compact('applicant'))->download(\Carbon\Carbon::now()->format('YmdHis') . $this->camp_info->table . $applicant->id . '報到通知單.pdf');
    }

    public function downloadCheckInQRcode(Request $request)
    {
        $applicant = Applicant::find($request->applicant_id);
        $qr_code = \DNS2D::getBarcodePNG('{"applicant_id":' . $applicant->id . '}', 'QRCODE');
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($applicant->batch->camp->fullName . ' QR code 報到單<br>梯次：' . $applicant->batch->name . '<br>錄取序號：' . $applicant->group . $applicant->number . '<br>姓名：' . $applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
        return $pdf->download(\Carbon\Carbon::now()->format('YmdHis') . $this->camp_info->table . $applicant->id . 'QR Code 報到單.pdf');
    }

    public function getCampTotalRegisteredNumber()
    {
        $batches = Batch::where('camp_id', $this->camp_info->id)->get()->pluck('id');
        return Applicant::whereIn('batch_id', $batches)->withTrashed()->count();
    }

    public function toggleAttend(Request $request)
    {
        $applicant = Applicant::find($request->id);
        //other camps
        if ($request->camp == "ycamp") {
            if ($request->cancel) {
                $applicant->is_attend = 0;
            } else {
                $applicant->is_attend = 1;
            } //reconfirm
        } else {
            if ($request->confirmation_no) {
                $applicant->is_attend = 0;
            } else {
                $applicant->is_attend = !isset($applicant->is_attend) ? 1 : !$applicant->is_attend;
            }
        }
        $applicant->save();
        $applicant = $this->applicantService->checkPaymentStatus($applicant);
        return redirect(route('showadmit', ['batch_id' => $applicant->batch_id, 'sn' => $applicant->id, 'name' => $applicant->name]));
    }

    public function toggleAttendBackend(Request $request)
    {
        $applicant = Applicant::find($request->id);
        $applicant->is_attend = $request->is_attend;
        $applicant->save();
        $applicant = $this->applicantService->checkPaymentStatus($applicant);
        $applicant->refresh();
        return redirect()->back();
    }

    public function modifyTraffic(Request $request)
    {
        $applicant = Applicant::findOrFail($request->applicant_id);
        $camp_table = $this->camp_info->table;

        // 呼叫 Service
        $updatedApplicant = $this->trafficService->updateApplicantTraffic(
            $applicant,
            $this->camp_info,
            $request->depart_from,
            $request->back_to
        );

        // 這裡處理 Controller 該做的「跳轉」責任
        return redirect(route('showadmit', [
            'batch_id' => $updatedApplicant->batch_id,
            'sn' => $updatedApplicant->id,
            'name' => $updatedApplicant->name
        ]));
    }

    public function modifyLodging(Request $request)
    {
        $applicant = Applicant::findOrFail($request->applicant_id);
        $camp_table = $this->camp_info->table;

        // 呼叫 Service
        $updatedApplicant = $this->lodgingService->updateApplicantLodging(
            $applicant,
            $this->camp_info,
            $request->room_type,
            $request->nights
        );

        // 這裡處理 Controller 該做的「跳轉」責任
        return redirect(route('showadmit', [
            'batch_id' => $updatedApplicant->batch_id,
            'sn' => $updatedApplicant->id,
            'name' => $updatedApplicant->name
        ]));
    }
    public function modifyAfterAdmitted(Request $request)
    {
        $applicant = Applicant::findOrFail($request->applicant_id);
        $campTable = $this->camp_info->table;
        $campId = $this->camp_info->id;

        \DB::transaction(function () use ($applicant, $request, $campTable, $campId) {
            // 呼叫 Service
            $this->lodgingService->updateApplicantLodging(
                $applicant,
                $this->camp_info,    //string, e.g. "ycamp"
                $request->room_type,
                $request->nights
            );

            // 呼叫 Service
            $this->trafficService->updateApplicantTraffic(
                $applicant,
                $this->camp_info,    //string, e.g. "ycamp"
                $request->depart_from,
                $request->back_to
            );

            $this->applicantService->fillPaymentData($applicant);
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
            $formData = $this->campDataService->handleRegion($formData, $campTable, $campId);

            // 呼叫 Service
            $this->applicantService->updateApplicantXCamp(
                $applicant,
                $campTable,    //string, e.g. "ycamp"
                $formData
            );
        });

        // 這裡處理 Controller 該做的「跳轉」責任
        return redirect(route('showadmit', [
            'batch_id' => $applicant->batch_id,
            'sn' => $applicant->id,
            'name' => $applicant->name
        ]));
    }

    public function showCampPayment()
    {
        return view('camps.' . $this->camp_info->table . '.payment');
    }

    public function returnBatches()
    {
        return;
    }
}
