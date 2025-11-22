<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CampOrg;
use App\Models\CarerApplicantXref;
use App\Models\CheckIn;
use App\Models\ContactLog;
use App\Models\GroupNumber;
use App\Models\Lodging;
use App\Models\OrgUser;
use App\Models\Region;
use App\Models\SignInSignOut;
use App\Models\Traffic;
use App\Models\User;
use App\Models\Vcamp;
use App\Models\Volunteer;
use App\Services\ApplicantService;
use App\Services\BackendService;
use App\Services\CampDataService;
use App\Services\GSheetService;
use App\Imports\ApplicantsImport;
use App\Exports\ApplicantsExport;
use App\Traits\EmailConfiguration;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\CheckResourceAccessJob;
use Illuminate\Support\Facades\Bus;
use View;

class BackendController extends Controller
{
    use EmailConfiguration;

    protected $campDataService;
    protected $applicantService;
    protected $backendService;
    protected $gsheetService;
    protected $batch_id;
    protected $camp_data;
    protected $batch;
    protected $has_attend_data;
    protected $usePermissionOptimization = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CampDataService $campDataService,
        ApplicantService $applicantService,
        BackendService $backendService,
        GSheetService $gsheetService,
        Request $request
    ) {
        $this->middleware('auth');
        $this->campDataService = $campDataService;
        $this->applicantService = $applicantService;
        $this->backendService = $backendService;
        $this->gsheetService = $gsheetService;

        if ($request->route()->parameter('batch_id')) {
            // 營隊資料，存入 view 全域
            $this->batch_id = $request->route()->parameter('batch_id');
            $this->camp_data = $this->campDataService->getCampData($this->batch_id)['camp_data'];
            $this->batch = Batch::find($request->route()->parameter('batch_id'));
            View::share('batch', $this->batch);
            View::share('batch_id', $this->batch_id);
            View::share('camp_data', $this->camp_data);
            if ($this->camp_data->table == 'ycamp' || $this->camp_data->table == 'acamp') {
                if (
                    $this->camp_data->admission_confirming_end &&
                    Carbon::now()->gt($this->camp_data->admission_confirming_end)
                ) {
                    $this->has_attend_data = true;
                }
            }
            $camp = $this->camp_data;
            if (!$camp) {
                echo "無此營隊";
                die();
            }
        }
        if ($request->route()->parameter('camp_id')) {
            $this->middleware('permitted');
            $this->camp_id = $request->route()->parameter('camp_id');
            $this->campFullData = Camp::find($request->route()->parameter('camp_id'));
            View::share('camp_id', $this->camp_id);
            View::share('campFullData', $this->campFullData);
            if ($this->campFullData && ($this->campFullData->table == 'ycamp' || $this->campFullData->table == 'acamp')) {
                if ($this->campFullData->admission_confirming_end && Carbon::now()->gt($this->campFullData->admission_confirming_end)) {
                    $this->has_attend_data = true;
                }
            }
            $camp = $this->campFullData;
            if (!$camp) {
                echo "無此營隊";
                die();
            }
        }
        if (\Str::contains(url()->current(), "campManage")) {
            $this->middleware('admin');
        }
        if ($camp ?? false) {
            $this->persist(camp: $camp);
        }
    }

    public function persist(...$args)
    {
        $that = $this;
        // https://laracasts.com/discuss/channels/laravel/authuser-return-null-in-construct
        $this->middleware(function ($request, $next) use ($that, $args) {
            $that->user = \App\Models\User::find(auth()->user()->id);
            $that->isVcamp = str_contains($args["camp"], "vcamp");
            if ($that->user->roles()->where("camp_id", $this->campFullData->id)->count() == 1 &&
               $that->user->roles()->where("camp_id", $this->campFullData->id)->where("position", "like", "%關懷小組%")->count()) {
                $that->user->no_panel = true;
            }
            View::share('currentUser', $that->user);
            return $next($request);
        });
        // 動態載入電子郵件設定
        self::setEmail($args["camp"]->table, $args["camp"]->variant);
    }

    /**
     * 營隊選單、登入後顯示的畫面
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function masterIndex()
    {
        // 檢查權限
        $permission = auth()->user()->getPermission('all');
        $camps = $this->campDataService->getAvailableCamps($permission);
        $newPermissions = OrgUser::where('user_id', \Auth::user()->id)->get();
        $camps2 = [];
        $newPermissions->each(function ($p) use (&$camps2) {
            if ($p->camp) {
                in_array($p->camp->id, $camps2, false) ?: array_push($camps2, $p->camp->id);
            }
        });
        $camps2 = Camp::whereIn('id', $camps2)->orderBy('id', 'desc')->get();
        $camps2->each(function ($camp) use (&$camps) {
            $camps[] = $camp;
        });
        return view('backend.MasterIndex')->with("camps", $camps);
    }

    public function campIndex()
    {
        return view('backend.campIndex');
    }

    public function admission(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $message = null;
        $error = null;
        if ($request->isMethod('POST')) {
            $candidate = Applicant::find($request->id);
            if ($request->get("clear") == "清除錄取序號") {
                $this->backendService->setAdmitted($candidate, 0);
                $this->backendService->removeAdmittedNumber($candidate);
                $message = "錄取序號已清除。";
            } else {
                $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->admittedSN);
                $group = $groupAndNumber['group'];
                $number = $groupAndNumber['number'];
                $check = $this->applicantService->fetchApplicantData(
                    $this->campFullData->id,
                    $this->campFullData->table,
                    group: $group,
                    number: $number,
                );
                if ($check) {
                    $candidate = $check;
                    $candidate = $this->applicantService->Mandarization($candidate);
                    $error = "報名序號重複。";
                    return view('backend.registration.showCandidate', compact('candidate', 'error'));
                }
                $this->backendService->setAdmitted($candidate, 1);
                if ($this->backendService->setGroup($candidate, $group)) {
                    $this->backendService->setNumber($candidate, $number);
                    $this->applicantService->fillPaymentData($candidate);
                    $message = "錄取完成。";
                } else {
                    $error = "錄取失敗，請檢查學員組數是否已達上限無法再新增。";
                }
            }
            $candidate = $this->applicantService->fetchApplicantData(
                $this->campFullData->id,
                $this->campFullData->table,
                idOrName: $candidate->id,
            );
            $candidate = $this->applicantService->Mandarization($candidate);
            return view('backend.registration.showCandidate', compact('candidate', 'message', 'error'));
        } else {
            $candidates = Applicant::select('applicants.*')
            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $this->campFullData->id);
            $count = $candidates->count();
            $admitted = $candidates->where('is_admitted', 1)->count();
            return view('backend.registration.admission', compact('count', 'admitted'));
        }
    }

    public function showPaymentForm($camp_id, $applicant_id)
    {
        $applicant = Applicant::find($applicant_id);
        $applicant = $this->applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->save();
        $download = $_GET['download'] ?? false;
        if (!$download) {
            return view('camps.' . $applicant->batch->camp->table . '.paymentForm', compact('applicant', 'download'));
        } else {
            return \PDF::loadView('camps.' . $applicant->batch->camp->table . '.paymentFormPDF', compact('applicant'))->setPaper('a3')->download(Carbon::now()->format('YmdHis') . $applicant->batch->camp->table . $applicant->id . '.pdf');
        }
    }

    public function batchAdmission(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        if ($request->isMethod('POST')) {
            $error = array();
            $message = array();
            $applicants = array();
            if (!isset($request->id)) {
                return "沒有輸入任何欄位，請回上上頁重新整理後再重試。";
            }
            $batches = Batch::where("camp_id", $this->camp_id)->get()->pluck("id");
            foreach ($request->id as $key => $id) {
                if (!$id) {
                    array_push($error, "第 " . ($key + 1) . " 筆資料遺失，請回上上頁重新整理後再重試。");
                    continue;
                }
                $skip = false;
                $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->admittedSN[$key]);
                $group = $groupAndNumber['group'];
                $number = $groupAndNumber['number'];
                $check = Applicant::select('applicants.*')
                ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                ->whereIn("batch_id", $batches)
                ->whereHas('numberRelation', function ($query) use ($number) {
                    $query->where('number', $number);
                })
                ->whereHas('groupRelation', function ($query) use ($group) {
                    $query->where('alias', $group);
                })
                ->first();
                $candidate = Applicant::withTrashed()->find($id);
                if ($check) {
                    array_push($error, $candidate->name . "，錄取序號" . $request->admittedSN[$key] . "重複，沒有針對此人執行任何動作。");
                    $skip = true;
                }
                if (!$candidate) {
                    array_push($error, "報名序號" . $id . "不存在，沒有針對此報名序號執行任何動作。");
                    $skip = true;
                }
                if ($candidate->deleted_at) {
                    array_push($error, $candidate->name . "，報名序號" . $id . "已取消報名，沒有針對此人執行任何動作。");
                    $skip = true;
                }
                if (!$skip) {
                    $this->backendService->setAdmitted($candidate, 1);
                    if ($this->backendService->setGroup($candidate, $group)) {
                        $this->backendService->setNumber($candidate, $number);
                        $candidate = $this->applicantService->fillPaymentData($candidate);
                        $applicant = $candidate->save();
                        array_push($message, $candidate->name . "，錄取序號" . $request->admittedSN[$key] . "錄取完成。");
                    } else {
                        array_push($error, $candidate->name . "，報名序號" . $id . "錄取失敗，請檢查學員組數是否已達上限無法再新增。");
                    }
                }
                $applicant = $this->applicantService->Mandarization($candidate);
                array_push($applicants, $applicant);
            }
            return view('backend.registration.showBatchCandidate', compact('applicants', 'error', 'message'));
        } else {
            $candidates = Applicant::select('applicants.*')
            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $this->camp_id);
            $count = $candidates->count();
            $admitted = $candidates->where('is_admitted', 1)->count();
            return view('backend.registration.batchAdmission', compact('count', 'admitted'));
        }
    }

    public function showBatchCandidate(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $applicants = explode(",", $request->snORadmittedSN);
        foreach ($applicants as &$applicant) {
            $groupAndNumber = $this->applicantService->groupAndNumberSeperator($applicant);
            $group = $groupAndNumber['group'];
            $number = $groupAndNumber['number'];
            $candidate = $this->applicantService->fetchApplicantData($this->campFullData->id, $this->campFullData->table, $applicant, $group, $number);
            if ($candidate) {
                $applicant = $this->applicantService->Mandarization($candidate);
            } else {
                $id = $applicant;
                $applicant = collect();
                $applicant->id = $id;
                $applicant->applicant_id = $id;
                $applicant->batch_id = "";
                $applicant->name = "無資料";
                $applicant->gender = "N/A";
                $applicant->region = "--";
                $applicant->group = "-";
                $applicant->number = "-";
            }
        }

        return view('backend.registration.showBatchCandidate', compact('applicants'));
    }

    public function showCandidate(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $result = [];
        $candidate = null;
        if (str_contains($request->snORadmittedSNorName, ',')) {
            $candidates = array_unique(explode(',', $request->snORadmittedSNorName));
            foreach ($candidates as $c) {
                if ($c == "") {
                    continue;
                }
                $groupAndNumber = $this->applicantService->groupAndNumberSeperator($c);
                $group = $groupAndNumber['group'];
                $number = $groupAndNumber['number'];
                $candidate = $this->applicantService->fetchApplicantData($this->campFullData->id, $this->campFullData->table, $c, $group, $number);
                if ($candidate) {
                    $result[] = $this->applicantService->Mandarization($candidate);
                } else {
                    $result[] = "報名序號 {$c} 已取消或查無此學員";
                }
            }
            $candidate = null;
        } else {
            $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->snORadmittedSNorName);
            $group = $groupAndNumber['group'];
            $number = $groupAndNumber['number'];
            $candidate = $this->applicantService->fetchApplicantData($this->campFullData->id, $this->campFullData->table, $request->snORadmittedSNorName, $group, $number);
            if ($candidate) {
                $candidate = $this->applicantService->Mandarization($candidate);
            } else {
                return "<h3>學員已取消或查無此學員</h3>";
            }
        }

        if (isset($request->change)) {
            $batches = Batch::where('camp_id', $this->campFullData->id)->get();
            $regions = $this->campFullData->regions;
            return view('backend.registration.changeBatchOrRegionForm', compact('candidate', 'batches', 'regions', 'result'));
        }
        //修改繳費資料/現場手動繳費
        if (\Str::contains(request()->headers->get('referer'), 'accounting')) {
            //checkPaymentStatus() 檢查完繳費狀況後會 return applicant
            $applicant = $this->applicantService->checkPaymentStatus($candidate);
            $camp_table = $applicant->batch->camp->table;
            $fare_depart_from = config('camps_payments.fare_depart_from.' . $camp_table) ?? [];
            $fare_back_to = config('camps_payments.fare_back_to.' . $camp_table) ?? [];
            $fare_room = config('camps_payments.fare_room.' . $camp_table) ?? [];
            return view('backend.modifyAccounting', compact('applicant', 'fare_depart_from', 'fare_back_to', 'fare_room'));
        }
        //設定取消參加
        if (\Str::contains(request()->headers->get('referer'), 'modifyAttend') || (\Str::contains(request()->headers->get('referer'), 'modifyAttend') && $request->isMethod("GET"))) {
            $candidate = $this->applicantService->checkPaymentStatus($candidate);
            return view('backend.modifyAttend', ['applicant' => $candidate]);
        }

        return view('backend.registration.showCandidate', compact('candidate'));
    }

    public function showRegistration()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $user_batch_or_region = null;
        //        if($this->campFullData->table == 'ecamp' && auth()->user()->getPermission('all')->first()->level > 2){
        //            $user_batch_or_region = Batch::where('camp_id', $this->campFullData->id)->where('name', 'like', '%' . auth()->user()->getPermission(true, $this->campFullData->id)->region . '%')->first();
        //            $user_batch_or_region = $user_batch_or_region ?? "empty";
        //        }
        return view('backend.registration.registration', compact('user_batch_or_region'));
    }

    public function showRegistrationUpload()
    {
        //權限??
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $batches = $this->campFullData->batchs;
        return view('backend.registration.registrationUpload', compact('batches'));
    }

    public function registrationUpload(Request $request)
    {
        //$camp_id = $this->campFullData->id;
        $camp = $this->campFullData;
        $batches = $this->campFullData->batchs;
        $batch_id = $request->batch_id;
        $table = $camp->table;

        $titles_map = array_merge(config('camps_fields.general'), config('camps_fields.' . $table) ?? []);
        $titles_flip = array_flip($titles_map);

        //imported information, all sheets
        $allsheets = Excel::toCollection(new ApplicantsImport(), $request->fn_registration_upload);
        $sheet = $allsheets[0];     //1st sheet
        $titles_chn = $sheet[0];    //title row
        $num_rows = $sheet->count();
        $num_cols = $titles_chn->count();

        //chinese 2 english
        for ($i = 0; $i < $num_cols; $i++) {
            $titles[$i] = $titles_flip[$titles_chn[$i]] ?? "";
        }
        $create_count = 0;
        $update_count = 0;
        $title_data = array();

        try {
            for ($i = 1; $i < $num_rows; $i++) {
                $data = $sheet[$i];
                for ($j = 0; $j < $num_cols; $j++) {
                    $title_data[$titles[$j]] = $data[$j];
                }
                $title_data['batch_id'] = $batch_id;
                $title_data = $this->applicantService->convertFormat($title_data, $camp);

                $applicant = Applicant::select('applicants.*')
                    ->where('batch_id', $title_data['batch_id'])
                    ->where('name', $title_data['name'])
                    ->where('email', $title_data['email'])
                    ->first();

                if ($applicant) {   //if exist, update
                    //$applicant->group_id = $title_data['group_id'];
                    //$applicant->region = $title_data['region'];
                    $applicant->update($title_data);
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
            }
        } catch (\Exception $e) {
            \logger($e->getMessage());
            $message = "資料庫寫入錯誤";
            return view('backend.registration.registrationUpload', compact('batches', 'message', 'create_count', 'update_count'));
        }
        $message = "資料庫寫入成功";
        //$stat['create'] = $create_count;
        //$stat['update'] = $update_count;
        //dd($stat);
        return view('backend.registration.registrationUpload', compact('batches', 'message', 'create_count', 'update_count'));
        //dd("to be implemented");
    }

    public function showRegistrationList()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        /*if ($this->campFullData->table == 'ycamp' || $this->campFullData->table == 'yvcamp') {
            //2694-2716是輔導組
            if (count($this->user->roles->whereBetween('id',[2397,2398]))==0 &&count($this->user->roles->whereBetween('id',[2694,2716]))==0 &&  $this->user->id > 2) {
                return "<h3>大專營：只有輔導組幹部有權限。</h3>";
            }
        }*/
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        $camp = Camp::find($this->campFullData->id);
        $regions = $camp->regions;
        return view('backend.registration.list', compact('batches', 'regions'));
    }

    public function getRegistrationList(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }

        ini_set('max_execution_time', 1200);

        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        //change to this? $batches = $this->campFullData->batchs;
        if (isset($request->region)) {
            $query = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)->withTrashed();
            if ($request->region == '全區') {
                $applicants = $query->get();
            } elseif ($request->region == '其他') {
                if ($this->campFullData->table == 'ceocamp' || $this->campFullData->table == 'ceovcamp') {
                    $applicants = $query->whereNotIn('region', ['北區', '竹區', '中區', '高區'])->get();
                } elseif ($this->campFullData->table == 'acamp') {
                    $applicants = $query->whereNotIn('region', ['北苑', '北區', '基隆', '桃區', '竹區', '中區', '雲嘉', '台南', '高屏'])->get();
                } elseif ($this->campFullData->table == 'ecamp') {
                    $applicants = $query->whereNotIn('region', ['台北', '桃園', '新竹', '中區', '雲嘉', '台南', '高區'])->get();
                } else {
                    $applicants = $query->whereNotIn('region', ['台北', '桃園', '新竹', '台中', '雲嘉', '台南', '高雄'])->get();
                }
            } else {
                $applicants = $query->where('region', $request->region)->get();
            }
            $query = $request->region;
        } elseif (isset($request->school_or_course)) {
            //教師營使用 school_or_course 欄位
            $applicants = Applicant::select("applicants.*", "tcamp.*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join('tcamp', 'applicants.id', '=', 'tcamp.applicant_id')
                            ->where('camps.id', $this->campFullData->id);
            if ($request->school_or_course == "無") {
                $applicants = $applicants->where(function ($q) {
                    $q->where('school_or_course', "")
                    ->orWhereNull('school_or_course');
                });
            } else {
                $applicants = $applicants->where('school_or_course', $request->school_or_course);
            }
            $applicants = $applicants->withTrashed()->get();
            $query = $request->school_or_course;
        } elseif (isset($request->education)) {
            //快樂營使用 education 欄位
            $applicants = Applicant::select("applicants.*", "hcamp.*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join('hcamp', 'applicants.id', '=', 'hcamp.applicant_id')
                            ->where('camps.id', $this->campFullData->id)
                            ->where('education', $request->education)
                            ->withTrashed()->get();
            $query = $request->education;
        } elseif (isset($request->batch)) {
            $applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)
                        ->where('batchs.name', $request->batch)
                        ->withTrashed()->get();
            $query = $request->batch . '梯';
        } else {
            $applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                            ->where('camps.id', $this->campFullData->id)
                            ->where('address', "like", "%" . $request->address . "%")
                            ->withTrashed()->get();
            $query = $request->address;
        }
        if ($request->show_cancelled) {
            $query .= "(已取消)";
            $applicants = $applicants->whereNotNull('deleted_at');
        }

        //----- 為了顯示需要而新增的欄位 -----
        foreach ($applicants as $applicant) {
            $applicant->id = $applicant->sn;
            if ($applicant->fee > 0) {
                if ($applicant->fee - $applicant->deposit <= 0) {
                    $applicant->is_paid = "是";
                } else {
                    $applicant->is_paid = "否";
                }
            } else {
                $applicant->is_paid = "無費用";
            }
            if ($applicant->trashed()) {
                $applicant->is_cancelled = "是";
            } else {
                $applicant->is_cancelled = "否";
            }
        }
        //----- 報名名單不以繳費與否排序 -----
        // $applicants = $applicants->sortByDesc('is_paid');
        if ($request->orderByCreatedAtDesc) {
            $applicants = $applicants->sortByDesc('created_at');
        }
        // todo: fcb440ab06a18ae2baa4dc39bad6ed04a9e79455 把原先的權限過濾拿掉了，後續要考慮加回來
        //----- 處理「下載」：開始 -----
        if (isset($request->download)) {
            if ($applicants) {
                // 參加者報到日期
                $checkInDates = CheckIn::select('check_in_date')->whereIn('applicant_id', $applicants->pluck('sn'))->groupBy('check_in_date')->get();
                if ($checkInDates) {
                    $checkInDates = $checkInDates->toArray();
                } else {
                    $checkInDates = array();
                }
                $checkInDates = \Arr::flatten($checkInDates);
                foreach ($checkInDates as $key => $checkInDate) {
                    unset($checkInDates[$key]);
                    $checkInDates[(string)$checkInDate] = $checkInDate;
                }
                // 各梯次報到日期填充
                $batches = Batch::whereIn('id', $applicants->pluck('batch_id'))->get();
                foreach ($batches as $batch) {
                    $date = Carbon::createFromFormat('Y-m-d', $batch->batch_start);
                    $endDate = Carbon::createFromFormat('Y-m-d', $batch->batch_end);
                    while (1) {
                        if ($date > $endDate) {
                            break;
                        }
                        $str = $date->format('Y-m-d');
                        if (!in_array($str, $checkInDates)) {
                            $checkInDates = array_merge($checkInDates, [$str => $str]);
                        }
                        $date->addDay();
                    }
                }
                // 按陣列鍵值升冪排列
                ksort($checkInDates);
                $checkInData = array();
                // 將每人每日的報到資料按報到日期組合成一個陣列
                foreach ($checkInDates as $checkInDate => $v) {
                    $checkInData[(string)$checkInDate] = array();
                    $rawCheckInData = CheckIn::select('applicant_id')->where('check_in_date', $checkInDate)->whereIn('applicant_id', $applicants->pluck('sn'))->get();
                    if ($rawCheckInData) {
                        $checkInData[(string)$checkInDate] = $rawCheckInData->pluck('applicant_id')->toArray();
                    }
                }

                // 簽到退時間
                $signAvailabilities = $this->campFullData->allSignAvailabilities;
                $signData = [];
                $signDateTimesCols = [];

                if ($signAvailabilities) {
                    foreach ($signAvailabilities as $signAvailability) {
                        $signData[$signAvailability->id] = [
                            'type'       => $signAvailability->type,
                            'name'       => $signAvailability->timeslot_name,
                            'date'       => substr($signAvailability->start, 5, 5),
                            'start'      => substr($signAvailability->start, 11, 5),
                            'end'        => substr($signAvailability->end, 11, 5),
                            'applicants' => $signAvailability->applicants->pluck('id')
                        ];
                        $name = $signAvailability->timeslot_name ?? [];
                        $str = $signAvailability->type == "in" ? "簽到時間：" : "簽退時間：";
                        $signDateTimesCols["SIGN_" . $signAvailability->id] = $name. $str . substr($signAvailability->start, 5, 5) . " " . substr($signAvailability->start, 11, 5) . " ~ " . substr($signAvailability->end, 11, 5);
                    }
                } else {
                    $signData = array();
                }
            }

            $fileName = $this->campFullData->abbreviation . $query . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $callback = function () use ($applicants, $checkInDates, $checkInData, $signData, $signDateTimesCols) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                $columns = ["deleted_at" => "取消時間"];
                if (str_contains($applicants->first()?->camp->table, 'vcamp')) {
                    $columns = array_merge($columns, ["role_section" => '職務組別', "role_position" => '職務']);
                }
                if ((!isset($signData) || count($signData) == 0)) {
                    if (!isset($checkInDates)) {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? []);
                    } else {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $checkInDates);
                    }
                } else {
                    if (!isset($checkInDates)) {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $signDateTimesCols);
                    } else {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $checkInDates, $signDateTimesCols);
                    }
                }
                // 2022 一般教師營需要
                if ($this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                    $pos = 44;
                    $columns = array_merge($columns, array_slice($columns, 0, $pos), ["lamrim" => "廣論班"], array_slice($columns, $pos));
                }
                if ($this->campFullData->table != "ceocamp") {
                    unset($columns["carers"]);
                }
                unset($columns["care_log"]);
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach ($columns as $key => $v) {
                        // 2022 一般教師營需要
                        if ($v == "廣論班" && $this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                            $lamrim = \explode("||/", $applicant->blisswisdom_type_complement)[0];
                            if (!$lamrim || $lamrim == "") {
                                array_push($rows, '="無"');
                            } else {
                                array_push($rows, '="' . $lamrim . '"');
                            }
                            continue;
                        }
                        if ($v == "關懷員" && $this->campFullData->table == "ceocamp") {
                            $str = $applicant->carers->flatten()->pluck('name')->implode('、');
                            if (!$str || $str == "") {
                                array_push($rows, '="無"');
                            } else {
                                array_push($rows, '="' . $str . '"');
                            }
                            continue;
                        }
                        if ($key == "care_log") {
                            continue;
                        }
                        // 使用正規表示式抓出日期欄
                        if (preg_match('/\d\d\d\d-\d\d-\d\d/', $key)) {
                            if (isset($checkInData)) {
                                // 填充報到資料
                                if (in_array($applicant->sn, $checkInData[$key])) {
                                    array_push($rows, '="⭕"');
                                } else {
                                    array_push($rows, '="➖"');
                                }
                            }
                        } elseif (str_contains($key, "SIGN_")) {
                            // 填充簽到資料
                            if ($signData[substr($key, 5)]['applicants']->contains($applicant->sn)) {
                                array_push($rows, '="✔️"');
                            } else {
                                array_push($rows, '="❌"');
                            }
                        } elseif ($key == "role_section") {
                            $roles = "";
                            $aRoles = $applicant->user?->roles()->where('camp_id', $applicant->vcamp->mainCamp->id)->get() ?? [];
                            foreach ($aRoles as $k => $role) {
                                $roles .= $role->section;
                                if ($k != count($aRoles) - 1) {
                                    $roles .= "\n";
                                }
                            }
                            array_push($rows, '="' . $roles . '"');
                        } elseif ($key == "role_position") {
                            $roles = "";
                            $aRoles = $applicant->user?->roles()->where('camp_id', $applicant->vcamp->mainCamp->id)->get() ?? [];
                            foreach ($aRoles as $k => $role) {
                                $roles .= $role->position;
                                if ($k != count($aRoles) - 1) {
                                    $roles .= "\n";
                                }
                            }
                            array_push($rows, '="' . $roles . '"');
                        } else {
                            array_push($rows, '="' . $applicant[$key] . '"');
                        }
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        //----- 處理「下載」：結束 -----

        $request->flash();
        return view('backend.registration.list')
                ->with('applicants', $applicants)
                ->with('query', $query)
                ->with('batches', $batches);
    }

    public function changeBatchOrRegion(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if ($request->isMethod('POST')) {
            $candidate = Applicant::find($request->id);
            $candidate->batch_id = $request->batch;
            $candidate->region_id = $request->region_id;
            $candidate->region = Region::find($request->region_id)?->name;
            $candidate->save();
            $message = "梯次 / 區域修改完成。";
            $batches = Batch::where('camp_id', $this->campFullData->id)->get();
            $regions = $this->campFullData->regions;
            $candidate = $candidate->refresh();
            return view('backend.registration.changeBatchOrRegionForm', compact('candidate', 'message', 'batches', 'regions'));
        } else {
            return view("backend.registration.changeBatchOrRegion");
        }
    }

    public function massChangeBatchOrRegion(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if ($request->isMethod('POST')) {
            $message = "";
            $result = [];
            foreach ($request->applicant_id as $key => $a_id) {
                $notChanged = false;
                $candidate = Applicant::find($a_id);
                if ($candidate->batch_id == $request->batch_id_new[$key] && $candidate->region_id == $request->region_id_new[$key]) {
                    $notChanged = true;
                }
                $candidate->batch_id = $request->batch_id_new[$key] ?? $candidate->batch_id;
                $candidate->region_id = $request->region_id_new[$key] ?? $candidate->region_id;
                $candidate->region = Region::find($request->region_id_new[$key])?->name;
                if ($notChanged) {
                    $message .= $a_id . " " . $candidate->name . " <u>未修改</u>。 <br>";
                } else {
                    $candidate->save();
                    $message .= $a_id . " " . $candidate->name . " <strong>修改完成</strong>。 <br>";
                }
                $batches = Batch::where('camp_id', $this->campFullData->id)->get();
                $regions = $this->campFullData->regions;
                $candidate = $candidate->refresh();
                $candidate->applicant_id = $candidate->id;
                $result[] = $this->applicantService->Mandarization($candidate);
            }
            $candidate = null;
            return view('backend.registration.changeBatchOrRegionForm', compact('candidate', 'message', 'batches', 'regions', 'result'));
        } else {
            return view("backend.registration.changeBatchOrRegion");
        }
    }

    public function sendAdmittedMail(Request $request)
    {
        if (!$request->sns) {
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach ($request->sns as $sn) {
            \App\Jobs\SendAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "錄取通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function sendNotAdmittedMail(Request $request)
    {
        if (!$request->sns) {
            \Session::flash('error', "未選取任何人。");
            return back();
        }
        foreach ($request->sns as $sn) {
            \App\Jobs\SendNotAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "未錄取通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function sendCheckInMail(Request $request)
    {
        if (isset($request->org_id)) {
            $org_id = $request->org_id;
        } else {
            $org_id = null;
        }

        if (!$request->sns) {
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach ($request->sns as $sn) {
            \App\Jobs\SendCheckInMail::dispatch($sn, $org_id);
        }
        \Session::flash('message', "報到通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function showGroupList()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        /*
        if ($this->campFullData->table == 'ycamp' || $this->campFullData->table == 'yvcamp') {
            //2694-2716是輔導組
            if (count($this->user->roles->whereBetween('id',[2397,2398]))==0 &&count($this->user->roles->whereBetween('id',[2694,2716]))==0 && $this->user->id > 2) {
                return "<h3>大專營：只有輔導組幹部有權限。</h3>";
            }
        }*/

        $batches = Batch::with('groups', 'groups.applicants')->where('camp_id', $this->camp_id)->get()->all();
        foreach ($batches as &$batch) {
            $batch->regions = Applicant::select('region')
                                ->where('batch_id', $batch->id)
                                ->where('is_admitted', 1)
                                ->whereNotNull('group_id')
                                ->where(function ($query) {
                                    if ($this->campFullData->table != "ceocamp" && $this->campFullData->table != "ecamp" && $this->campFullData->table != "coupon") {
                                        $query->whereNotNull('number_id');
                                    }
                                })->groupBy('region')->get();
            //dd($batch->regions);
            foreach ($batch->regions as &$region) {
                $region->groups = Applicant::select('group_id', \DB::raw('count(*) as groupApplicantsCount'))
                    ->join('applicants_groups', 'applicants_groups.id', '=', 'applicants.group_id')
                    ->where('applicants.batch_id', $batch->id)
                    ->where('region', $region->region)
                    ->where('is_admitted', 1)
                    ->where(function ($query) {
                        if ($this->has_attend_data) {
                            $query->where('is_attend', 1);
                        }
                    })->whereNotNull('group_id')
                    ->where(function ($query) {
                        if ($this->campFullData->table != "ceocamp" && $this->campFullData->table != "ecamp" && $this->campFullData->table != "coupon") {
                            $query->whereNotNull('number_id');
                        }
                    })
                    ->orderBy('applicants_groups.alias')
                    ->groupBy('group_id')->get();
                $region->groups->each(function (&$applicant) {
                    $applicant->group = $applicant->groupRelation->alias;
                });
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.registration.groupList')->with(['batches' => $batches, 'user' => $this->user]);
    }

    public function showSectionList(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        /*
        if ($this->campFullData->table == 'ycamp' || $this->campFullData->table == 'yvcamp') {
            //2694-2716是輔導組
            if (count($this->user->roles->whereBetween('id',[2397,2398]))==0 &&count($this->user->roles->whereBetween('id',[2694,2716]))==0 && $this->user->id > 2) {
                return "<h3>大專營：只有輔導組幹部有權限。</h3>";
            }
        }*/

        $org_id = $request->route()->parameter('org_id');

        // This logic is for vcamps to display organization structure.
        $vcamp = VCamp::find($this->camp_id);
        if (!$vcamp || !$vcamp->mainCamp) {
            return "<h3>錯誤：找不到對應的主營隊資料。</h3>";
        }
        $main_camp = $vcamp->mainCamp;

        if ($org_id == 0) {
            $org_parent = $main_camp->org_root();
            $orgs = $main_camp->org_layer1;    //第一層；大組
        } else {
            $org_parent = CampOrg::find($org_id);
            if (!$org_parent) {
                return "<h3>錯誤：找不到指定的組織資料。</h3>";
            }
            $orgs = $org_parent->children;
        }
        $campFullData = $this->campFullData;
        return view('backend.registration.sectionList', compact('campFullData', 'orgs', 'org_parent'));
    }

    public function showNotAdmitted()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $batches = Batch::where('camp_id', $this->camp_id)->get();
        $batches->each(
            fn ($batch) =>
                $batch->applicants = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                ->where('batch_id', $batch->id)
                ->where(function ($query) {
                    // 只檢查 0
                    // todo: 需確認是否有營隊有「明確指定不錄取」才寄未錄取信的需求
                    $query->where('is_admitted', 0)->orWhereNull('is_admitted');
                })
                ->orderBy('applicants.id', 'asc')
                ->get()
        );
        return view('backend.registration.notAdmitted')->with('batches', $batches);
    }

    public function showGroup(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $batch_id = $request->route()->parameter('batch_id');
        $group = $request->route()->parameter('group');
        $applicants = Applicant::with('groupRelation', 'numberRelation')
                        ->whereHas('groupRelation', function ($query) use ($group) {
                            $query->where('alias', $group);
                        })
                        ->where(function ($query) {
                            if (!$this->campFullData->table == 'ecamp' || !$this->campFullData->table == 'ceocamp') {
                                $query->whereHas('numberRelation', function ($query) {
                                    $query->whereNotNull('number');
                                });
                            }
                        })
                        ->select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->where('batch_id', $batch_id)
                        ->where(function ($query) use ($request) {
                            if ($this->has_attend_data && !$request->showAttend) {
                                $query->where('is_attend', '<>', 0);
                            }
                        })
                        ->get();

        $applicants = $applicants->filter(fn ($applicant) => $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant));
        // $applicants = $applicants->filter(function ($applicant) {
        //     // 先檢查快取
        //     $cacheKey = "user_{$this->user->id}_can_access_{$applicant->id}";
        //     return cache()->remember($cacheKey, now()->addMinutes(10), function () use ($applicant) {
        //         return $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant);
        //     });
        // });
        foreach ($applicants as $applicant) {
            if ($applicant->fee > 0) {
                if ($applicant->fee - $applicant->deposit <= 0) {
                    $applicant->is_paid = "是";
                } else {
                    $applicant->is_paid = "否";
                }
            } else {
                $applicant->is_paid = "無費用";
            }
            $applicant->id = $applicant->applicant_id;
            $applicant = $this->applicantService->Mandarization($applicant);    //M/F->男/女
            //是否報到
            if (isset($applicant->checkInData->first()->check_in_date)) {
                $applicant->is_checkin = 1;
            } else {
                $applicant->is_checkin = 0;
            }
        }

        $applicants = $applicants->sortBy([
                                    ['groupRelation.alias', 'asc'],
                                    ['numberRelation.number', 'asc'],
                                    ['is_paid', 'desc']
                                ])->values();

        $template = $request->template ?? 0;
        $camp = $this->campFullData;

        if (isset($request->download) && $template == 2) {
            $form_title = "報名報到暨宿舍安排單";
            $form_width = "740px";  //portrait
            $columns = config('camps_fields.form_accomodation.' . $this->campFullData->table) ?? [];
            $accomodation_m = $this->gsheetService->importAccomodation($camp->id, '男', $group);
            $accomodation_f = $this->gsheetService->importAccomodation($camp->id, '女', $group);
            //return view('camps.' . $this->campFullData->table . '.formAccomodation', compact( 'form_title','form_width','columns','camp','group','applicants'));
            return \PDF::loadView('camps.' . $this->campFullData->table . '.formAccomodation', compact('form_title', 'form_width', 'columns', 'camp', 'group', 'applicants', 'accomodation_m', 'accomodation_f'))->setPaper('a3')->download($this->campFullData->abbreviation . $group . $form_title . Carbon::now()->format('YmdHis') . '.pdf');
        } elseif (isset($request->download) && $template == 3) {
            $form_title = "通訊資料確認表";
            $form_width = "1046px"; //landscape
            $columns = config('camps_fields.form_contact.' . $this->campFullData->table) ?? [];
            //return view('camps.' . $this->campFullData->table . '.formContact', compact('form_title','form_width','columns','camp','group','applicants'));
            return \PDF::loadView('camps.' . $this->campFullData->table . '.formGroup', compact('form_title', 'form_width', 'columns', 'camp', 'group', 'applicants'))->setPaper('a3', 'landscape')->download($this->campFullData->abbreviation . $group . $form_title . Carbon::now()->format('YmdHis') .'.pdf');
        } elseif (isset($request->download) && $template == 4) {
            $form_title = "回程交通確認表";
            $form_width = "740px";  //portrait
            $columns = config('camps_fields.form_traffic_confirm.' . $this->campFullData->table) ?? [];
            //return view('camps.' . $this->campFullData->table . '.formTraffic', compact('form_title','form_width','columns','camp','group','applicants'));
            return \PDF::loadView('camps.' . $this->campFullData->table . '.formGroup', compact('form_title', 'form_width', 'columns', 'camp', 'group', 'applicants'))->setPaper('a3')->download($this->campFullData->abbreviation . $group . $form_title . Carbon::now()->format('YmdHis') .'.pdf');
        } elseif (isset($request->download) && $template == 50) {
            $form_title = "報到學員名單";
            $form_width = "1046px";  //landscape
            $columns = config('camps_fields.form_checkin.' . $this->campFullData->table) ?? [];
            //return view('camps.' . $this->campFullData->table . '.formGroup', compact('form_title','form_width','columns','camp','group','applicants'));
            return \PDF::loadView('camps.' . $this->campFullData->table . '.formGroup', compact('form_title', 'form_width', 'columns', 'camp', 'group', 'applicants'))->setPaper('a3', 'landscape')->download($this->campFullData->abbreviation . $group . $form_title . Carbon::now()->format('YmdHis') .'.pdf');
        }

        if (isset($request->download)) {
            if ($template == 1) { //名單樣板=名單for now
                $fileName = $this->campFullData->abbreviation . $group . "組名單樣板" . Carbon::now()->format('YmdHis') . '.csv';
            } elseif ($template == 51) {
                $form_title = "報到學員名單";
                $fileName = $this->campFullData->abbreviation . $group . $form_title . Carbon::now()->format('YmdHis') .'.csv';
            } else {    //名單
                $fileName = $this->campFullData->abbreviation . $group . "組名單" . Carbon::now()->format('YmdHis') . '.csv';
            }

            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $callback = function () use ($applicants, $template) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                if ($template == 1) {  //名單樣板＝名單for now
                    if ($this->campFullData->table == 'tcamp') {
                        $columns = ["admitted_no" => "錄取序號", "name" => "姓名", "idno" => "身分證字號", "unit_county" => "服務單位所在縣市", "unit" => "服務單位", "workshop_credit_type" => "研習時數類型"];
                    } else {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? []);
                    }
                } elseif ($template == 51) {  //報到學員名單
                    $columns = config('camps_fields.form_checkin.' . $this->campFullData->table) ?? [];
                } else {    //名單
                    $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? []);
                }
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
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
                        } else {
                            $data = $applicant->$key;
                        }
                        $rows[] = '="' . $data . '"';
                    }
                    fputcsv($file, $rows);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->showAttend) {
            return view('backend.in_camp.groupAttend', compact('applicants'));
        }
        foreach ($applicants as $applicant) {
            $traffic = Traffic::where('applicant_id', '=', $applicant->applicant_id)->first();
            if ($traffic == null) {
                $applicant->depart_from = '尚未登記';
                $applicant->back_to = '尚未登記';
            } else {
                //dd($traffic);
                $applicant->depart_from = $traffic->depart_from;
                $applicant->back_to = $traffic->back_to;
            }
        }
        return view('backend.registration.group', compact('applicants'));
    }

    public function showSection(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $camp_id = $request->route()->parameter('camp_id'); //vcamp_id
        $org_id = $request->route()->parameter('org_id');
        $vcamp = Camp::find($camp_id);
        $main_camp = $vcamp->mainCamp;
        $batches = $vcamp->batchs;
        $batch = $batches->first();
        $batch_id = $batches->first()->id;
        $org = CampOrg::find($org_id);
        $users = $org->users;
        $applicants = array();
        foreach ($users as $user) {
            $aps = $user->application_log;
            foreach ($aps as $ap) {
                if ($ap->batch_id == $batch_id) {
                    array_push($applicants, $ap);
                }
            }
        }
        return view('backend.registration.section', compact('applicants', 'batch', 'org'));
    }

    public function showGroupAttendList()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach ($batches as &$batch) {
            $batch->regions = Applicant::select('region')
                ->where('batch_id', $batch->id)
                ->where('is_admitted', 1)
                ->whereHas('groupRelation', function ($query) {
                    $query->whereNotNull('alias');
                })
                ->groupBy('region')->get();
            $tmp = collect([]);
            foreach (config('camps_fields.regions.ycamp') ?? [] as $region) {
                if ($batch->regions->where('region', $region)->count()) {
                    $tmp->push($batch->regions->where('region', $region)->first());
                }
            }
            $batch->regions = $tmp->flatten();
            foreach ($batch->regions as &$region) {
                $region->groups = Applicant::with('groupRelation')->select('id', 'group_id', \DB::raw("count(*) as count,
                                        SUM(case when is_attend is null then 1 else 0 end) as null_sum,
                                        SUM(case when is_attend = 1 then 1 else 0 end) as attend_sum,
                                        SUM(case when (is_attend = 1 and gender = 'M') then 1 else 0 end) as attend_sum_m,
                                        SUM(case when (is_attend = 1 and gender = 'F') then 1 else 0 end) as attend_sum_f,
                                        SUM(case when is_attend = 0 then 1 else 0 end) as not_attend_sum,
                                        SUM(case when is_attend = 2 then 1 else 0 end) as not_decided_yet_sum,
                                        SUM(case when is_attend = 3 then 1 else 0 end) as couldnt_contact_sum,
                                        SUM(case when is_attend = 4 then 1 else 0 end) as cant_full_event_sum"))
                    ->where('batch_id', $batch->id)
                    ->where('region', $region->region)
                    ->where('is_admitted', 1)
                    ->whereNotNull('group_id')
                    ->where(function ($query) {
                        if (!$this->campFullData->table == 'ecamp' || !$this->campFullData->table == 'ceocamp' || !$this->campFullData->table == 'coupon') {
                            $query->whereNotNull('number_id');
                        }
                    })
                    ->groupBy('group_id')->get();
                $region->groups = $region->groups->sortBy("groupRelation.alias");
                $region->region = $region->region ?? "其他";

            }
        }
        return view('backend.in_camp.groupAttendList')->with('batches', $batches);
    }

    public function sendCheckInNotifydMail(Request $request)
    {
        if (!$request->sns) {
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach ($request->sns as $sn) {
            \App\Jobs\SendAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "已將產生之信件排入任務佇列。");
        return back();
    }

    public function showTrafficList(Request $request)
    {
        $batch_id = $_GET['batch_id'] ?? null;
        $download = $_GET['download'] ?? null;

        $camp = $this->campFullData->table;
        $batches = $this->campFullData->batchs;
        $batch_ids = $batches->pluck('id');
        $applicants = Applicant::with($camp)->whereIn('batch_id', $batch_ids)->get();
        $trafficData = Traffic::whereIn('applicant_id', $applicants->pluck('id'))->get();
        if (!\Schema::hasColumn($camp, 'traffic_depart') && $trafficData->count() == 0) {
            return "<h1>本次營隊沒有統計交通</h1>";
        }

        //download one batch_id
        if ($download) {
            //取消但有繳費也要篩進來因為要對帳
            $applicants = Applicant::has('traffic')
            ->with('traffic')
            ->where('batch_id', $batch_id)
            ->where('is_admitted', 1)
            ->get();
            $applicants = $applicants->sortBy([
                                        ['groupRelation.alias', 'asc'],
                                        ['numberRelation.number', 'asc'],
                                        //['is_paid', 'desc']
                                    ])->values();
            $batch = Batch::find($batch_id);
            $columns = config('camps_fields.form_traffic.' . $this->campFullData->table) ?? [];

            //$applicant1 = Applicant::find('19374');
            //$applicant1 = Applicant::find('16973');
            //dd($applicant1->checkInData->first()->check_in_date);

            $fileName = $this->campFullData->abbreviation . $batch->name . "梯車資繳納明細" . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                    "Content-Encoding"    => "Big5",
                    "Content-type"        => "text/csv; charset=big5",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
            );

            $callback = function () use ($applicants, $columns, $batch) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach ($columns as $key => $v) {
                        $data = null;
                        if ($key == "admitted_no") {
                            $data = $applicant->group . $applicant->number;
                        } elseif ($key == "is_checkin") {
                            if (isset($applicant->checkInData->first()->check_in_date)) {
                                $data = 1;
                            }
                        } elseif ($key == "deposit") {
                            $data = $applicant->traffic?->$key ?? 0;
                        } else {
                            if (isset($applicant->$key)) {
                                $data = $applicant->$key;
                            } else {
                                $data = $applicant->traffic?->$key ?? 0;
                            }
                        }
                        $rows[] = '="' . $data . '"';
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } else {
            $traffic_depart = Applicant::select(
                \DB::raw('traffic'.'.depart_from as traffic_depart, count(*) as count')
            )->join('traffic', 'traffic'.'.applicant_id', '=', 'applicants.id')
            //->where('traffic'.'.depart_from', '<>', '自往')
            ->whereIn('batch_id', $batch_ids)
            ->where('is_attend', 1)
            ->groupBy('traffic_depart')->get();

            $traffic_return = Applicant::select(
                \DB::raw('traffic'.'.back_to as traffic_return, count(*) as count')
            )->join('traffic', 'traffic'.'.applicant_id', '=', 'applicants.id')
            //->where('traffic'.'.back_to', '<>', '自回')
            ->whereIn('batch_id', $batch_ids)
            ->where('is_attend', 1)
            ->groupBy('traffic_return')->get();

            return view('backend.in_camp.trafficList', compact('batches', 'applicants', 'traffic_depart', 'traffic_return', 'camp'));
        }
    }

    public function showTrafficListLoc(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp, 'onlyCheckAvailability') && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        $batch_id = $_GET['batch_id'] ?? null;
        $depart_from = $_GET['depart_from'] ?? null;
        $back_to = $_GET['back_to'] ?? null;
        $download = $_GET['download'] ?? null;

        if ($depart_from) {
            $applicants = Applicant::select('applicants.*')
            ->join('traffic', 'traffic'.'.applicant_id', '=', 'applicants.id')
            ->where('batch_id', $batch_id)
            ->where('is_attend', 1)
            ->where('depart_from', $depart_from)
            ->get();
        }
        if ($back_to) {
            $applicants = Applicant::select('applicants.*')
            ->join('traffic', 'traffic'.'.applicant_id', '=', 'applicants.id')
            ->where('batch_id', $batch_id)
            ->where('is_attend', 1)
            ->where('back_to', $back_to)
            ->get();
        }
        $applicants = $applicants->sortBy([
                                    ['groupRelation.alias', 'asc'],
                                    ['numberRelation.number', 'asc'],
                                    ['is_paid', 'desc']
                                ])->values();
        $batch = Batch::find($batch_id);
        $camp = $this->campFullData;
        if ($depart_from) {
            $direction = "去程";
            $location = $depart_from;
        } else {
            $direction = "回程";
            $location = $back_to;
        }
        $columns = config('camps_fields.form_traffic_loc.' . $this->campFullData->table) ?? [];

        if ($download) {
            $fileName = $this->campFullData->abbreviation . $batch->name . "梯" . $direction .$location . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                    "Content-Encoding"    => "Big5",
                    "Content-type"        => "text/csv; charset=big5",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
            );

            $callback = function () use ($applicants, $columns) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");

                fputcsv($file, $columns);
                $count = 1;
                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach ($columns as $key => $v) {
                        $data = null;
                        if ($key == "admitted_no") {
                            $data = $applicant->group . $applicant->number;
                        } elseif ($key == "deposit") {
                            $data = $applicant->traffic?->$key ?? 0;
                        } elseif ($key == "no") {
                            $data = $count;
                            $count++;
                        } else {
                            $data = $applicant->$key;
                        }
                        $rows[] = '="' . $data . '"';
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);


        } else {
            return view('backend.in_camp.trafficListLoc', compact('camp', 'batch', 'direction', 'location', 'applicants', 'columns'));
        }
    }

    public function showVolunteerPhoto(Request $request)
    {
        ini_set('max_execution_time', -1);
        ini_set("memory_limit", -1);
        $camp = $this->campFullData->vcamp;
        $batches = Batch::where("camp_id", $camp->id)->get();
        $query = Applicant::select("applicants.*", $camp->table . ".*", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($camp->table, 'applicants.id', '=', $camp->table . '.applicant_id')
                        ->where('camps.id', $camp->id)->withTrashed();
        $applicants = $query->get();
        $applicants = $applicants->filter(fn ($applicant) => $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant));
        // $applicants = $applicants->filter(function ($applicant) {
        //     // 先檢查快取
        //     $cacheKey = "user_{$this->user->id}_can_access_{$applicant->id}";
        //     return cache()->remember($cacheKey, now()->addMinutes(10), function () use ($applicant) {
        //         return $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant);
        //     });
        // });

        if ($request->download) {
            return \PDF::loadView('backend.in_camp.volunteerPhoto', compact('applicants', 'batches'))->download(Carbon::now()->format('YmdHis') . $camp->table . '義工名冊.pdf');
        }

        return view('backend.in_camp.volunteerPhoto')
                ->with('applicants', $applicants)
                ->with('batches', $batches)
                ->with('camp', $camp);
    }

    public function queryAttendee(Request $request)
    {
        ini_set('max_execution_time', -1);
        ini_set("memory_limit", -1);
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        $query = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)->withTrashed();
        $applicants = $query->get();
        if (auth()->user()->getPermission(false)->role->level <= 2) {
        } elseif (auth()->user()->getPermission(true, $this->campFullData->id)->level > 2) {
            $constraint = auth()->user()->getPermission(true, $this->campFullData->id)->region;
            $batch = Batch::where('camp_id', $this->campFullData->id)->where('name', 'like', '%' . $constraint . '%')->first();
            $applicants = $applicants->filter(function ($applicant) use ($constraint, $batch) {
                if ($batch) {
                    return $applicant->region == $constraint || $applicant->batch_id == $batch->id;
                }
                return $applicant->region == $constraint;
            });
        }

        return view('backend.in_camp.queryAttendee')
                ->with('applicants', $applicants)
                ->with('batches', $batches);
    }

    public function showAttendeeInfo(Request $request)
    {
        ini_set('max_execution_time', -1);
        ini_set("memory_limit", -1);
        $camp = $this->campFullData;
        //one camp, multiple batches
        //$batches = Batch::where("camp_id", $this->campFullData->id)->get();
        $applicant = $this->applicantService->fetchApplicantData(
            $this->campFullData->id,
            $this->campFullData->table,
            $request->snORadmittedSN,
        );
        if ($request->isMethod("POST")) {
            try {
                $disk = \Storage::disk('local');
                $path = 'media/';
                if ($request->hasFile('file1')) {
                    $file1 = $request->file('file1');
                    $name1 = $file1->hashName();
                }
                if ($request->hasFile('file2')) {
                    $file2 = $request->file('file2');
                    $name2 = $file2->hashName();
                }
                $files = [];
                if ($file1 ?? false) {
                    if ($this->campFullData->table == 'utcamp') {
                        $path = 'avatars/';
                    }
                    $disk->put($path, $file1);
                    $image = Image::make(storage_path($path . $name1))->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save(storage_path($path . $name1));
                    $files[] = $path . $name1;
                }
                if ($file2 ?? false) {
                    $disk->put($path, $file2);
                    $image = Image::make(storage_path($path . $name2))->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save(storage_path($path . $name2));
                    $files[] = $path . $name2;
                }
                if ($applicant && count($files) > 0) {
                    $a = Applicant::find($applicant->applicant_id);
                    if ($this->campFullData->table == 'utcamp') {
                        $a->avatar = $files[0];
                    } else {
                        $a->files = json_encode($files);
                    }
                    $a->save();
                    $a->refresh();
                    $applicant = $this->applicantService->fetchApplicantData(
                        $this->campFullData->id,
                        $this->campFullData->table,
                        $request->snORadmittedSN,
                    );
                }
            } catch (\Throwable $e) {
                logger($e);
            }
        }
        if ($applicant) {
            if (str_contains($camp->table, 'vcamp')) {
                $theCamp = Vcamp::find($camp->id)->mainCamp;
                $theStr = "義工";
                $target = Volunteer::find($applicant->applicant_id);
            } else {
                $theCamp = $camp;
                $theStr = "學員";
                $target = $applicant;
            }
            if (!\App\Models\User::find(auth()->id())?->canAccessResource($target, 'read', $theCamp, target: $target)) {
                return "<h1>您沒有權限查看此資料(" . $theStr . ")</h1>";
            }

            $applicant = $this->applicantService->Mandarization($applicant);
        } else {
            return "<h1>異常狀況發生，請將網址提供給開發人員檢查。</h1>";
        }
        $batch = Batch::find($applicant->batch_id);
        $contactlog = ContactLog::where("applicant_id", $applicant->applicant_id)->orderByDesc('id')->first();
        if (isset($contactlog)) {
            $contactlog = $this->backendService->setTakenByName($contactlog);
        }

        /*
        foreach ($applicant as $column => $value) {
            if (str_contains($column, "||/")) {
                $newKey = $column . "_split";
                $applicant->$newKey = explode("||/", $value);
            }
        }
        */
        if (isset($applicant->favored_event)) {
            $applicant->favored_event_split = explode("||/", $applicant->favored_event);
        }
        if (isset($applicant->expertise)) {  //ivcamp
            $applicant->expertise_split = explode("||/", $applicant->expertise);
        }
        if (isset($applicant->language)) {
            $applicant->language_split = explode("||/", $applicant->language);
        }
        if (isset($applicant->after_camp_available_day)) {
            $applicant->after_camp_available_day_split = explode("||/", $applicant->after_camp_available_day);
        }
        if (isset($applicant->participation_dates)) {    //evcamp,icamp
            $applicant->participation_dates_split = explode("||/", $applicant->participation_dates);
        }
        if (isset($applicant->stay_dates)) {    //evcamp,icamp
            $applicant->stay_dates_split = explode("||/", $applicant->stay_dates);
        }
        if (isset($applicant->contact_time)) {
            $applicant->contact_time_split = explode("||/", $applicant->contact_time);
        }

        //get urls for each applicant
        $dynamic_stat_urls = null;
        if ($applicant->batch->dynamic_stats) {
            $applicant->url = "";
            try {
                foreach ($applicant->batch->dynamic_stats as $stat) {
                    $sheet = $this->gsheetService->Get($stat->spreadsheet_id, $stat->sheet_name);
                    //look-up applicant_id
                    foreach ($sheet as $row) {
                        if ($row[0] == $applicant->applicant_id) {
                            $dynamic_stat_urls[$stat->purpose] = $row[1];
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                $dynamic_stat_urls = "FAILED.";
                \Sentry\captureException($e);
            }
        }

        $lodgings = config('camps_payments.fare_room.' . $camp->table) ?? [];
        $departfroms = config('camps_payments.fare_depart_from.' . $camp->table) ?? [];
        $backtos = config('camps_payments.fare_back_to.' . $camp->table) ?? [];

        $qrcode = $this->generateQrCodeWithText($applicant);

        if (str_contains($camp->table, "vcamp")) {
            return view('backend.in_camp.volunteerInfo', compact('camp', 'batch', 'applicant', 'contactlog', 'qrcode'));
        } elseif ($camp->table == "acamp") {
            return view('backend.in_camp.attendeeInfoAcamp', compact('camp', 'batch', 'applicant', 'contactlog', 'qrcode'));
        } elseif ($camp->table == "ceocamp") {
            return view('backend.in_camp.attendeeInfoCeocamp', compact('camp', 'batch', 'applicant', 'contactlog', 'dynamic_stat_urls', 'lodgings', 'qrcode'));
        } elseif ($camp->table == "ecamp") {
            return view('backend.in_camp.attendeeInfoEcamp', compact('camp', 'batch', 'applicant', 'contactlog', 'dynamic_stat_urls', 'qrcode'));
        } elseif ($camp->table == "ycamp") {
            return view('backend.in_camp.attendeeInfoYcamp', compact('camp', 'batch', 'applicant', 'contactlog', 'qrcode', 'departfroms', 'backtos'));
        } else {
            return view('backend.in_camp.attendeeInfo', compact('camp', 'batch', 'applicant', 'contactlog', 'dynamic_stat_urls', 'qrcode'));
        }
    }

    public function generateQrCodeWithText(Applicant $applicant)
    {
        // Generate QR code
        $qrcodeData = \DNS2D::getBarcodePNG('{"applicant_id":' . $applicant->id . '}', 'QRCODE', 150, 150); // Using a scale factor instead of pixel dimensions

        // Create image resource from QR code
        $qrcodeImage = imagecreatefromstring(base64_decode($qrcodeData));
        if (!$qrcodeImage) {
            die('Failed to create image from QR code');
        }

        // Text to display on the image
        $text = $applicant->group . $applicant->number . "：" . $applicant->name;

        // Use a TrueType font for better control over the text size and encoding
        $fontFile = storage_path('fonts/msjh.ttf'); // Ensure the font file path is correct
        $fontSize = 300; // Adjust the font size as needed

        // Get the bounding box size of the text
        $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = $bbox[2] - $bbox[0] + 80;
        $textHeight = $bbox[1] - $bbox[7] + 120;

        // Create a blank image for the text
        $textImage = imagecreatetruecolor($textWidth, $textHeight);
        $background_color = imagecolorallocate($textImage, 255, 255, 255); // white background
        $text_color = imagecolorallocate($textImage, 0, 0, 0); // black text

        // Fill the background with the allocated color
        imagefill($textImage, 0, 0, $background_color);

        // Add the text to the image with UTF-8 encoding support
        imagettftext($textImage, $fontSize, 0, 0, $fontSize + 60, $text_color, $fontFile, $text);

        // Merge the QR code with the text image
        $qrcodeWidth = imagesx($qrcodeImage) + 320;
        $qrcodeHeight = imagesy($qrcodeImage) + 320;

        $finalWidth = max($qrcodeWidth, $textWidth + 20); // Ensure some padding
        $finalHeight = $qrcodeHeight + $textHeight + 10;

        $finalImage = imagecreatetruecolor($finalWidth, $finalHeight);
        $white = imagecolorallocate($finalImage, 255, 255, 255);
        imagefill($finalImage, 0, 0, $white);

        // Copy text image onto the final image
        imagecopy($finalImage, $textImage, 160, 100, 0, 0, $textWidth, $textHeight);

        // Copy the QR code to the final image
        imagecopy($finalImage, $qrcodeImage, 160, $textHeight + 160, 0, 0, $qrcodeWidth, $qrcodeHeight);

        // Calculate new dimensions
        $newWidth = $finalWidth / 10;
        $newHeight = $finalHeight / 10;

        // Create a new image with the reduced dimensions
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $finalImage, 0, 0, 0, 0, $newWidth, $newHeight, $finalWidth, $finalHeight);

        // Output the final image to a buffer
        ob_start();
        imagepng($resizedImage);
        $imageData = ob_get_contents();
        ob_end_clean();

        // Encode the image data to base64
        $base64Image = base64_encode($imageData);

        // Free memory
        imagedestroy($qrcodeImage);
        imagedestroy($textImage);
        imagedestroy($finalImage);
        imagedestroy($resizedImage);

        // Return the base64 image
        return $base64Image;
    }

    public function deleteUserRole(Request $request)
    {
        $user = User::find($request->user_id);
        $user->roles()->detach($request->role_id);
        $camp = Vcamp::find($request->camp_id)->mainCamp;
        if ($user->roles()->where('camp_id', $camp->id)->count() == 0) {
            $user->application_log()->detach($request->applicant_id);
            $user->canAccessResult()->delete();
        }
        $request->session()->flash('message', '已刪除該義工職務');
        return redirect()->route("showAttendeeInfoGET", ["camp_id" => $request->camp_id, "snORadmittedSN" => $request->applicant_id]);
    }

    public function deleteApplicantGroupAndNumber(Request $request)
    {
        $applicant = Applicant::find($request->applicant_id);
        $applicant->groupRelation()->dissociate();
        $applicant->numberRelation()->dissociate();
        $applicant->save();
        $request->session()->flash('message', '已刪除該學員組別');
        return redirect()->route("showAttendeeInfoGET", ["camp_id" => $request->camp_id, "snORadmittedSN" => $request->applicant_id]);
    }

    public function deleteApplicantCarer(Request $request)
    {
        if (CarerApplicantXref::query()->where("applicant_id", $request->applicant_id)->where("user_id", $request->carer_id)->delete()) {
            $request->session()->flash('message', '已刪除該關懷員');
        } else {
            return redirect()->route("showAttendeeInfoGET", ["camp_id" => $request->camp_id, "snORadmittedSN" => $request->applicant_id])->withErrors(['發生錯誤']);
        }
        return redirect()->route("showAttendeeInfoGET", ["camp_id" => $request->camp_id, "snORadmittedSN" => $request->applicant_id]);
    }

    public function showLearners(Request $request)
    {
        $user = \App\Models\User::findOrFail(auth()->user()->id);
        view()->share('user', $user);
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();

        $dynamic_stats = collect();
        //if($this->campFullData->table == "ceocamp") {
        //    //MCH:find the related applicant of the (user,camp_id)
        //    $user_applicant = $user->applicants($this->campFullData->id)->first() ?? [];
        //    //MCH:find the urls
        //    $dynamic_stats = $user_applicant->dynamic_stats ?? [];
        //} else {
        $roles = $user->roles?->where('camp_id', $this->campFullData->id) ?? null;
        foreach ($roles as $role) {
            $dynamic_stats = $dynamic_stats->merge($role->dynamic_stats ?? null);
        }
        //}

        if (!$user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $user->id > 2) {
            return "<h3>沒有權限瀏覽任何學員，或您尚未被指派任何學員</h3>";
        }
        if (!$this->isVcamp && $this->campFullData->access_end && Carbon::now()->gt($this->campFullData->access_end)) {
            return "<h3>權限已關閉。</h3>";
        }
        ini_set('max_execution_time', -1);
        ini_set("memory_limit", -1);
        if ($request->isMethod("post")) {
            $payload = $request->all();
            if (count($payload) == 1) {
                return back()->withErrors(['未設定任何條件。']);
            }
            foreach ($payload as $key => &$value) {
                if (!is_array($value)) {
                    unset($payload[$key]);
                }
            }
            $queryStr = $this->backendService->queryStringParser($payload, $request);
        }
        $query = Applicant::with('groupRelation', 'groupOrgRelation', 'batch')
                        ->select("applicants.*", $this->campFullData->table . ".*", $this->campFullData->table . ".id as ''", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)->withTrashed()->orderBy('deleted_at', 'asc')->orderBy('applicants.id', 'asc');
        if ($request->batch_id) {
            $query->where('batchs.id', $request->batch_id);
        }
        if ($request->isMethod("post")) {
            $query = $queryStr != "" ? $query->whereRaw(\DB::raw($queryStr)) : $query;
            $request->flash();
        }
        $applicants = $query->get();
        $applicants = $applicants->each(fn ($applicant) => $applicant->id = $applicant->applicant_id);
        if ($request->isSetting == 1) {
            $isSetting = 1;
        } else {
            $isSetting = 0;
        }

        if ($request->isSettingCarer) {
            $target_group_ids = $user->roles()->where('camp_id', $this->campFullData->id)->where('camp_org.position', 'like', '%關懷小組第%')->get()->pluck('group_id');
            $all_groups = $user->roles()->where('camp_id', $this->campFullData->id)->where('camp_org.section', 'like', '%關懷大組%')->where('all_group', 1)->get();
            if (!count($target_group_ids) && ($user->canAccessResource(new \App\Models\CarerApplicantXref(), 'create', $this->campFullData, target: $this->campFullData->vcamp) || $user->canAccessResource(new \App\Models\CarerApplicantXref(), 'assign', $this->campFullData, target: $this->campFullData->vcamp))) {
                $permissions = $user->load('roles.permissions')->roles->pluck("permissions")->flatten()->filter(
                    static fn ($permission) => $permission->name == '\App\Models\CarerApplicantXref.create' || $permission->name == '\App\Models\CarerApplicantXref.assign'
                );
                $carers = collect([]);
                $target_group_ids = $this->campFullData->organizations()->where('camp_org.camp_id', $this->campFullData->id)->where('camp_org.position', 'like', '%關懷小組第%')->get()->pluck('group_id');
                foreach ($permissions as $permission) {
                    if ($permission->range == 'na' || $permission->range == 'all') {
                        $carers = $carers->merge(\App\Models\User::with(
                            [
                            'groupOrgRelation' => function ($query) use ($request) {
                                $query->where('camp_id', $this->campFullData->id);
                                if ($request->batch_id) {
                                    $query->where('batch_id', $request->batch_id);
                                }
                            },
                            'groupOrgRelation.region',
                            'groupOrgRelation.batch']
                        )->whereHas('groupOrgRelation', function ($query) use ($request, $target_group_ids) {
                            $query->where('camp_id', $this->campFullData->id)->whereIn('group_id', $target_group_ids);
                            if ($request->batch_id) {
                                $query->where('batch_id', $request->batch_id);
                            }
                        })->get());
                        break;
                    }
                }
            } else {
                if ($request->batch_id) {
                    $carers = \App\Models\User::with([
                        'groupOrgRelation' => function ($query) use ($request, $target_group_ids) {
                            $query->where('camp_id', $this->campFullData->id)
                                ->where('batch_id', $request->batch_id)
                                ->whereIn('group_id', $target_group_ids);
                        },
                        'groupOrgRelation.region',
                        'groupOrgRelation.batch'
                    ])->whereHas('groupOrgRelation', function ($query) use ($request, $target_group_ids) {
                        $query->where('batch_id', $request->batch_id)
                            ->whereIn('group_id', $target_group_ids);
                    })->get();
                } else {
                    $carers = \App\Models\User::with([
                        'groupOrgRelation' => function ($query) use ($target_group_ids) {
                            $query->where('camp_id', $this->campFullData->id)
                                ->whereIn('group_id', $target_group_ids);
                        },
                        'groupOrgRelation.region',
                        'groupOrgRelation.batch'
                    ])->whereHas('groupOrgRelation', function ($query) use ($target_group_ids) {
                        $query->where('camp_id', $this->campFullData->id)
                            ->whereIn('group_id', $target_group_ids);
                    })->get();
                }
            }
        }

        if ($this->usePermissionOptimization) {
            $accessResults = $this->user->batchCanAccessResources($applicants, 'read', $this->campFullData);
            $applicants = $applicants->filter(fn ($applicant) => $accessResults->get($applicant->id, false));
        } else {
            $applicants = $applicants->filter(fn ($applicant) => $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant));
        }

        // $applicants = $applicants->filter(function ($applicant) {
        //     // 先檢查快取
        //     $cacheKey = "user_{$this->user->id}_can_access_{$applicant->id}";
        //     return cache()->remember($cacheKey, now()->addMinutes(10), function () use ($applicant) {
        //         return $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant);
        //     });
        // });

        // $chunks = $applicants->chunk(100);
        // $filteredApplicants = new \Illuminate\Database\Eloquent\Collection;

        // foreach ($chunks as $chunk) {
        //     $jobs = $chunk->map(function ($applicant) {
        //         return new CheckResourceAccessJob($applicant, $this->user, $this->campFullData);
        //     });

        //     Bus::batch($jobs)->dispatch();

        //     // 處理結果
        //     $filtered = $chunk->filter(fn ($applicant) => $applicant->access_granted);
        //     $filteredApplicants = $filteredApplicants->merge($filtered);
        // }

        // $applicants = $filteredApplicants;

        /*// 使用多執行緒處理
        $chunkSize = 100; // 調整批次大小
        $chunks = $applicants->chunk($chunkSize);
        $filteredApplicants = new \Illuminate\Database\Eloquent\Collection;

        // 創建一個程序池
        $pool = \Spatie\Async\Pool::create();
        $user_id = $this->user->id;
        $camp_id = $this->campFullData->id;

        foreach ($chunks as $chunk) {
            $pool->add(function() use ($chunk, $user_id, $camp_id) {
                // 在子程序中重新取得資源
                $user = \App\Models\User::find($user_id);
                $camp = \App\Models\Camp::find($camp_id);

                return $chunk->filter(function ($applicant) use ($user, $camp) {
                    return $user->canAccessResource($applicant, 'read', $camp, target: $applicant);
                })->values()->toArray();
            })->then(function($result) use (&$filteredApplicants) {
                // 將陣列轉回 Eloquent 集合
                $collection = new \Illuminate\Database\Eloquent\Collection;
                foreach ($result as $item) {
                    $model = new \App\Models\Applicant;
                    $model->setRawAttributes($item, true);
                    $collection->push($model);
                }
                $filteredApplicants = $filteredApplicants->merge($collection);
            });
        }

        $pool->wait();
        $applicants = $filteredApplicants;*/

        $columns_zhtw = config('camps_fields.display.' . $this->campFullData->table);

        $request->flash();
        return response()->view('backend.integrated_operating_interface.theList', [
                'applicants' => $applicants,
                'batches' => $batches,
                'current_batch' => Batch::find($request->batch_id),
                'isShowVolunteers' => 0,
                'isSetting' => $isSetting,
                'isSettingCarer' => $request->isSettingCarer ?? 0,
                'carers' => $carers ?? null,
                'isShowLearners' => 1,
                'is_ingroup' => 0,
                'groupName' => '',
                'columns_zhtw' => $columns_zhtw,
                'fullName' => $this->campFullData->fullName,
                'queryStr' => $queryStr ?? null,
                'groups' => $this->campFullData->groups,
                'targetGroupIds' => $target_group_ids ?? null,
                'dynamic_stats' => $dynamic_stats
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function showVolunteers(Request $request)
    {
        ini_set('max_execution_time', -1);
        ini_set("memory_limit", -1);
        if (!$this->campFullData->vcamp) {
            return "<h1>尚未設定對應之義工營。</h1>";
        }
        if ($request->isMethod("post")) {
            $payload = $request->all();
            if (count($payload) == 1) {
                return back()->withErrors(['未設定任何條件。']);
            }
            [$queryStr, $queryRoles, $showNoJob] = $this->backendService->volunteerQueryStringParser($payload, $request, $this->campFullData);
        } else {
            $queryStr = null;
            $queryRoles = null;
            $showNoJob = null;
        }
        $batches = Batch::where("camp_id", $this->campFullData->vcamp->id)->get();
        $query = Applicant::with('groupRelation', 'groupOrgRelation', 'batch', 'contactlog', 'user')
                        ->select("applicants.*", $this->campFullData->vcamp->table . ".*", $this->campFullData->vcamp->table . ".id as ''", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->vcamp->id)
                        ->whereDoesntHave('user')
                        ->withTrashed()->orderBy('deleted_at', 'asc');
        ;
        if ($request->batch_id) {
            $query->where('batchs.id', $request->batch_id);
        }
        if ($request->isMethod("post")) {
            if ($queryStr != "") {
                $query = $query->where(\DB::raw($queryStr), 1);
            } else {
                $query = $query->whereRaw("1 = 0");
            }
        }
        $applicants = $query->get();
        $applicants = $applicants->each(fn ($applicant) => $applicant->id = $applicant->applicant_id);
        $filtered_batches = clone $batches;
        if ($request->batch_id) {
            $filtered_batches = $filtered_batches->filter(fn ($batch) => $batch->id == $request->batch_id);
        }
        $new = 1;
        if (!$new) {
            $registeredUsers = \App\Models\User::with([
                'roles' => fn ($q) => $q->where('camp_id', $this->campFullData->id), // 給 IoiSearch 用的資料
                'application_log.user.roles' => fn ($q) => $q->where('camp_id', $this->campFullData->id),  // applicant-list 顯示用的資料
                'application_log.user.roles.batch',
                'application_log' => function ($query) use ($filtered_batches) {
                    $query->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id');
                    $query->whereIn('batch_id', $filtered_batches->pluck('id'));
                },  'application_log.groupRelation',
                    'application_log.groupOrgRelation',
                    'application_log.batch',
                    'application_log.contactlog'
                ])
                ->where(function ($q) use ($queryRoles) {
                    $q->whereHas('application_log.user.roles', function ($query) use ($queryRoles) {
                        $query->where('camp_id', $this->campFullData->id);
                        if ($queryRoles && !$queryRoles->isEmpty()) {
                            $query->whereIn('camp_org.id', $queryRoles->pluck('id'));
                        }
                    });
                    if (!$queryRoles || $queryRoles->isEmpty()) {
                        $q->orWhereDoesntHave('application_log.user.roles', function ($query) {
                            $query->where('camp_id', $this->campFullData->id);
                        });
                    }
                })
                ->whereHas('application_log', function ($query) use ($batches) {
                    $query->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id');
                    $query->whereIn('batch_id', $batches->pluck('id'));
                });
            if ($request->isMethod("post")) {
                if ($showNoJob) {
                    if ($queryRoles->isEmpty() && $queryStr == "(1 = 1)") {
                        $registeredUsers = $registeredUsers->whereDoesntHave('application_log.user.roles');
                    } else {
                        $application_log_constraint = function ($query) use ($queryStr, $batches) {
                            $query->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id');
                            $query->whereIn('batch_id', $batches->pluck('id'))
                                ->when($queryStr, function ($query) use ($queryStr) {
                                    $query->whereRaw($queryStr);
                                });
                        };
                        $registeredUsers = $registeredUsers->where(function ($query) use ($queryRoles, $queryStr, $application_log_constraint) {
                            $query->when(!$queryRoles->isEmpty(), function ($query) use ($queryRoles) {
                                $query->orWhereHas('application_log.user.roles', function ($query) use ($queryRoles) {
                                    $query->where('camp_id', $this->campFullData->id)
                                        ->whereIn('camp_org.id', $queryRoles->pluck('id'));
                                });
                            })->when($queryStr != "(1 = 1)", function ($query) use ($queryRoles, $application_log_constraint, $queryStr) {
                                $query->when($queryRoles->isEmpty(), function ($query) use ($application_log_constraint) {
                                    $query->orWhereHas('application_log', $application_log_constraint);
                                })->when(!$queryRoles->isEmpty(), function ($query) use ($application_log_constraint) {
                                    $query->WhereHas('application_log', $application_log_constraint);
                                })->when($queryStr, function ($query) use ($queryStr) {
                                    $query->whereRaw($queryStr);
                                });
                            })->orWhereDoesntHave('application_log.user.roles');
                        });
                    }
                } else {
                    $registeredUsers = $registeredUsers->when($queryStr != "(1 = 1)", function ($query) use ($queryStr, $batches) {
                        $query->whereHas('application_log', function ($query) use ($queryStr, $batches) {
                            $query->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id')
                                ->whereIn('batch_id', $batches->pluck('id'))
                                ->when($queryStr, function ($query) use ($queryStr) {
                                    $query->whereRaw($queryStr);
                                });
                        });
                    });
                }
            }
            $registeredUsers = $registeredUsers->get();
        } else {
            $registeredUsers = \App\Models\User::with([
                'roles' => fn ($q) => $q->where('camp_id', $this->campFullData->id), // 給 IoiSearch 用的資料
                'application_log.user.roles' => fn ($q) => $q->where('camp_id', $this->campFullData->id),  // applicant-list 顯示用的資料
                'application_log.user.roles.batch',
                'application_log' => function ($query) use ($filtered_batches) {
                    $query->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id');
                    $query->whereIn('batch_id', $filtered_batches->pluck('id'));
                },
                'application_log.' .  $this->campFullData->vcamp->table,
                'application_log.groupRelation',
                'application_log.groupOrgRelation',
                'application_log.batch',
                'application_log.contactlog'
            ])->select('users.*')
            ->join('user_applicant_xrefs', 'users.id', '=', 'user_applicant_xrefs.user_id')
            ->join('applicants', 'user_applicant_xrefs.applicant_id', '=', 'applicants.id')
            ->join($this->campFullData->vcamp->table . ' as evcamp', 'applicants.id', '=', 'evcamp.applicant_id')
            ->leftJoin('org_user', function ($join) {
                $join->on('users.id', '=', 'org_user.user_id')
                    ->where('org_user.user_type', 'AppModelsUser');
            })
            ->leftJoin('camp_org', function ($join) {
                $join->on('org_user.org_id', '=', 'camp_org.id')
                    ->where('camp_org.camp_id', $this->campFullData->id);
            })
            ->whereIn('applicants.batch_id', $filtered_batches->pluck('id'))
            ->where(function ($query) use ($queryRoles) {
                $query->whereExists(function ($subquery) use ($queryRoles) {
                    $subquery->select(\DB::raw(1))
                        ->from('camp_org')
                        ->join('org_user', 'camp_org.id', '=', 'org_user.org_id')
                        ->whereColumn('org_user.user_id', 'users.id')
                        ->where('camp_org.camp_id', $this->campFullData->id)
                        ->when($queryRoles && !$queryRoles->isEmpty(), function ($q) use ($queryRoles) {
                            $q->whereIn('camp_org.id', $queryRoles->pluck('id'));
                        });
                })
                ->when(!$queryRoles || $queryRoles->isEmpty(), function ($q) {
                    $q->orWhereNotExists(function ($subquery) {
                        $subquery->select(\DB::raw(1))
                            ->from('camp_org')
                            ->join('org_user', 'camp_org.id', '=', 'org_user.org_id')
                            ->whereColumn('org_user.user_id', 'users.id')
                            ->where('camp_org.camp_id', $this->campFullData->id);
                    });
                });
            });

            if ($request->isMethod("post")) {
                if ($showNoJob) {
                    if ($queryRoles->isEmpty() && $queryStr == "(1 = 1)") {
                        $registeredUsers = $registeredUsers->whereDoesntHave('application_log.user.roles');
                    } else {
                        $application_log_constraint = function ($query) use ($queryStr, $batches) {
                            $query->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id');
                            $query->whereIn('batch_id', $batches->pluck('id'))
                                ->when($queryStr, function ($query) use ($queryStr) {
                                    $query->whereRaw($queryStr);
                                });
                        };
                        $registeredUsers = $registeredUsers->where(function ($query) use ($queryRoles, $queryStr, $application_log_constraint) {
                            $query->when(!$queryRoles->isEmpty(), function ($query) use ($queryRoles) {
                                $query->orWhereHas('application_log.user.roles', function ($query) use ($queryRoles) {
                                    $query->where('camp_id', $this->campFullData->id)
                                        ->whereIn('camp_org.id', $queryRoles->pluck('id'));
                                });
                            })->when($queryStr != "(1 = 1)", function ($query) use ($queryRoles, $application_log_constraint, $queryStr) {
                                $query->when($queryRoles->isEmpty(), function ($query) use ($application_log_constraint) {
                                    $query->orWhereHas('application_log', $application_log_constraint);
                                })->when(!$queryRoles->isEmpty(), function ($query) use ($application_log_constraint) {
                                    $query->WhereHas('application_log', $application_log_constraint);
                                })->when($queryStr, function ($query) use ($queryStr) {
                                    $query->whereRaw($queryStr);
                                });
                            })->orWhereDoesntHave('application_log.user.roles');
                        });
                    }
                } else {
                    if ($queryStr != "(1 = 1)") {
                        $registeredUsers->whereRaw($queryStr);
                    }
                }
            }
            $registeredUsers = $registeredUsers->distinct()->get();
        }
        if ($this->usePermissionOptimization) {
            $accessResults = $this->user->batchCanAccessResources($registeredUsers, 'read', $this->campFullData, 'vcamp');
            $registeredUsers = $registeredUsers->filter(fn ($user) => $accessResults->get($user->id, false));
            $accessResults = $this->user->batchCanAccessResources($applicants, 'read', $this->campFullData, 'vcamp');
            $applicants = $applicants->filter(fn ($applicant) => $accessResults->get($applicant->id, false));
        } else {
            $registeredUsers = $registeredUsers->filter(fn ($user) => $this->user->canAccessResource($user, 'read', $this->campFullData, target: $user, context: 'vcamp'));
            $applicants = $applicants->filter(fn ($applicant) => $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant, context: 'vcamp'));
        }
        // $registeredUsers = $registeredUsers->filter(function ($user) {
        //     // 先檢查快取
        //     $cacheKey = "user_{$this->user->id}_can_access_user_{$user->id}";
        //     return cache()->remember($cacheKey, now()->addMinutes(10), function () use ($user) {
        //         return $this->user->canAccessResource($user, 'read', $this->campFullData, target: $user, context: 'vcamp');
        //     });
        // });
        // $applicants = $applicants->filter(function ($applicant) {
        //     // 先檢查快取
        //     $cacheKey = "user_{$this->user->id}_can_access_{$applicant->id}";
        //     return cache()->remember($cacheKey, now()->addMinutes(10), function () use ($applicant) {
        //         return $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant, context: 'vcamp');
        //     });
        // });

        if ($request->isSetting == 1) {
            $isSetting = 1;
        } else {
            $isSetting = 0;
        }

        $camp_str = $this->campFullData->vcamp->table;
        $columns_zhtw = config('camps_fields.display.' . $camp_str);

        $request->flash();
        return response()->view('backend.integrated_operating_interface.theList', [
                'applicants' => $applicants,
                'registeredVolunteers' => $registeredUsers,
                'batches' => $batches,
                'current_batch' => Batch::find($request->batch_id),
                'isShowVolunteers' => 1,
                'isSetting' => $isSetting,
                'isSettingCarer' => $request->isSettingCarer ?? 0,
                'carers' => null,
                'isShowLearners' => 0,
                'is_ingroup' => 0,
                'groupName' => '',
                'columns_zhtw' => $columns_zhtw,
                'fullName' => $this->campFullData->fullName,
                'groups' => $this->campFullData->roles,
                'queryStr' => $queryStr ?? '',
                'queryRoles' => $queryRoles ?? ''
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function export(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        if ($request->input('vcamp')) {
            $camp = Camp::find($this->campFullData->vcamp->id);
            $filename = $camp->fullName . '義工名單' . Carbon::now()->format('YmdHis') .  '.xlsx';
        } else {
            $camp = $this->campFullData;
            $filename = $camp->fullName . '學員名單' . Carbon::now()->format('YmdHis') .  '.xlsx';
        }

        if ($request->allData && $this->user->email == "lzong.tw@gmail.com") {
            return Excel::download(new ApplicantsExport($camp, true), $filename);
        }
        return Excel::download(new ApplicantsExport($camp), $filename);
    }

    public function showCarers(Request $request)
    {
        if (!$this->campFullData->vcamp) {
            return "<h1>尚未設定對應之義工營。</h1>";
        }
        if ($request->isMethod("post")) {
            $queryStr = "";
            $payload = $request->all();
            if (count($payload) == 1) {
                return back()->withErrors(['未設定任何條件。']);
            }
            foreach ($payload as $key => &$value) {
                if (!is_array($value)) {
                    unset($payload[$key]);
                }
            }
            $count = 0;
            foreach ($payload as $key => $parameters) {
                if (is_array($parameters)) {
                    foreach ($parameters as $index => $parameter) {
                        if ($index == 0) {
                            $queryStr .= " (";
                        }
                        if (is_numeric($parameter)) {
                            if ($key == 'age') {
                                $year = now()->subYears($parameter)->format('Y');
                                $queryStr .= "birthyear = " . $year;
                            } else {
                                $queryStr .= $key . "=" . $parameter;
                            }
                        } elseif (is_string($parameter)) {
                            if ($key == 'name') {
                                $key = 'applicants.name';
                            }
                            $queryStr .= $key . " like '%" . $parameter . "%'";
                        }
                        if ($index != count($parameters) - 1) {
                            $queryStr .= " or ";
                        } else {
                            $queryStr .= ") ";
                        }
                    }
                    $count++;
                }
                if ($count <= count($payload) - 1) {
                    $queryStr .= " and ";
                }
            }
        }
        $batches = Batch::where("camp_id", $this->campFullData->vcamp->id)->get();
        $query = Applicant::select("applicants.*", $this->campFullData->vcamp->table . ".*", $this->campFullData->vcamp->table . ".id as ''", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id')
            ->where('camps.id', $this->campFullData->vcamp->id)->withTrashed();
        if ($request->isMethod("post")) {
            $query = $queryStr != "" ? $query->where(\DB::raw($queryStr), 1) : $query;
        }
        $applicants = $query->get();
        $applicants = $applicants->each(fn ($applicant) => $applicant->id = $applicant->applicant_id);

        $registeredUsers = \App\Models\User::with('roles')->whereHas('roles', function ($query) {
            $query->where('camp_id', $this->campFullData->id)->where('position', 'like', '%關懷小組%');
        })->get();
        if ($request->isSetting == 1) {
            $isSetting = 1;
        } else {
            $isSetting = 0;
        }

        $camp_str = $this->campFullData->vcamp->table;
        $columns_zhtw = config('camps_fields.display.' . $camp_str);

        $request->flash();
        return response()->view('backend.integrated_operating_interface.theList', [
                'registeredVolunteers' => $registeredUsers,
                'applicants' => $applicants,
                'batches' => $batches,
                'isShowVolunteers' => 1,
                'current_batch' => Batch::find($request->batch_id),
                'isSetting' => $isSetting,
                'isSettingCarer' => $request->isSettingCarer ?? 0,
                'carers' => null,
                'isShowLearners' => 1,
                'is_ingroup' => 1,
                'groupName' => '第1組',
                'columns_zhtw' => $columns_zhtw,
                'fullName' => $this->campFullData->fullName,
                'groups' => $this->campFullData->roles,
                'queryStr' => $queryStr ?? ''
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function getAvatar($camp_id, $id)
    {
        $applicant = Applicant::find($id);
        if ($applicant && $applicant->avatar && file_exists(base_path(\Storage::disk('local')->url($applicant->avatar)))) {
            return response()->file(base_path(\Storage::disk('local')->url($applicant->avatar)));
        }
        return response('無', 404);
    }

    public function getFile($camp_id, $file)
    {
        if ($file && file_exists(base_path(\Storage::disk('local')->url('media/' . $file)))) {
            return response()->file(base_path(\Storage::disk('local')->url('media/' . $file)));
        }
        return response('無', 404);
    }

    public function getMediaImage($camp_id, $path)
    {
        if (file_exists(base_path(\Storage::disk('local')->url("media/" . $path)))) {
            return response()->file(base_path(\Storage::disk('local')->url("media/" . $path)));
        }
        return '無';
    }

    public function showAccountingPage()
    {
        $constraints = function ($query) {
            $query->where('id', $this->camp_id);
        };
        $accountingTable = config('camps_payments.' . $this->campFullData->table . '.accounting_table');
        $accountings = Applicant::select('applicants.batch_id', 'applicants.name as aName', 'applicants.fee as shouldPay', $accountingTable . '.*', 'applicants.mobile')
            ->with(['batch', 'batch.camp' => $constraints])
            ->whereHas('batch.camp', $constraints)
            ->join($accountingTable, $accountingTable . '.accounting_no', '=', 'applicants.bank_second_barcode')
            ->orderBy($accountingTable . '.id', 'desc')->withTrashed()->get();
        $download = $_GET['download'] ?? false;
        if (!$download) {
            return view('backend.registration.accounting')->with('accountings', $accountings);
        } else {
            $fileName = $this->campFullData->abbreviation . "銷帳資料" . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $callback = function () use ($accountings) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                $columns = ["id" => "銷帳流水序號",
                            "cbname" => "營隊梯次",
                            "aName" => "姓名",
                            "shouldPay" => "應繳金額",
                            "amount" => "實繳金額",
                            "accounting_sn" => "銷帳流水號",
                            "accounting_no" => "銷帳編號",
                            "paid_at" => "繳費日期",
                            "creditted_at" => "入帳日期",
                            "name" => "繳費管道",
                            "mobile" => "手機號碼"];
                fputcsv($file, $columns);

                foreach ($accountings as $accounting) {
                    $rows = array();
                    foreach ($columns as $key => $v) {
                        if ($key == "cbname") {
                            array_push($rows, '="' . $accounting->batch->camp->abbreviation . " - " . $accounting->batch->name . '"');
                        } elseif ($key == "shouldPay" || $key == "amount") {
                            array_push($rows, $accounting[$key]);
                        } else {
                            array_push($rows, '="' . $accounting[$key] . '"');
                        }
                    }
                    fputcsv($file, $rows);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
    }

    public function modifyAccounting(Request $request)
    {
        if ($request->isMethod('POST')) {
            $applicant = Applicant::find($request->id);
            $admitted_sn = $applicant->group.$applicant->number;
            $camp_table = $this->campFullData->table;
            $fare_depart_from = config('camps_payments.fare_depart_from.' . $camp_table) ?? [];
            $fare_back_to = config('camps_payments.fare_back_to.' . $camp_table) ?? [];
            $fare_room = config('camps_payments.fare_room.' . $camp_table) ?? [];
            if ($camp_table == 'ycamp') {
                $traffic = $applicant->traffic;
                //尚未登記，建新的Traffic
                if (!$traffic) {
                    $traffic = new Traffic();
                    $traffic->applicant_id = $applicant->id;
                }
                //更新去程交通、回桯交通及應繳車資
                $traffic->depart_from = $request->depart_from;
                $traffic->back_to = $request->back_to;
                $traffic->fare = ($fare_depart_from[$traffic->depart_from] ?? 0) + ($fare_back_to[$traffic->back_to] ?? 0);
                //更新現金繳費金額
                if ($request->is_add == 'add') {
                    $traffic->cash = $traffic->cash + $request->cash;
                } else {
                    $traffic->cash = $request->cash;
                }
                //重新計算已繳總額
                $traffic->sum = $traffic->deposit + $traffic->cash;
                $traffic->save();
                //update barcode
                $applicant = $this->applicantService->fillPaymentData($applicant);
                $applicant->save();
                $message = "手動繳費完成。";
                if ($request->page == "attendeeInfo") {
                    return redirect()->back();
                } else {
                    return view("backend.modifyAccounting", compact('applicant', 'message', 'fare_depart_from', 'fare_back_to', 'fare_room'));
                }
            } elseif ($camp_table == 'ceocamp') {
                $lodging = $applicant->lodging;
                //尚未登記，建新的Lodging
                if (!$lodging) {
                    $lodging = new Lodging();
                    $lodging->applicant_id = $applicant->id;
                }
                //更新房型、天數及應繳車資
                $lodging->room_type = $request->room_type;
                $lodging->nights = $request->nights;
                $lodging->fare = ($fare_room[$lodging->room_type] ?? 0) * ($lodging->nights ?? 0);
                //更新現金繳費金額
                if ($request->is_add == 'add') {
                    $lodging->cash = $lodging->cash + $request->cash;
                } else {
                    $lodging->cash = $request->cash;
                }
                //重新計算已繳總額
                $lodging->sum = $lodging->deposit + $lodging->cash;
                $lodging->save();
                //update barcode
                $applicant = $this->applicantService->fillPaymentData($applicant);
                $applicant->save();
                $message = "修改完成。";
                if ($request->page == "attendeeInfo") {
                    return redirect()->back();
                } else {
                    return view("backend.modifyAccounting", compact('applicant', 'message', 'fare_depart_from', 'fare_back_to', 'fare_room'));
                }
            } elseif ($camp_table == 'utcamp') {
                $applicant->fee = ($request->fee) ?? 0;

                if ($request->is_add == 'add') {
                    $applicant->deposit = $applicant->deposit + $request->cash;
                } else {
                    $applicant->deposit = $request->cash;
                }

                $applicant = $this->applicantService->fillPaymentData($applicant);
                $applicant->save();
                $applicant = $this->applicantService->checkPaymentStatus($applicant);
                $message = "修改完成。";
                if ($request->page == "attendeeInfo") {
                    return redirect()->back();
                } else {
                    return view("backend.modifyAccounting", compact('applicant', 'message', 'fare_depart_from', 'fare_back_to', 'fare_room'));
                }
            } else {
                if ($admitted_sn == $request->double_check || $applicant->id == $request->double_check) {
                    $applicant->deposit = $applicant->fee;
                    $applicant->save();
                    $applicant = $this->applicantService->checkPaymentStatus($applicant);
                    $message = "繳費完成 / 已繳金額設定完成。";
                    return view("backend.modifyAccounting", compact('applicant', 'message', 'fare_depart_from', 'fare_back_to', 'fare_room'));
                } else {
                    $error = "報名序號錯誤。";
                    $applicant = $this->applicantService->checkPaymentStatus($applicant);
                    return view("backend.modifyAccounting", compact('applicant', 'message', 'fare_depart_from', 'fare_back_to', 'fare_room'));
                }
            }
        } else { //$request->isMethod('GET')
            $title = "現場手動繳費 / 修改繳費資料";
            $campFullData = $this->campFullData;
            return view("backend.findApplicant", compact("campFullData", "title"));
        }
    }

    public function modifyAttend(Request $request)
    {
        if ($request->isMethod('POST')) {
            $applicant = Applicant::find($request->id);
            $admitted_sn = $applicant->group.$applicant->number;
            //dd($request->cash);
            if ($this->campFullData->table == 'ycamp' && $request->cash > 0) {
                $traffic = $applicant->traffic;
                if ($request->is_add == 'add') {
                    $traffic->cash = $traffic->cash + $request->cash;
                } else {
                    $traffic->cash = $request->cash;
                }
                $traffic->sum = $traffic->deposit + $traffic->cash;
                $traffic->save();
                $message = "手動繳費完成。";
                return view("backend.modifyAccounting", compact("applicant", "message"));
            } else {
                if ($admitted_sn == $request->double_check || $applicant->id == $request->double_check) {
                    $applicant->deposit = $applicant->fee;
                    $applicant->save();
                    $applicant = $this->applicantService->checkPaymentStatus($applicant);
                    $message = "繳費完成 / 已繳金額設定完成。";
                    return view("backend.modifyAccounting", compact("applicant", "message"));
                } else {
                    $error = "報名序號錯誤。";
                    $applicant = $this->applicantService->checkPaymentStatus($applicant);
                    return view("backend.modifyAccounting", compact("applicant", "error"));
                }
            }
        } else { //$request->isMethod('GET')
            $title = "設定取消參加";
            $campFullData = $this->campFullData;
            return view("backend.findApplicant", compact("campFullData", "title"));
        }
    }

    public function customMail(Request $request)
    {
        return view("backend.other.customMail");
    }

    public function selectMailTarget()
    {
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach ($batches as &$batch) {
            $batch->regions = Applicant::select('region')->where('batch_id', $batch->id)->where('is_admitted', 1)->groupBy('region')->get();
            foreach ($batch->regions as &$region) {
                $region->groups = Applicant::select('group_id', \DB::raw('count(*) as count'))->where('batch_id', $batch->id)->where('region', $region->region)->where('is_admitted', 1)->groupBy('group_id')->get();
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.other.groupList')->with('batches', $batches);
    }

    public function writeCustomMail(Request $request)
    {
        return view("backend.other.writeMail");
    }

    public function sendCustomMail(Request $request)
    {
        $camp = Camp::find($request->camp_id);
        if ($request->target == 'all') { // 全體錄取人士
            $batch_ids = $camp->batchs()->pluck('id')->toArray();
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->whereNotNull(['group_id', 'number_id'])->where([['group_id', '<>', ''], ['number_id', '<>', '']])->whereIn('batch_id', $batch_ids)->get();
        } elseif ($request->target == 'batch') { // 梯次錄取人士
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->whereNotNull(['group_id', 'number_id'])->where([['group_id', '<>', ''], ['number_id', '<>', '']])->where('batch_id', $request->batch_id)->get();
        } elseif ($request->target == 'group') { // 梯次組別錄取人士
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->where('group_id', '=', $request->group_id)->where('batch_id', $request->batch_id)->get();
        }
        $files = array();
        for ($i  = 0; $i < 3; $i++) {
            if ($request->hasFile('attachment' . $i) && $request->file('attachment' . $i)->isValid()) {
                $file = $request->file('attachment' . $i);
                $originalname = $file->getClientOriginalName();
                $fileName = time().$originalname;
                $file->storeAs('attachment', $fileName);
                $files[$i] = $fileName;
            }
        }
        foreach ($receivers as $receiver) {
            \Mail::to($receiver)->queue(new \App\Mail\CustomMail($request->subject, $request->content, $files, $receiver->batch->camp->variant ?? $receiver->batch->camp->table));
        }
        return view("backend.other.mailSent", ['message' => '已成功將自定郵件送入任務佇列。']);
    }

    public function editRemark(Request $request, $camp_id)
    {
        $formData = $request->toArray();
        if (!isset($formData['applicant_id'])) {
            \Sentry\captureMessage("學員 / 義工註記沒報名序號。 Request 內容：" . $request);
            return "<h1>找不到報名序號</h1>";
        }
        $applicant_id = $formData['applicant_id'];
        $applicant = Applicant::find($applicant_id);
        $applicant->remark = $formData['remark'];
        //dd($applicant->remark);
        $applicant->save();
        \Session::flash('message', "備註修改成功。");

        $controller = resolve(self::class);
        $request = new Request();
        $request->replace([
            "_token" => csrf_token(),
            "snORadmittedSN" => $applicant_id,
            "camp_id" => $camp_id
        ]);
        return $controller->showAttendeeInfo($request);
    }

    public function addContactLog(Request $request, $camp_id)
    {
        $formData = $request->toArray();
        $applicant_id = $formData['applicant_id'];
        $applicant = Applicant::find($applicant_id);
        if ($formData['todo'] == 'show') {
            //dd($applicant);
            $todo = 'add';
            return view('backend.in_camp.addContactLog', compact("camp_id", "applicant", "todo"));
        } elseif ($formData['todo'] == 'add') {
            //dd($formData);
            //$applicant = Applicant::find($applicant_id);
            //$contactlog = $applicant->contactlog;
            $newSet['applicant_id'] = $applicant_id;
            $newSet['notes'] = $formData['notes'];
            $newSet['user_id'] = auth()->user()->id;

            ContactLog::create($newSet);
            \Session::flash('message', "關懷記錄新增成功。");
            return redirect()->route("showContactLogs", [$camp_id, $applicant->id]);
        } else {
            //modify
            //dd($formData);
            $contactlog_id = $formData['contactlog_id'];
            $contactlog = ContactLog::find($contactlog_id);
            if (!$contactlog) {
                return back()->withErrors(['找不到關懷記錄。']);
            }
            $contactlog->update($formData);
            \Session::flash('message', "關懷記錄修改成功。");
            return redirect()->route("showContactLogs", [$camp_id, $applicant->id]);
        }
    }

    public function showAddContactLogs($camp_id, $applicant_id)
    {
        //dd($applicant_id);
        $applicant = Applicant::find($applicant_id);
        //$contactlogs = $applicant->contactlog->sortByDesc('id');
        $todo = 'add';
        return view('backend.in_camp.addContactLog', compact("camp_id", "applicant", "todo"));
    }

    public function modifyContactLog(Request $request, $camp_id, $contactlog_id)
    {
        $formData = $request->toArray();
        $contactlog_id = $formData['contactlog_id'];
        $contactlog = ContactLog::find($contactlog_id);
        if (!$contactlog) {
            return back()->withErrors(['找不到關懷記錄。']);
        }
        $applicant_id = $contactlog->applicant_id;
        $contactlog->update($formData);
        \Session::flash('message', "關懷記錄修改成功。");
        return redirect()->route("showContactLogs", [$camp_id, $applicant_id]);
    }

    public function showModifyContactLog($camp_id, $contactlog_id)
    {
        //$applicant = Applicant::find($applicant_id);
        $contactlog = ContactLog::find($contactlog_id);
        $applicant = Applicant::find($contactlog->applicant_id);
        $todo = 'modify';
        //只有顯示在textarea中，換行符號才會出問題，所以好像只需在這裡改
        $contactlog->notes = str_replace("\r", " ", $contactlog->notes);
        $contactlog->notes = str_replace("\n", " ", $contactlog->notes);
        $contactlog->notes = str_replace("\t", " ", $contactlog->notes);
        return view('backend.in_camp.addContactLog', compact("camp_id", "applicant", "contactlog", "todo"));
        //return view('backend.in_camp.modifyContactLog', compact("applicant", "contactlog"));
    }

    public function showContactLogs(Request $request, $camp_id, $applicant_id)
    {
        $formData = $request->toArray();
        //$applicant_id = $formData['applicant_id'];
        $applicant = Applicant::withTrashed()->find($applicant_id);
        $contactlogs = $applicant->contactlog->sortByDesc('id');
        //dd($contactlogs);
        if (isset($contactlogs)) {
            foreach ($contactlogs as &$contactlog) {
                $contactlog = $this->backendService->setTakenByName($contactlog);
            }
        }
        return view('backend.in_camp.contactLogList', compact('camp_id', 'applicant', 'contactlogs'));
    }

    public function removeContactLog(Request $request)
    {
        $result = \App\Models\ContactLog::find($request->contactlog_id)->delete();

        if ($result) {
            \Session::flash('message', "記錄刪除成功。");
            return back();
        } else {
            \Session::flash('error', "記錄刪除失敗。");
            return back();
        }
    }

    public function connectVolunteerToUser(Request $request)
    {
        if ($request->isMethod("GET")) {
            $list = [];
            $errors = [];
            if (!$request->applicant_ids) {
                $errors[] = '未選擇任何義工。';
            }
            if (!$request->group_id) {
                $errors[] = '未選擇任何職務組別。';
            }
            if (count($errors)) {
                return redirect()->back()->withErrors($errors);
            }
            foreach ($request->applicant_ids as $applicant_id) {
                $type = substr($applicant_id, 0, 1);
                $id = substr($applicant_id, 1);
                if ($type == 'U') {
                    $list[] = ["type" => "登入帳號", "data" => \App\Models\User::find($id), "action" => "直接指派職務"];
                }
                if ($type == 'A') {
                    $list[] = ["type" => "報名義工", "data" => Applicant::find($id), "action" => null];
                }
            }
            $group = \App\Models\CampOrg::find($request->group_id);
            return view("backend.integrated_operating_interface.connectVolunteerToUser", compact('list', 'group'));
        }
        if ($request->isMethod("POST")) {
            $processedlist = $this->backendService->setGroupOrg($request->candidates, $request->group_id);
            if (is_string($processedlist)) {
                return $processedlist;
            }
            $messages = [];
            foreach ($processedlist as $item) {
                if (!$item['user_is_generated']) {
                    $messages[] = "已將 " . $item['applicant']->name . " 指派為 " . $item['org']->section . $item['org']->position . " 並連結至帳號 " . $item['connected_to_user']->email . "，帳號 ID 為 " . $item['connected_to_user']->id;
                } else {
                    $messages[] = "已為 " . $item['applicant']->name . " 指派為 " . $item['org']->section . $item['org']->position . " 並建立及連結至帳號 " . $item['connected_to_user']->email . "，帳號 ID 為 " . $item['connected_to_user']->id . "，預設密碼為 " . $item['applicant']->mobile;
                }
            }
            $request->session()->flash('messages', $messages);
            return redirect()->route("showVolunteers", [$request->camp_id, 'isSetting' => 1]);
        }
    }

    public function cancelRegistration(Request $request)
    {
        $applicant = Applicant::find($request->id);
        $applicant->delete();
        return redirect()->route("showAttendeeInfoGET", ["camp_id" => $request->camp_id, "snORadmittedSN" => $applicant->id]);
    }

    public function revertCancellation(Request $request)
    {
        $applicant = Applicant::withTrashed()->find($request->id);
        $applicant->restore();
        return redirect()->route("showAttendeeInfoGET", ["camp_id" => $request->camp_id, "snORadmittedSN" => $applicant->id]);
    }

    public function switchToUser($id)
    {
        if (auth()->user()->email != "lzong.tw@gmail.com" && auth()->user()->email != "minchen.ho@blisswisdom.org") {
            return abort(500);
        }
        try {
            $user = User::find($id);
            \Session::put('original_user', \Auth::id());
            \Auth::login($user);
        } catch (\Exception $e) {
            throw new \Exception("Error logging in as user", 1);
        }
        return back();
    }

    public function switchUserBack()
    {
        try {
            $original = \Session::pull('original_user');
            $user = User::find($original);
            \Auth::login($user);
        } catch (\Exception $e) {
            throw new \Exception("Error returning to your user", 1);
        }
        return back();
    }
}
