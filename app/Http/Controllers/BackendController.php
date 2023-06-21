<?php

namespace App\Http\Controllers;

use App\Models\ApplicantsGroup;
use App\Models\CampOrg;
use App\Models\CarerApplicantXref;
use App\Models\GroupNumber;
use App\Models\OrgUser;
use App\Models\Vcamp;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Services\BackendService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Volunteer;
use App\Models\Batch;
use App\Models\CheckIn;
use App\Models\ContactLog;
use App\Models\Traffic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use View;
use App\Traits\EmailConfiguration;
use App\Models\SignInSignOut;

class BackendController extends Controller
{
    use EmailConfiguration;

    protected $campDataService;
    protected $applicantService;
    protected $backendService;
    protected $batch_id;
    protected $camp_data;
    protected $batch;
    protected $has_attend_data;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CampDataService $campDataService,
        ApplicantService $applicantService,
        BackendService $backendService,
        Request $request
    ) {
        $this->middleware('auth');
        $this->campDataService = $campDataService;
        $this->applicantService = $applicantService;
        $this->backendService = $backendService;
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
        }
        if($request->route()->parameter('camp_id')) {
            $this->middleware('permitted');
            $this->camp_id = $request->route()->parameter('camp_id');
            $this->campFullData = Camp::find($request->route()->parameter('camp_id'));
            View::share('camp_id', $this->camp_id);
            View::share('campFullData', $this->campFullData);
            if($this->campFullData->table == 'ycamp' || $this->campFullData->table == 'acamp') {
                if($this->campFullData->admission_confirming_end && Carbon::now()->gt($this->campFullData->admission_confirming_end)) {
                    $this->has_attend_data = true;
                }
            }
            $camp = $this->campFullData;
        }
        if(\Str::contains(url()->current(), "campManage")) {
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
            if($that->user->roles()->where("camp_id", $this->campFullData->id)->count() == 1 &&
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
            if($p->camp) {
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
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $message = null;
        $error = null;
        if ($request->isMethod('POST')) {
            $candidate = Applicant::find($request->id);
            if($request->get("clear") == "清除錄取序號") {
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
                if($check) {
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
        if(!$download) {
            return view('camps.' . $applicant->batch->camp->table . '.paymentForm', compact('applicant', 'download'));
        } else {
            return \PDF::loadView('camps.' . $applicant->batch->camp->table . '.paymentFormPDF', compact('applicant'))->setPaper('a3')->download(Carbon::now()->format('YmdHis') . $applicant->batch->camp->table . $applicant->id . '.pdf');
        }
    }

    public function batchAdmission(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if ($request->isMethod('POST')) {
            $error = array();
            $message = array();
            $applicants = array();
            if(!isset($request->id)) {
                return "沒有輸入任何欄位，請回上上頁重試。";
            }
            $batches = Batch::where("camp_id", $this->camp_id)->get()->pluck("id");
            foreach($request->id as $key => $id) {
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
                if($check) {
                    array_push($error, $candidate->name . "，錄取序號" . $request->admittedSN[$key] . "重複，沒有針對此人執行任何動作。");
                    $skip = true;
                }
                if($candidate->deleted_at) {
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
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $applicants = explode(",", $request->snORadmittedSN);
        foreach($applicants as &$applicant) {
            $groupAndNumber = $this->applicantService->groupAndNumberSeperator($applicant);
            $group = $groupAndNumber['group'];
            $number = $groupAndNumber['number'];
            $candidate = $this->applicantService->fetchApplicantData($this->campFullData->id, $this->campFullData->table, $applicant, $group, $number);
            if($candidate) {
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
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $groupAndNumber = $this->applicantService->groupAndNumberSeperator($request->snORadmittedSNorName);
        $group = $groupAndNumber['group'];
        $number = $groupAndNumber['number'];
        $candidate = $this->applicantService->fetchApplicantData($this->campFullData->id, $this->campFullData->table, $request->snORadmittedSNorName, $group, $number);
        if($candidate) {
            $candidate = $this->applicantService->Mandarization($candidate);
        } else {
            return "<h3>學員已取消或查無此學員</h3>";
        }

        if(isset($request->change)) {
            $batches = Batch::where('camp_id', $this->campFullData->id)->get();
            return view('backend.registration.changeBatchOrRegionForm', compact('candidate', 'batches'));
        }

        if(\Str::contains(request()->headers->get('referer'), 'accounting')) {
            $candidate = $this->applicantService->checkPaymentStatus($candidate);
            return view('backend.modifyAccounting', ['applicant' => $candidate]);
        }
        return view('backend.registration.showCandidate', compact('candidate'));
    }

    public function showRegistration()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $user_batch_or_region = null;
        //        if($this->campFullData->table == 'ecamp' && auth()->user()->getPermission('all')->first()->level > 2){
        //            $user_batch_or_region = Batch::where('camp_id', $this->campFullData->id)->where('name', 'like', '%' . auth()->user()->getPermission(true, $this->campFullData->id)->region . '%')->first();
        //            $user_batch_or_region = $user_batch_or_region ?? "empty";
        //        }
        return view('backend.registration.registration', compact('user_batch_or_region'));
    }

    public function showRegistrationList()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        return view('backend.registration.list', compact('batches'));
    }

    public function getRegistrationList(Request $request)
    {
        ini_set('max_execution_time', 1200);
        $batches = Batch::where("camp_id", $this->campFullData->id)->get();
        if(isset($request->region)) {
            $query = Applicant::select("applicants.*", $this->campFullData->table . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)->withTrashed();
            if($request->region == '全區') {
                $applicants = $query->get();
            } elseif($request->region == '其他') {
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
        } elseif(isset($request->school_or_course)) {
            //教師營使用 school_or_course 欄位
            $applicants = Applicant::select("applicants.*", "tcamp.*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join('tcamp', 'applicants.id', '=', 'tcamp.applicant_id')
                            ->where('camps.id', $this->campFullData->id);
            if($request->school_or_course == "無") {
                $applicants = $applicants->where(function ($q) {
                    $q->where('school_or_course', "")
                    ->orWhereNull('school_or_course');
                });
            } else {
                $applicants = $applicants->where('school_or_course', $request->school_or_course);
            }
            $applicants = $applicants->withTrashed()->get();
            $query = $request->school_or_course;
        } elseif(isset($request->education)) {
            //快樂營使用 education 欄位
            $applicants = Applicant::select("applicants.*", "hcamp.*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                            ->join('hcamp', 'applicants.id', '=', 'hcamp.applicant_id')
                            ->where('camps.id', $this->campFullData->id)
                            ->where('education', $request->education)
                            ->withTrashed()->get();
            $query = $request->education;
        } elseif(isset($request->batch)) {
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
        if($request->show_cancelled) {
            $query .= "(已取消)";
            $applicants = $applicants->whereNotNull('deleted_at');
        }
        foreach($applicants as $applicant) {
            $applicant->id = $applicant->sn;
            if($applicant->fee > 0) {
                if($applicant->fee - $applicant->deposit <= 0) {
                    $applicant->is_paid = "是";
                } else {
                    $applicant->is_paid = "否";
                }
            } else {
                $applicant->is_paid = "無費用";
            }
            if($applicant->trashed()) {
                $applicant->is_cancelled = "是";
            } else {
                $applicant->is_cancelled = "否";
            }
        }
        // 報名名單不以繳費與否排序
        // $applicants = $applicants->sortByDesc('is_paid');
        if($request->orderByCreatedAtDesc) {
            $applicants = $applicants->sortByDesc('created_at');
        }
        if(isset($request->download)) {
            if($applicants) {
                // 參加者報到日期
                $checkInDates = CheckIn::select('check_in_date')->whereIn('applicant_id', $applicants->pluck('sn'))->groupBy('check_in_date')->get();
                if($checkInDates) {
                    $checkInDates = $checkInDates->toArray();
                } else {
                    $checkInDates = array();
                }
                $checkInDates = \Arr::flatten($checkInDates);
                foreach($checkInDates as $key => $checkInDate) {
                    unset($checkInDates[$key]);
                    $checkInDates[(string)$checkInDate] = $checkInDate;
                }
                // 各梯次報到日期填充
                $batches = Batch::whereIn('id', $applicants->pluck('batch_id'))->get();
                foreach($batches as $batch) {
                    $date = Carbon::createFromFormat('Y-m-d', $batch->batch_start);
                    $endDate = Carbon::createFromFormat('Y-m-d', $batch->batch_end);
                    while(1) {
                        if($date > $endDate) {
                            break;
                        }
                        $str = $date->format('Y-m-d');
                        if(!in_array($str, $checkInDates)) {
                            $checkInDates = array_merge($checkInDates, [$str => $str]);
                        }
                        $date->addDay();
                    }
                }
                // 按陣列鍵值升冪排列
                ksort($checkInDates);
                $checkInData = array();
                // 將每人每日的報到資料按報到日期組合成一個陣列
                foreach($checkInDates as $checkInDate => $v) {
                    $checkInData[(string)$checkInDate] = array();
                    $rawCheckInData = CheckIn::select('applicant_id')->where('check_in_date', $checkInDate)->whereIn('applicant_id', $applicants->pluck('sn'))->get();
                    if($rawCheckInData) {
                        $checkInData[(string)$checkInDate] = $rawCheckInData->pluck('applicant_id')->toArray();
                    }
                }

                // 簽到退時間
                $signAvailabilities = $this->campFullData->allSignAvailabilities;
                $signData = [];
                $signDateTimesCols = [];

                if($signAvailabilities) {
                    foreach($signAvailabilities as $signAvailability) {
                        $signData[$signAvailability->id] = [
                            'type'       => $signAvailability->type,
                            'date'       => substr($signAvailability->start, 5, 5),
                            'start'      => substr($signAvailability->start, 11, 5),
                            'end'        => substr($signAvailability->end, 11, 5),
                            'applicants' => $signAvailability->applicants->pluck('id')
                        ];
                        $str = $signAvailability->type == "in" ? "簽到時間：" : "簽退時間：";
                        $signDateTimesCols["SIGN_" . $signAvailability->id] = $str . substr($signAvailability->start, 5, 5) . " " . substr($signAvailability->start, 11, 5) . " ~ " . substr($signAvailability->end, 11, 5);
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
                if((!isset($signData) || count($signData) == 0)) {
                    if(!isset($checkInDates)) {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? []);
                    } else {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $checkInDates);
                    }
                } else {
                    if(!isset($checkInDates)) {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $signDateTimesCols);
                    } else {
                        $columns = array_merge($columns, config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $checkInDates, $signDateTimesCols);
                    }
                }
                // 2022 一般教師營需要
                if($this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                    $pos = 44;
                    $columns = array_merge($columns, array_slice($columns, 0, $pos), ["lamrim" => "廣論班"], array_slice($columns, $pos));
                }
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach($columns as $key => $v) {
                        // 2022 一般教師營需要
                        if($v == "廣論班" && $this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                            $lamrim = \explode("||/", $applicant->blisswisdom_type_complement)[0];
                            if(!$lamrim || $lamrim == "") {
                                array_push($rows, '="無"');
                            } else {
                                array_push($rows, '="' . $lamrim . '"');
                            }
                            continue;
                        }
                        if ($v == "關懷員") {
                            $str = $applicant->carers->flatten()->pluck('name')->implode('、');
                            if(!$str || $str == "") {
                                array_push($rows, '="無"');
                            } else {
                                array_push($rows, '="' . $str . '"');
                            }
                            continue;
                        }
                        // 使用正規表示式抓出日期欄
                        if(preg_match('/\d\d\d\d-\d\d-\d\d/', $key)) {
                            if(isset($checkInData)) {
                                // 填充報到資料
                                if(in_array($applicant->sn, $checkInData[$key])) {
                                    array_push($rows, '="⭕"');
                                } else {
                                    array_push($rows, '="➖"');
                                }
                            }
                        } elseif(str_contains($key, "SIGN_")) {
                            // 填充簽到資料
                            if($signData[substr($key, 5)]['applicants']->contains($applicant->sn)) {
                                array_push($rows, '="✔️"');
                            } else {
                                array_push($rows, '="❌"');
                            }
                        } elseif($key == "role_section") {
                            $roles = "";
                            $aRoles = $applicant->user?->roles()->where('camp_id', $applicant->vcamp->mainCamp->id)->get() ?? [];
                            foreach ($aRoles as $k => $role) {
                                $roles .= $role->section;
                                if ($k != count($aRoles) - 1) {
                                    $roles .= "\n";
                                }
                            }
                            array_push($rows, '="' . $roles . '"');
                        } elseif($key == "role_position") {
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
        $request->flash();
        return view('backend.registration.list')
                ->with('applicants', $applicants)
                ->with('query', $query)
                ->with('batches', $batches);
    }

    public function changeBatchOrRegion(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        if ($request->isMethod('POST')) {
            $candidate = Applicant::find($request->id);
            $candidate->batch_id = $request->batch;
            $candidate->region = $request->region;
            $candidate->save();
            $message = "梯次 / 區域修改完成。";
            $batches = Batch::where('camp_id', $this->campFullData->id)->get();
            return view('backend.registration.changeBatchOrRegionForm', compact('candidate', 'message', 'batches'));
        } else {
            return view("backend.registration.changeBatchOrRegion");
        }
    }

    public function sendAdmittedMail(Request $request)
    {
        if(!$request->sns) {
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach($request->sns as $sn) {
            \App\Jobs\SendAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "錄取通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function sendNotAdmittedMail(Request $request)
    {
        if(!$request->sns) {
            \Session::flash('error', "未選取任何人。");
            return back();
        }
        foreach($request->sns as $sn) {
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

        if(!$request->sns) {
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach($request->sns as $sn) {
            \App\Jobs\SendCheckInMail::dispatch($sn, $org_id);
        }
        \Session::flash('message', "報到通知信寄送程序已被排入任務佇列。");
        return back();
    }

    public function showGroupList()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $batches = Batch::with('groups', 'groups.applicants')->where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch) {
            $batch->regions = Applicant::select('region')
                                ->where('batch_id', $batch->id)
                                ->where('is_admitted', 1)
                                ->whereNotNull('group_id')
                                ->where(function ($query) {
                                    if ($this->campFullData->table != "ceocamp" && $this->campFullData->table != "ecamp") {
                                        $query->whereNotNull('number_id');
                                    }
                                })->groupBy('region')->get();
            foreach($batch->regions as &$region) {
                $region->groups = Applicant::select('group_id', \DB::raw('count(*) as groupApplicantsCount'))
                    ->where('batch_id', $batch->id)
                    ->where('region', $region->region)
                    ->where('is_admitted', 1)
                    ->where(function ($query) {
                        if($this->has_attend_data) {
                            $query->where('is_attend', 1);
                        }
                    })->whereNotNull('group_id')
                    ->where(function ($query) {
                        if ($this->campFullData->table != "ceocamp" && $this->campFullData->table != "ecamp") {
                            $query->whereNotNull('number_id');
                        }
                    })
                    ->groupBy('group_id')->get();
                $region->groups->each(function (&$applicant) {
                    $applicant->group = $applicant->groupRelation->alias;
                });
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.registration.groupList')->with('batches', $batches);
    }

    public function showSectionList()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        //for ecamp only
        $main_camp = VCamp::find($this->camp_id)->mainCamp;
        $roles = $main_camp->roles;
        foreach ($roles as $role) {
            $role->count = count($role->users);
            $role->org_id = $role->id;
        }
        $campFullData = $this->campFullData;
        return view('backend.registration.sectionList', compact('campFullData', 'roles'));
    }

    public function showNotAdmitted()
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
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
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $batch_id = $request->route()->parameter('batch_id');
        $group = $request->route()->parameter('group');
        $applicants = Applicant::with('groupRelation', 'numberRelation')
                        ->whereHas('groupRelation', function ($query) use ($group) {
                            $query->where('alias', $group);
                        })
                        ->where(function ($query) {
                            if(!$this->campFullData->table == 'ecamp' || !$this->campFullData->table == 'ceocamp') {
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
                            if($this->has_attend_data && !$request->showAttend) {
                                $query->where('is_attend', 1);
                            }
                        })
                        ->get();
        foreach($applicants as $applicant) {
            if($applicant->fee > 0) {
                if($applicant->fee - $applicant->deposit <= 0) {
                    $applicant->is_paid = "是";
                } else {
                    $applicant->is_paid = "否";
                }
            } else {
                $applicant->is_paid = "無費用";
            }
        }
        $applicants = $applicants->sortBy([
                                    ['groupRelation.alias', 'asc'],
                                    ['numberRelation.number', 'asc'],
                                    ['is_paid', 'desc']
                                ]);
        if(isset($request->download)) {
            $fileName = $this->campFullData->abbreviation . $group . "組名單" . Carbon::now()->format('YmdHis') . '.csv';
            $headers = array(
                "Content-Encoding"    => "Big5",
                "Content-type"        => "text/csv; charset=big5",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $template = $request->template ?? 0;

            $callback = function () use ($applicants, $template) {
                $file = fopen('php://output', 'w');
                // 先寫入此三個字元使 Excel 能正確辨認編碼為 UTF-8
                // http://jeiworld.blogspot.com/2009/09/phpexcelutf-8csv.html
                fwrite($file, "\xEF\xBB\xBF");
                if($template) {
                    if($this->campFullData->table == 'tcamp') {
                        $columns = ["admitted_no" => "錄取序號", "name" => "姓名", "idno" => "身分證字號", "unit_county" => "服務單位所在縣市", "unit" => "服務單位", "workshop_credit_type" => "研習時數類型"];
                    }
                } else {
                    $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? []);
                }
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach($columns as $key => $v) {
                        $data = null;
                        if($key == "admitted_no") {
                            $data = $applicant->group . $applicant->number;
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
        if($request->showAttend) {
            return view('backend.in_camp.groupAttend', compact('applicants'));
        }
        return view('backend.registration.group', compact('applicants'));
    }

    public function showSection(Request $request)
    {
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
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
        foreach($users as $user) {
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
        if (!$this->isVcamp && !$this->user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何學員</h3>";
        }
        if ($this->isVcamp && !$this->user->canAccessResource(new \App\Models\Volunteer(), 'read', Vcamp::find($this->campFullData->id)->mainCamp) && $this->user->id > 2) {
            return "<h3>沒有權限：瀏覽任何義工</h3>";
        }
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch) {
            $batch->regions = Applicant::select('region')
                ->where('batch_id', $batch->id)
                ->where('is_admitted', 1)
                ->whereHas('groupRelation', function ($query) {
                    $query->whereNotNull('alias');
                })
                ->groupBy('region')->get();
            foreach($batch->regions as &$region) {
                $region->groups = Applicant::select('id', 'group_id', \DB::raw("count(*) as count,
                                        SUM(case when is_attend is null then 1 else 0 end) as null_sum,
                                        SUM(case when is_attend = 1 then 1 else 0 end) as attend_sum,
                                        SUM(case when is_attend = 0 then 1 else 0 end) as not_attend_sum,
                                        SUM(case when is_attend = 2 then 1 else 0 end) as not_decided_yet_sum,
                                        SUM(case when is_attend = 3 then 1 else 0 end) as couldnt_contact_sum,
                                        SUM(case when is_attend = 4 then 1 else 0 end) as cant_full_event_sum"))
                    ->where('batch_id', $batch->id)
                    ->where('region', $region->region)
                    ->where('is_admitted', 1)
                    ->whereNotNull('group_id')
                    ->where(function ($query) {
                        if (!$this->campFullData->table == 'ecamp' || !$this->campFullData->table == 'ceocamp') {
                            $query->whereNotNull('number_id');
                        }
                    })
                    ->groupBy('group_id')->get();
                $region->region = $region->region ?? "其他";
            }
        }
        return view('backend.in_camp.groupAttendList')->with('batches', $batches);
    }

    public function sendCheckInNotifydMail(Request $request)
    {
        if(!$request->sns) {
            \Session::flash('error', "未選取任何被錄取者。");
            return back();
        }
        foreach($request->sns as $sn) {
            \App\Jobs\SendAdmittedMail::dispatch($sn);
        }
        \Session::flash('message', "已將產生之信件排入任務佇列。");
        return back();
    }

    public function showTrafficList()
    {
        $camp = $this->campFullData->table;
        $batches = $this->campFullData->batchs;
        $batch_ids = $batches->pluck('id');
        $applicants = Applicant::with($camp)->whereIn('batch_id', $batch_ids)->get();
        $trafficData = Traffic::whereIn('applicant_id', $applicants->pluck('id'))->get();
        if(!\Schema::hasColumn($camp, 'traffic_depart') && $trafficData->count() == 0) {
            return "<h1>本次營隊沒有統計交通</h1>";
        }
        $traffic_depart = Applicant::select(
            \DB::raw($camp . '.traffic_depart as traffic_depart, count(*) as count')
        )->join($camp, $camp . '.applicant_id', '=', 'applicants.id')
            ->where('traffic_depart', '<>', '自往')
            ->whereIn('batch_id', $batch_ids)
            ->groupBy($camp . '.traffic_depart')->get();
        $traffic_return = Applicant::select(
            \DB::raw($camp . '.traffic_return as traffic_return, count(*) as count')
        )->join($camp, $camp . '.applicant_id', '=', 'applicants.id')
            ->where('traffic_return', '<>', '自往')
            ->whereIn('batch_id', $batch_ids)
            ->groupBy($camp . '.traffic_return')->get();
        return view('backend.in_camp.traffic_list', compact('batches', 'applicants', 'traffic_depart', 'traffic_return', 'camp'));
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

        if($request->download) {
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
        if(auth()->user()->getPermission(false)->role->level <= 2) {
        } elseif(auth()->user()->getPermission(true, $this->campFullData->id)->level > 2) {
            $constraint = auth()->user()->getPermission(true, $this->campFullData->id)->region;
            $batch = Batch::where('camp_id', $this->campFullData->id)->where('name', 'like', '%' . $constraint . '%')->first();
            $applicants = $applicants->filter(function ($applicant) use ($constraint, $batch) {
                if($batch) {
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
                if(request()->hasFile('file1')) {
                    $file1 = request()->file('file1');
                    $name1 = $file1->hashName();
                }
                if(request()->hasFile('file2')) {
                    $file2 = request()->file('file2');
                    $name2 = $file2->hashName();
                }
                $files = [];
                if($file1 ?? false) {
                    $disk->put($path, $file1);
                    $image = Image::make(storage_path($path . $name1))->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save(storage_path($path . $name1));
                    $files[] = $path . $name1;
                }
                if($file2 ?? false) {
                    $disk->put($path, $file2);
                    $image = Image::make(storage_path($path . $name2))->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save(storage_path($path . $name2));
                    $files[] = $path . $name2;
                }
                if($applicant) {
                    $applicant->files = json_encode($files);
                    $applicant->save();
                }
            } catch(\Throwable $e) {
                logger($e);
            }
        }
        if($applicant) {
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
        if(isset($contactlog)) {
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
        if(isset($applicant->favored_event)) {
            $applicant->favored_event_split = explode("||/", $applicant->favored_event);
        }
        if(isset($applicant->expertise)) {
            $applicant->expertise_split = explode("||/", $applicant->expertise);
        }
        if(isset($applicant->language)) {
            $applicant->language_split = explode("||/", $applicant->language);
        }
        if(isset($applicant->after_camp_available_day)) {
            $applicant->after_camp_available_day_split = explode("||/", $applicant->after_camp_available_day);
        }
        if(isset($applicant->participation_dates)) {    //evcamp
            $applicant->participation_dates_split = explode("||/", $applicant->participation_dates);
        }
        if(isset($applicant->contact_time)) {
            $applicant->contact_time_split = explode("||/", $applicant->contact_time);
        }
        if(str_contains($camp->table, "vcamp")) {
            return view('backend.in_camp.volunteerInfo', compact('camp', 'batch', 'applicant', 'contactlog'));
        } elseif($camp->table == "acamp") {
            return view('backend.in_camp.attendeeInfoAcamp', compact('camp', 'batch', 'applicant', 'contactlog'));
        } elseif($camp->table == "ceocamp") {
            return view('backend.in_camp.attendeeInfoCeocamp', compact('camp', 'batch', 'applicant', 'contactlog'));
        } elseif($camp->table == "ecamp") {
            return view('backend.in_camp.attendeeInfoEcamp', compact('camp', 'batch', 'applicant', 'contactlog'));
        } elseif($camp->table == "ycamp") {
            return view('backend.in_camp.attendeeInfoYcamp', compact('camp', 'batch', 'applicant', 'contactlog'));
        } else {
            return view('backend.in_camp.attendeeInfo', compact('camp', 'batch', 'applicant', 'contactlog'));
        }
    }

    public function deleteUserRole(Request $request)
    {
        $user = User::find($request->user_id);
        $user->roles()->detach($request->role_id);
        $camp = Vcamp::find($request->camp_id)->mainCamp;
        if ($user->roles()->where('camp_id', $camp->id)->count() == 0) {
            $user->application_log()->detach($request->applicant_id);
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

        if (!$user->canAccessResource(new \App\Models\Applicant(), 'read', $this->campFullData, 'onlyCheckAvailability') && $user->id > 2) {
            return "<h3>沒有權限瀏覽任何學員，或您尚未被指派任何學員</h3>";
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
        $query = Applicant::select("applicants.*", $this->campFullData->table . ".*", $this->campFullData->table . ".id as ''", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->table, 'applicants.id', '=', $this->campFullData->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->id)->withTrashed()->orderBy('deleted_at', 'asc');
        if ($request->batch_id) {
            $query->where('batchs.id', $request->batch_id);
        }
        if ($request->isMethod("post")) {
            $query = $query->whereRaw(\DB::raw($queryStr));
            $request->flash();
        }
        $applicants = $query->get();
        $applicants = $applicants->each(fn ($applicant) => $applicant->id = $applicant->applicant_id);
        if($request->isSetting==1) {
            $isSetting = 1;
        } else {
            $isSetting = 0;
        }

        if ($request->isSettingCarer) {
            $target_group_ids = $user->roles()->where('camp_id', $this->campFullData->id)->where('camp_org.position', 'like', '%關懷小組第%')->get()->pluck('group_id');
            $all_groups = $user->roles()->where('camp_id', $this->campFullData->id)->where('camp_org.section', 'like', '%關懷大組%')->where('all_group', 1)->get();
            if (!count($target_group_ids) && ($user->isAbleTo('\App\Models\CarerApplicantXref.create') || $user->isAbleTo('\App\Models\CarerApplicantXref.assign'))) {
                $permissions = $user->load('roles.permissions')->roles->pluck("permissions")->flatten()->filter(
                    static fn ($permission) => $permission->name == '\App\Models\CarerApplicantXref.create' || $permission->name == '\App\Models\CarerApplicantXref.assign'
                );
                $carers = collect([]);
                foreach ($permissions as $permission) {
                    if ($permission->range == 'na' || $permission->range == 'all') {
                        $carers = $carers->merge(\App\Models\User::with('groupOrgRelation')->whereHas('groupOrgRelation', function ($query) use ($request) {
                            $query->where('camp_id', $this->campFullData->id);
                            if ($request->batch_id) {
                                $query->where('batch_id', $request->batch_id);
                            }
                        })->get());
                    }
                }
                $target_group_ids = $this->campFullData->organizations()->where('camp_org.position', 'like', '%關懷小組第%')->get()->pluck('group_id');
            } else {
                if ($request->batch_id) {
                    $carers = \App\Models\User::with('groupOrgRelation')
                        ->whereHas('groupOrgRelation', function ($query) use ($request, $target_group_ids) {
                            $query->where('batch_id', $request->batch_id)
                                ->whereIn('group_id', $target_group_ids);
                        })->get();
                } else {
                    $carers = \App\Models\User::with('groupOrgRelation')->whereHas('groupOrgRelation', function ($query) use ($target_group_ids) {
                        $query->where('camp_id', $this->campFullData->id)
                            ->whereIn('group_id', $target_group_ids);
                    })->get();
                }
            }
        }

        $applicants = $applicants->filter(fn ($applicant) => $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant));

        if(isset($request->download)) {
            if($applicants) {
                // 參加者報到日期
                $checkInDates = CheckIn::select('check_in_date')->whereIn('applicant_id', $applicants->pluck('sn'))->groupBy('check_in_date')->get();
                if($checkInDates) {
                    $checkInDates = $checkInDates->toArray();
                } else {
                    $checkInDates = array();
                }
                $checkInDates = \Arr::flatten($checkInDates);
                foreach($checkInDates as $key => $checkInDate) {
                    unset($checkInDates[$key]);
                    $checkInDates[(string)$checkInDate] = $checkInDate;
                }
                // 各梯次報到日期填充
                $batches = Batch::whereIn('id', $applicants->pluck('batch_id'))->get();
                foreach($batches as $batch) {
                    $date = Carbon::createFromFormat('Y-m-d', $batch->batch_start);
                    $endDate = Carbon::createFromFormat('Y-m-d', $batch->batch_end);
                    while(1) {
                        if($date > $endDate) {
                            break;
                        }
                        $str = $date->format('Y-m-d');
                        if(!in_array($str, $checkInDates)) {
                            $checkInDates = array_merge($checkInDates, [$str => $str]);
                        }
                        $date->addDay();
                    }
                }
                // 按陣列鍵值升冪排列
                ksort($checkInDates);
                $checkInData = array();
                // 將每人每日的報到資料按報到日期組合成一個陣列
                foreach($checkInDates as $checkInDate => $v) {
                    $checkInData[(string)$checkInDate] = array();
                    $rawCheckInData = CheckIn::select('applicant_id')->where('check_in_date', $checkInDate)->whereIn('applicant_id', $applicants->pluck('sn'))->get();
                    if($rawCheckInData) {
                        $checkInData[(string)$checkInDate] = $rawCheckInData->pluck('applicant_id')->toArray();
                    }
                }

                // 簽到退時間
                $signAvailabilities = $this->campFullData->allSignAvailabilities;
                $signData = [];
                $signDateTimesCols = [];

                if($signAvailabilities) {
                    foreach($signAvailabilities as $signAvailability) {
                        $signData[$signAvailability->id] = [
                            'type'       => $signAvailability->type,
                            'date'       => substr($signAvailability->start, 5, 5),
                            'start'      => substr($signAvailability->start, 11, 5),
                            'end'        => substr($signAvailability->end, 11, 5),
                            'applicants' => $signAvailability->applicants->pluck('id')
                        ];
                        $str = $signAvailability->type == "in" ? "簽到時間：" : "簽退時間：";
                        $signDateTimesCols["SIGN_" . $signAvailability->id] = $str . substr($signAvailability->start, 5, 5) . " " . substr($signAvailability->start, 11, 5) . " ~ " . substr($signAvailability->end, 11, 5);
                    }
                } else {
                    $signData = array();
                }
            }

            $fileName = $this->campFullData->abbreviation . Carbon::now()->format('YmdHis') . '.csv';
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
                if((!isset($signData) || count($signData) == 0)) {
                    if(!isset($checkInDates)) {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? []);
                    } else {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $checkInDates);
                    }
                } else {
                    if(!isset($checkInDates)) {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $signDateTimesCols);
                    } else {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->table) ?? [], $checkInDates, $signDateTimesCols);
                    }
                }
                // 2022 一般教師營需要
                if($this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                    $pos = 44;
                    $columns = array_merge(array_slice($columns, 0, $pos), ["lamrim" => "廣論班"], array_slice($columns, $pos));
                }
                // 移除義工的 工作資訊和其他資訊 欄位標題
                if(strpos($this->campFullData->table, "vcamp")) {
                    unset($columns["group_priority1"]);
                    unset($columns["group_priority2"]);
                    unset($columns["group_priority3"]);
                    unset($columns["employees"]);
                    unset($columns["direct_managed_employees"]);
                    unset($columns["profile_agree"]);
                    unset($columns["portrait_agree"]);
                } else {
                }
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach($columns as $key => $v) {
                        // 移除義工的 工作資訊和其他資訊 欄位內容
                        if(strpos($this->campFullData->table, "vcamp")) {
                            if($key == "group_priority1" || $key == "group_priority2" || $key == "group_priority3" || $key == "employees" || $key == "direct_managed_employees" || $key == "profile_agree" || $key == "portrait_agree") {
                                continue;
                            }
                        } else {
                        }
                        // 2022 一般教師營需要
                        if($v == "廣論班" && $this->campFullData->table == "tcamp" && !$this->campFullData->variant) {
                            $lamrim = \explode("||/", $applicant->blisswisdom_type_complement)[0];
                            if(!$lamrim || $lamrim == "") {
                                array_push($rows, '="無"');
                            } else {
                                array_push($rows, '="' . $lamrim . '"');
                            }
                            continue;
                        }
                        // 使用正規表示式抓出日期欄
                        if(preg_match('/\d\d\d\d-\d\d-\d\d/', $key)) {
                            if(isset($checkInData)) {
                                // 填充報到資料
                                if(in_array($applicant->sn, $checkInData[$key])) {
                                    array_push($rows, '="⭕"');
                                } else {
                                    array_push($rows, '="➖"');
                                }
                            }
                        } elseif(str_contains($key, "SIGN_")) {
                            // 填充簽到資料
                            if($signData[substr($key, 5)]['applicants']->contains($applicant->sn)) {
                                array_push($rows, '="✔️"');
                            } else {
                                array_push($rows, '="❌"');
                            }
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

        $columns_zhtw = config('camps_fields.display.' . $this->campFullData->table);

        return view('backend.integrated_operating_interface.theList')
                ->with('applicants', $applicants)
                ->with('batches', $batches)
                ->with('current_batch', Batch::find($request->batch_id))
                ->with('isShowVolunteers', 0)
                ->with('isSetting', $isSetting)
                ->with('isSettingCarer', $request->isSettingCarer ?? 0)
                ->with('carers', $carers ?? null)
                ->with('isShowLearners', 1)
                ->with('is_ingroup', 0)
                ->with('groupName', '')
                ->with('columns_zhtw', $columns_zhtw)
                ->with('fullName', $this->campFullData->fullName)
                ->with('queryStr', $queryStr ?? null)
                ->with('groups', $this->campFullData->groups)
                ->with('targetGroupIds', $target_group_ids ?? null)
                ->withInput($request->all());
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
        $query = Applicant::select("applicants.*", $this->campFullData->vcamp->table . ".*", $this->campFullData->vcamp->table . ".id as ''", "batchs.name as   bName", "applicants.id as sn", "applicants.created_at as applied_at")
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->join('camps', 'camps.id', '=', 'batchs.camp_id')
                        ->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id')
                        ->where('camps.id', $this->campFullData->vcamp->id)
                        ->whereDoesntHave('user')
                        ->whereIn('batch_id', $batches->pluck('id'))->withTrashed();
        if ($request->isMethod("post")) {
            if ($queryStr != "") {
                $query = $query->where(\DB::raw($queryStr), 1);
            } else {
                $query = $query->whereRaw("1 = 0");
            }
        }
        $applicants = $query->get();
        $applicants = $applicants->each(fn ($applicant) => $applicant->id = $applicant->applicant_id);
        $registeredUsers = \App\Models\User::with([
            'roles' => fn ($q) => $q->where('camp_id', $this->campFullData->id), // 給 IoiSearch 用的資料
            'application_log.user.roles' => fn ($q) => $q->where('camp_id', $this->campFullData->id),  // applicant-list 顯示用的資料
            'application_log.user.roles.batch',
            'application_log' => function ($query) use ($batches) {
                $query->join($this->campFullData->vcamp->table, 'applicants.id', '=', $this->campFullData->vcamp->table . '.applicant_id');
                $query->whereIn('batch_id', $batches->pluck('id'));
            }])
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
        $registeredUsers = $registeredUsers->filter(fn ($user) => $this->user->canAccessResource($user, 'read', $this->campFullData, target: $user));
        $applicants = $applicants->filter(fn ($applicant) => $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant));

        if($request->isSetting==1) {
            $isSetting = 1;
        } else {
            $isSetting = 0;
        }

        if(isset($request->download)) {
            if($applicants) {
                // 參加者報到日期
                $checkInDates = CheckIn::select('check_in_date')->whereIn('applicant_id', $applicants->pluck('sn'))->groupBy('check_in_date')->get();
                if($checkInDates) {
                    $checkInDates = $checkInDates->toArray();
                } else {
                    $checkInDates = array();
                }
                $checkInDates = \Arr::flatten($checkInDates);
                foreach($checkInDates as $key => $checkInDate) {
                    unset($checkInDates[$key]);
                    $checkInDates[(string)$checkInDate] = $checkInDate;
                }
                // 各梯次報到日期填充
                $batches = Batch::whereIn('id', $applicants->pluck('batch_id'))->get();
                foreach($batches as $batch) {
                    $date = Carbon::createFromFormat('Y-m-d', $batch->batch_start);
                    $endDate = Carbon::createFromFormat('Y-m-d', $batch->batch_end);
                    while(1) {
                        if($date > $endDate) {
                            break;
                        }
                        $str = $date->format('Y-m-d');
                        if(!in_array($str, $checkInDates)) {
                            $checkInDates = array_merge($checkInDates, [$str => $str]);
                        }
                        $date->addDay();
                    }
                }
                // 按陣列鍵值升冪排列
                ksort($checkInDates);
                $checkInData = array();
                // 將每人每日的報到資料按報到日期組合成一個陣列
                foreach($checkInDates as $checkInDate => $v) {
                    $checkInData[(string)$checkInDate] = array();
                    $rawCheckInData = CheckIn::select('applicant_id')->where('check_in_date', $checkInDate)->whereIn('applicant_id', $applicants->pluck('sn'))->get();
                    if($rawCheckInData) {
                        $checkInData[(string)$checkInDate] = $rawCheckInData->pluck('applicant_id')->toArray();
                    }
                }

                // 簽到退時間
                $signAvailabilities = $this->campFullData->vcamp->allSignAvailabilities;
                $signData = [];
                $signDateTimesCols = [];

                if($signAvailabilities) {
                    foreach($signAvailabilities as $signAvailability) {
                        $signData[$signAvailability->id] = [
                            'type'       => $signAvailability->type,
                            'date'       => substr($signAvailability->start, 5, 5),
                            'start'      => substr($signAvailability->start, 11, 5),
                            'end'        => substr($signAvailability->end, 11, 5),
                            'applicants' => $signAvailability->applicants->pluck('id')
                        ];
                        $str = $signAvailability->type == "in" ? "簽到時間：" : "簽退時間：";
                        $signDateTimesCols["SIGN_" . $signAvailability->id] = $str . substr($signAvailability->start, 5, 5) . " " . substr($signAvailability->start, 11, 5) . " ~ " . substr($signAvailability->end, 11, 5);
                    }
                } else {
                    $signData = array();
                }
            }

            $fileName = $this->campFullData->vcamp->abbreviation . Carbon::now()->format('YmdHis') . '.csv';
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
                if((!isset($signData) || count($signData) == 0)) {
                    if(!isset($checkInDates)) {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->vcamp->table));
                    } else {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->vcamp->table), $checkInDates);
                    }
                } else {
                    if(!isset($checkInDates)) {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->vcamp->table), $signDateTimesCols);
                    } else {
                        $columns = array_merge(config('camps_fields.general'), config('camps_fields.' . $this->campFullData->vcamp->table), $checkInDates, $signDateTimesCols);
                    }
                }
                fputcsv($file, $columns);

                foreach ($applicants as $applicant) {
                    $rows = array();
                    foreach($columns as $key => $v) {
                        // 2022 一般教師營需要
                        if($v == "廣論班" && $this->campFullData->vcamp->table == "tcamp" && !$this->campFullData->vcamp->variant) {
                            $lamrim = \explode("||/", $applicant->blisswisdom_type_complement)[0];
                            if(!$lamrim || $lamrim == "") {
                                array_push($rows, '="無"');
                            } else {
                                array_push($rows, '="' . $lamrim . '"');
                            }
                            continue;
                        }
                        // 使用正規表示式抓出日期欄
                        if(preg_match('/\d\d\d\d-\d\d-\d\d/', $key)) {
                            if(isset($checkInData)) {
                                // 填充報到資料
                                if(in_array($applicant->sn, $checkInData[$key])) {
                                    array_push($rows, '="⭕"');
                                } else {
                                    array_push($rows, '="➖"');
                                }
                            }
                        } elseif(str_contains($key, "SIGN_")) {
                            // 填充簽到資料
                            if($signData[substr($key, 5)]['applicants']->contains($applicant->sn)) {
                                array_push($rows, '="✔️"');
                            } else {
                                array_push($rows, '="❌"');
                            }
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

        $camp_str = $this->campFullData->vcamp->table;
        $columns_zhtw = config('camps_fields.display.' . $camp_str);

        return view('backend.integrated_operating_interface.theList')
                ->with('applicants', $applicants)
                ->with('registeredVolunteers', $registeredUsers)
                ->with('batches', $batches)
                ->with('current_batch', Batch::find($request->batch_id))
                ->with('isShowVolunteers', 1)
                ->with('isSetting', $isSetting)
                ->with('isSettingCarer', $request->isSettingCarer ?? 0)
                ->with('carers', null)
                ->with('isShowLearners', 0)
                ->with('is_ingroup', 0)
                ->with('groupName', '')
                ->with('columns_zhtw', $columns_zhtw)
                ->with('fullName', $this->campFullData->fullName)
                ->with('groups', $this->campFullData->roles)
                ->with('queryStr', $queryStr ?? '')
                ->with('queryRoles', $queryRoles ?? '');
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
            $query = $query->where(\DB::raw($queryStr), 1);
        }
        $applicants = $query->get();
        $applicants = $applicants->each(fn ($applicant) => $applicant->id = $applicant->applicant_id);

        $registeredUsers = \App\Models\User::with('roles')->whereHas('roles', function ($query) {
            $query->where('camp_id', $this->campFullData->id)->where('position', 'like', '%關懷小組%');
        })->get();
        if($request->isSetting==1) {
            $isSetting = 1;
        } else {
            $isSetting = 0;
        }

        $camp_str = $this->campFullData->vcamp->table;
        $columns_zhtw = config('camps_fields.display.' . $camp_str);

        return view('backend.integrated_operating_interface.theList')
                ->with('registeredVolunteers', $registeredUsers)
                ->with('applicants', $applicants)
                ->with('batches', $batches)
                ->with('isShowVolunteers', 1)
                ->with('current_batch', Batch::find($request->batch_id))
                ->with('isSetting', $isSetting)
                ->with('isSettingCarer', $request->isSettingCarer ?? 0)
                ->with('carers', null)
                ->with('isShowLearners', 1)
                ->with('is_ingroup', 1)
                ->with('groupName', '第1組')
                ->with('columns_zhtw', $columns_zhtw)
                ->with('fullName', $this->campFullData->fullName)
                ->with('groups', $this->campFullData->roles)
                ->with('queryStr', $queryStr ?? '');
    }

    public function getAvatar($camp_id, $id)
    {
        $applicant = Applicant::find($id);
        if ($applicant->avatar) {
            return response()->file(base_path(\Storage::disk('local')->url($applicant->avatar)));
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
        if(!$download) {
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
                    foreach($columns as $key => $v) {
                        if($key == "cbname") {
                            array_push($rows, '="' . $accounting->batch->camp->abbreviation . " - " . $accounting->batch->name . '"');
                        } elseif($key == "shouldPay" || $key == "amount") {
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
            if($admitted_sn == $request->double_check || $applicant->id == $request->double_check) {
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
        return view("backend.findAccounting");
    }

    public function customMail(Request $request)
    {
        return view("backend.other.customMail");
    }

    public function selectMailTarget()
    {
        $batches = Batch::where('camp_id', $this->camp_id)->get()->all();
        foreach($batches as &$batch) {
            $batch->regions = Applicant::select('region')->where('batch_id', $batch->id)->where('is_admitted', 1)->groupBy('region')->get();
            foreach($batch->regions as &$region) {
                $region->groups = Applicant::select('group', \DB::raw('count(*) as count'))->where('batch_id', $batch->id)->where('region', $region->region)->where('is_admitted', 1)->groupBy('group')->get();
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
        if($request->target == 'all') { // 全體錄取人士
            $batch_ids = $camp->batchs()->pluck('id')->toArray();
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->whereNotNull(['group', 'number'])->where([['group', '<>', ''], ['number', '<>', '']])->whereIn('batch_id', $batch_ids)->get();
        } elseif($request->target == 'batch') { // 梯次錄取人士
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->whereNotNull(['group', 'number'])->where([['group', '<>', ''], ['number', '<>', '']])->where('batch_id', $request->batch_id)->get();
        } elseif($request->target == 'group') { // 梯次組別錄取人士
            $receivers = Applicant::select('batch_id', 'email')->where('is_admitted', 1)->where('group', '=', $request->group_no)->where('batch_id', $request->batch_id)->get();
        }
        $files = array();
        for($i  = 0; $i < 3; $i++) {
            if ($request->hasFile('attachment' . $i) && $request->file('attachment' . $i)->isValid()) {
                $file = $request->file('attachment' . $i);
                $originalname = $file->getClientOriginalName();
                $fileName = time().$originalname;
                $file->storeAs('attachment', $fileName);
                $files[$i] = $fileName;
            }
        }
        foreach($receivers as $receiver) {
            \Mail::to($receiver)->queue(new \App\Mail\CustomMail($request->subject, $request->content, $files, $receiver->batch->camp->variant ?? $receiver->batch->camp->table));
        }
        return view("backend.other.mailSent", ['message' => '已成功將自定郵件送入任務佇列。']);
    }

    public function editRemark(Request $request, $camp_id)
    {
        $formData = $request->toArray();
        $applicant_id = $formData['applicant_id'];
        $applicant = Applicant::find($applicant_id);
        $applicant->remark=$formData['remark'];
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
            $newSet['takenby_id'] = auth()->user()->id;

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
        return view('backend.in_camp.addContactLog', compact("camp_id", "applicant", "contactlog", "todo"));
        //    return view('backend.in_camp.modifyContactLog', compact("applicant", "contactlog"));
    }

    public function showContactLogs(Request $request, $camp_id, $applicant_id)
    {
        $formData = $request->toArray();
        //$applicant_id = $formData['applicant_id'];
        $applicant = Applicant::withTrashed()->find($applicant_id);
        $contactlogs = $applicant->contactlog->sortByDesc('id');
        //dd($contactlogs);
        if(isset($contactlogs)) {
            foreach($contactlogs as &$contactlog) {
                $contactlog = $this->backendService->setTakenByName($contactlog);
            }
        }
        return view('backend.in_camp.contactLogList', compact('camp_id', 'applicant', 'contactlogs'));
    }

    public function removeContactLog(Request $request)
    {
        $result = \App\Models\ContactLog::find($request->contactlog_id)->delete();

        if($result) {
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
            return redirect()->route("showVolunteers", [$request->camp_id, 'isSetting' => 1, 'batch_id' => $request->group_id]);
        }
    }

    public function switchToUser($id)
    {
        if (auth()->user()->email != "lzong.tw@gmail.com") {
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
