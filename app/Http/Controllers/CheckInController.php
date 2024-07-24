<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\CheckIn;
use App\Models\OrgUser;
use App\Models\User;
use App\Models\Vcamp;
use View;
use Carbon\Carbon;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\MessageBag;

class CheckInController extends Controller
{
    protected $campDataService;
    protected $applicantService;
    protected $batch_id;
    protected $camp_data;
    protected $batch;
    protected $has_attend_data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService, ApplicantService $applicantService, Request $request)
    {
        $this->middleware('auth');
        $this->middleware('permitted');
        if ($request->route()->uri != 'checkin/selectCamp') {
            if ($request->camp_id) {
                $camp = Camp::find($request->camp_id);
                $this->camp = $camp;
            } elseif ($request->route()->uri == 'checkin') {
                return \Redirect::to(route('selectCamp'))->send();
            } else {
                return "<h3>請選擇營隊</h3>";
            }
            if($this->camp->table == 'ycamp' || $this->camp->table == 'acamp') {
                $this->has_attend_data = true;
            }
            View::share('camp', $this->camp);
            View::share('has_attend_data', $this->has_attend_data);
        }
        $this->campDataService = $campDataService;
        $this->applicantService = $applicantService;
        $this->persist(camp: $camp ?? null);
    }

    public function persist(...$args)
    {
        $that = $this;
        // https://laracasts.com/discuss/channels/laravel/authuser-return-null-in-construct
        $this->middleware(function ($request, $next) use ($that, $args) {
            $that->user = \App\Models\User::find(auth()->user()->id);
            $that->isVcamp = str_contains($args["camp"], "vcamp");
            View::share('currentUser', $that->user);
            return $next($request);
        });
    }

    public function index()
    {
        return view('checkIn.home');
    }

    public function selectCamp()
    {
        $camps = $this->user->roles->map(function ($role) {
            return $role->camp;
        })->unique();
        foreach ($camps as $camp) {
            if ($camp->vcamp && $this->user->canAccessResource(CheckIn::class, 'create', $camp)) {
                $camps = $camps->push($camp->vcamp);
            }
        }
        if ($camps->count() == 0 && $this->user->id == 1) {
            $camps = Camp::orderByDesc('id')->get();
        }
        return view('checkIn.select', compact('camps'));
    }

    public function query(Request $request)
    {
        $group = null;
        $number = null;
        if (!$this->camp->is_vcamp()) {
            if ((preg_match("/\p{Han}+/u", $request->query_str) && \Str::length($request->query_str) == 3) ||
                (str_contains($request->query_str, '第') && str_contains($request->query_str, '組'))) {
                $group = substr($request->query_str, 0, 9);
            } elseif(\Str::length($request->query_str) == 3 ||
                    in_array(
                        $request->query_str,
                        ["貴賓", "後補名單", "知音", "得獎相關人員", "其他", "福業汐止物流",
                    "僧伽護持聯誼會", "班幹部", "傳心", "里仁", "幕僚", "台北學苑管理處",
                    "台北學苑淨智處", "淨智北區", "淨智總部", "慈心總部", "台北學苑文教處", "文教總部"]
                    )) {
                $group = $request->query_str;
            } elseif(\Str::length($request->query_str) == 5) {
                $group = substr($request->query_str, 0, 3);
                $number = substr($request->query_str, 3, 2);
            }
        } else {
            $group = $request->query_str;
        }
        $constraint = function ($query) { $query->where('camps.id', $this->camp->id); };
        if ($this->camp->is_vcamp()) {
            $main_camp = Vcamp::find($this->camp->id)->mainCamp;
            if ($group) {
                $groups = $this->camp->organizations()->where(function ($query) use ($group) {
                    return $query->where('section', 'like', '%' . $group . '%')->orWhere('position', 'like', '%' . $group . '%');
                })->get();
                $groups = $groups->merge($main_camp->organizations()->where(function ($query) use ($group) {
                    return $query->where('section', 'like', '%' . $group . '%')->orWhere('position', 'like', '%' . $group . '%');
                })->get());
            }
            $ids = OrgUser::whereIn('org_id', $groups->pluck('id'))->get()->pluck('user_id')->toArray();
            $users = User::with(['application_log' => function ($query) {
                return $query->whereIn('applicants.batch_id', $this->camp->batchs->pluck('id')->toArray());
            }])->whereIn('id', $ids)->get();
            $applicants = Applicant::with(['batch',
                                           'batch.camp' => $constraint,
                                           'user.roles' => function ($query) use ($main_camp) {
                                               return $query->where('camp_org.camp_id', $main_camp->id);
                                           }])
                            ->whereHas('batch.camp', $constraint)
                            ->where('is_admitted', 1)
                            // 暫時性程式碼，待企業營及菁英營結束後刪除
                            ->when($this->camp->id == 77 || $this->camp->id == 78 || $this->camp->id == 79 || $this->camp->id == 80, function ($query) {
                                $query->whereIn('batch_id', [166, 168, 183, 184]);
                            })
                            ->where(function ($query) use ($request, $users) {
                                $query->where('applicants.id', $request->query_str);
                                $query->orWhere('name', 'like', '%' . $request->query_str . '%')
                                ->orWhere(\DB::raw("replace(mobile, '-', '')"), 'like', '%' . $request->query_str . '%')
                                ->orWhere(\DB::raw("replace(mobile, '(', '')"), 'like', '%' . $request->query_str . '%')
                                ->orWhere(\DB::raw("replace(mobile, ')', '')"), 'like', '%' . $request->query_str . '%')
                                ->orWhere(\DB::raw("replace(mobile, '（', '')"), 'like', '%' . $request->query_str . '%')
                                ->orWhere(\DB::raw("replace(mobile, '）', '')"), 'like', '%' . $request->query_str . '%')
                                ->orwhereIn('applicants.id', $users->pluck('application_log')->flatten()->pluck('id'));
                            })->get()->sortBy(['batch.camp.id', 'batch.id']);
        } else {
            $numbers = collect([]);
            if ($group) {
                $groups = $this->camp->groups()->where('alias', 'like', '%' . $group . '%')->get();
                if ($number) {
                    foreach ($groups as $g) {
                        $num =  $g->numbers()?->where("number", $number)->get();
                        $numbers = $numbers->merge($num);
                    }
                }
            }
            $applicants = Applicant::with(['batch', 'batch.camp' => $constraint, 'groupRelation', 'numberRelation'])
                                        ->whereHas('batch.camp', $constraint)
                                        ->where('is_admitted', 1)
                                        // 暫時性程式碼，待企業營及菁英營結束後刪除
                                        ->when($this->camp->id == 77 || $this->camp->id == 78 || $this->camp->id == 79 || $this->camp->id == 80, function ($query) {
                                            $query->whereIn('batch_id', [166, 168, 183, 184]);
                                        })
                                        ->where(function ($query) {
                                            if($this->has_attend_data) {
                                                $query->where('is_attend', 1);
                                            }
                                        })
                                        ->whereNotNull('group_id')
                                        ->where(function ($query) use ($request, $groups, $numbers) {
                                            $query->where('applicants.id', $request->query_str);
                                            if ($groups && (count($numbers) == 0 || !$numbers)) {
                                                $query->orWhereIn('group_id', $groups?->pluck("id"));
                                            }
                                            if ((count($numbers) || $numbers) && $groups) {
                                                $query->orWhere(function ($query) use ($groups, $numbers) {
                                                    $query->whereIn('group_id', $groups?->pluck("id"));
                                                    $query->whereIn('number_id', $numbers?->pluck('id'));
                                                });
                                            }
                                            $query->orWhere('name', 'like', '%' . $request->query_str . '%')
                                            ->orWhere(\DB::raw("replace(mobile, '-', '')"), 'like', '%' . $request->query_str . '%')
                                            ->orWhere(\DB::raw("replace(mobile, '(', '')"), 'like', '%' . $request->query_str . '%')
                                            ->orWhere(\DB::raw("replace(mobile, ')', '')"), 'like', '%' . $request->query_str . '%')
                                            ->orWhere(\DB::raw("replace(mobile, '（', '')"), 'like', '%' . $request->query_str . '%')
                                            ->orWhere(\DB::raw("replace(mobile, '）', '')"), 'like', '%' . $request->query_str . '%');
                                        })->get()->sortBy(['batch.camp.id', 'batch.id', 'groupRelation.alias', 'numberRelation.number']);
        }
        $batches = $applicants->pluck('batch.name', 'batch.id')->unique();
        $request->flash();
        return view('checkIn.home', compact('applicants', 'batches'))->with('query', $request->query_str);
    }

    public function checkIn(Request $request)
    {
        if(CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', Carbon::today()->format('Y-m-d'))->first()) {
            return back()->withErrors(['無法重複報到。']);
        } else {
            $applicant = Applicant::find($request->applicant_id);
            if($applicant->deposit - $applicant->fee < 0) {
                //return back()->withErrors([$applicant->name . '未繳費，無法報到。']);
            }
            $checkin = new CheckIn();
            $checkin->applicant_id = $request->applicant_id;
            $checkin->checker_id = \Auth()->user()->id;
            $checkin->check_in_date = Carbon::today()->format('Y-m-d');
            $checkin->save();
        }
        \Session::flash('message', "報到成功。");
        return back();
    }

    public function massCheckIn(Request $request)
    {
        $errors = \Session::get('errors', new ViewErrorBag());

        if (!$errors instanceof ViewErrorBag) {
            $errors = new ViewErrorBag();
        }
        $bag = $errors->getBags()['default'] ?? new MessageBag();
        $succeded = "";
        foreach($request->applicant_multi_values as $key => $applicant_id) {
            if(CheckIn::where('applicant_id', $applicant_id)->where('check_in_date', Carbon::today()->format('Y-m-d'))->first()) {
                $bag->add('default', $applicant_id . "已報到過，無法重複報到。");

                // $request->session()->put(
                //     'errors', $errors->put('default', $bag)
                // );
            } else {
                $applicant = Applicant::find($applicant_id);
                if($applicant->deposit - $applicant->fee < 0) {
                    //return back()->withErrors([$applicant->name . '未繳費，無法報到。']);
                }
                $checkin = new CheckIn();
                $checkin->applicant_id = $applicant_id;
                $checkin->checker_id = \Auth()->user()->id;
                $checkin->check_in_date = Carbon::today()->format('Y-m-d');
                $checkin->save();
                $succeded .= $applicant->name . "報到成功。";
                if ($key != count($request->applicant_multi_values) - 1 && count($request->applicant_multi_values) != 1) {
                    $succeded .= "<br>";
                }
            }
        }
        $query_str = $request->query_str;
        $camp_id = $request->camp_id ?? 1;
        return redirect(route('checkInPage', ['query_str' => $query_str, 'camp_id' => $camp_id]))
                    ->with('shouldRefresh', 1)
                    ->with('errors', $errors->put('default', $bag))
                    ->with('message', $succeded);
    }

    public function by_QR(Request $request)
    {
        try {
            $dataStr = [['報名資料', '梯次', '錄取序號', '姓名'], ['優惠碼', '場次', '流水號', '優惠碼']];
            $resultStr = [['梯次'], ['限制']];
            $pivot = 0;
            if($request->coupon_code) {
                $applicant = Applicant::where('name', $request->coupon_code)->first();
                $pivot = 1;
            } else {
                $applicant = Applicant::find($request->applicant_id);
            }
            if(!$applicant) {
                return response()->json([
                    'msg' => '<h4 class="text-danger">找不到' . $dataStr[$pivot][0] . '，請檢查後重試</h4>'
                ]);
            }
            if ($applicant->camp->id != $this->camp->id) {
                return response()->json([
                    'msg' => '<h4 class="text-danger">該學員 / 義工報名的是' . $applicant->camp->abbreviation ?? $applicant->camp->fullName . '，非屬本營隊，請檢查後重試</h4>'
                ]);
            }
            $str = $resultStr[$pivot][0] . '：' . $applicant->batch->name . '<br>' . $dataStr[$pivot][2] . '：' . $applicant->group . $applicant->number . '<br>' . $dataStr[$pivot][3] . '：' . $applicant->name;
            if($applicant->deposit - $applicant->fee < 0) {
                //
                /*return response()->json([
                    'msg' => $str . '<h4 class="text-danger">未繳費，無法報到</h4>'
                ]);*/
            }
            if(!$applicant->is_admitted) {
                return response()->json([
                    'msg' => $str . '<h4 class="text-danger">未錄取，無法報到</h4>'
                ]);
            }
            if(!$applicant->group_id) {
                return response()->json([
                    'msg' => $str . '<h4 class="text-danger">未分組，無法報到</h4>'
                ]);
            }
            if($pivot == 1) {
                $hasCheckedIn = CheckIn::where('applicant_id', $applicant->id)->first();
            } else {
                $hasCheckedIn = CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', Carbon::today()->format('Y-m-d'))->first();
            }
            if($hasCheckedIn) {
                if($pivot == 1) {
                    return response()->json([
                        'msg' => $str . '<h4 class="text-warning">已於 ' . $hasCheckedIn->created_at . ' 兌換，無法重複使用</h4>'
                    ]);
                }
                return response()->json([
                    'msg' => $str . '<h4 class="text-warning">已報到完成，無法重複報到</h4>'
                ]);
            } else {
                $checkin = new CheckIn();
                $checkin->applicant_id = $applicant->id;
                $checkin->checker_id = \Auth()->user()->id;
                $checkin->check_in_date = Carbon::today()->format('Y-m-d');
                $checkin->save();
                if($pivot == 1) {
                    return response()->json([
                        'msg' => $str . '<h4 class="text-success">兌換完成</h4>'
                    ]);
                }
                return response()->json([
                    'msg' => $str . '<h4 class="text-success">報到完成</h4>'
                ]);
            }
        } catch(\Exception $e) {
            logger($e);
            if($pivot == 1) {
                return response()->json([
                    'msg' => '<h4 class="text-danger">發生未預期錯誤，無法完成兌換程序</h4>'
                ]);
            }
            return response()->json([
                'msg' => '<h4 class="text-danger">發生未預期錯誤，無法完成報到程序</h4>'
            ]);
        }
    }

    public function realtimeStat()
    {
        try {
            $applicants = Applicant::select('applicants.id')
                        ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->where('batchs.camp_id', $this->camp->id)
                        ->where(function ($query) {
                            if($this->has_attend_data) {
                                $query->where('is_attend', 1);
                            }
                        })
                        ->where(\DB::raw("fee - deposit"), "<=", \DB::raw('0'))
                        ->whereNotNull('group_id')
                        ->where('group_id', '<>', \DB::raw('""'))
                        // 這邊可能不需要判斷這麼多
                        // ->where([['batch_start', '<=', Carbon::today()], ['batch_end', '>=', Carbon::today()]])
                        // 暫時性程式碼，待企業營及菁英營結束後刪除
                        ->when($this->camp->id == 77 || $this->camp->id == 78 || $this->camp->id == 79 || $this->camp->id == 80, function ($query) {
                            $query->whereIn('batch_id', [166, 168, 183, 184]);
                        })
                        ->get();
            $checkedInCount = CheckIn::where('check_in_date', Carbon::today()->format('Y-m-d'))->whereIn('applicant_id', $applicants)->count();
            $applicants = $applicants->count();
            return response()->json([
                'msg' => $checkedInCount . ' / ' . ($applicants - $checkedInCount)
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'msg' => '<h6 class="text-danger">發生未預期錯誤，無法顯示報到人數</h6>'
            ]);
        }
    }

    public function detailedStat(Request $request)
    {
        $allApplicants = Applicant::select('applicants.id')
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->where('batchs.camp_id', $this->camp->id)
                            ->where(\DB::raw("fee - deposit"), "<=", \DB::raw('0'))
                            ->whereNotNull('group_id')
                            ->where('group_id', '<>', \DB::raw('""'))
                            ->where(function ($query) {
                                if($this->has_attend_data) {
                                    $query->where('is_attend', 1);
                                }
                            })
                            ->get();
        $checkedInData = CheckIn::where('check_in_date', Carbon::today()->format('Y-m-d'))->whereIn('applicant_id', $allApplicants)->get();
        $checkedInApplicants = Applicant::select('batchs.name', \DB::raw('count(*) as count'))
                    ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                    ->where('batchs.camp_id', $this->camp->id)
                    ->whereIn('applicants.id', $checkedInData->pluck('applicant_id'))
                    ->groupBy('batchs.name')
                    ->get();
        $batches = $allApplicants->pluck('batch.name')->unique();
        $batchArray = array();
        foreach ($batches as $key => $batch) {
            $tmp = $allApplicants->where('batch.name', $batch);
            $batchName = $batch;
            $batchArray[$key]['name'] = $batchName;
            $batchArray[$key]['checkedInApplicants'] = $checkedInApplicants->where('name', $batch)->first();
            $batchArray[$key]['checkedInApplicants'] = $batchArray[$key]['checkedInApplicants']->count ?? 0;
            $batchArray[$key]['allApplicants'] = $tmp->count();
        }
        $checkedInCount = $checkedInData->count();
        $applicantsCount = $allApplicants->count();
        return view('checkIn.detailedStat', compact('allApplicants', 'checkedInApplicants', 'batchArray', 'checkedInCount', 'applicantsCount'));
    }

    public function detailedStatOptimized(Request $request)
    {
        $allBatchesApplicants = Applicant::select('applicants.id')
                            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->where('batchs.camp_id', $this->camp->id)
                            ->where(\DB::raw("fee - deposit"), "<=", \DB::raw('0'))
                            ->whereNotNull('group_id')
                            ->where('group_id', '<>', \DB::raw('""'))
                            ->where(function ($query) {
                                if($this->has_attend_data) {
                                    $query->where('is_attend', 1);
                                }
                            })
                            ->get();
        // 取得報到資料
        $checkedInData = CheckIn::where('check_in_date', Carbon::today()->format('Y-m-d'))->whereIn('applicant_id', $allBatchesApplicants)->get();
        // 取得梯次
        $batches = Batch::where("camp_id", $this->camp->id)->where([['batch_start', '<=', Carbon::today()], ['batch_end', '>=', Carbon::today()]])->get();
        $batchArray = array();
        // 照梯次取報名人
        $applicantsCount = 0;
        $allApplicants = null;
        $checkedInApplicants = null;
        foreach($batches as $key => $batch) {
            $allApplicants = Applicant::join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                            ->where('batchs.camp_id', $this->camp->id)
                            ->where(\DB::raw("fee - deposit"), "<=", 0)
                            ->where("batch_id", $batch->id)
                            // 暫時性程式碼，待企業營及菁英營結束後刪除
                            ->when($this->camp->id == 77 || $this->camp->id == 78 || $this->camp->id == 79 || $this->camp->id == 80, function ($query) {
                                $query->whereIn('batch_id', [166, 168, 183, 184]);
                            })
                            ->whereNotNull('group_id')
                            ->where(function ($query) {
                                if($this->has_attend_data) {
                                    $query->where('is_attend', 1);
                                }
                            })
                            ->where('group_id', '<>', '')
                            ->count();
            $checkedInApplicants = Applicant::where("batch_id", $batch->id)
                                ->whereIn('applicants.id', $checkedInData->pluck('applicant_id'))
                                ->count();
            $batchArray[$key]['name'] = $batch->name;
            $batchArray[$key]['allApplicants'] = $allApplicants;
            $batchArray[$key]['checkedInApplicants'] = $checkedInApplicants;
            $applicantsCount += $allApplicants;
        }
        $checkedInCount = $checkedInData->count();
        return view('checkIn.detailedStat', compact('allApplicants', 'checkedInApplicants', 'batchArray', 'checkedInCount', 'applicantsCount'));
    }

    public function uncheckIn(Request $request)
    {
        $record = CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', $request->check_in_date)->first();
        try {
            if($record) {
                $record->delete();
                \Session::flash('message', "報消報到成功。");
            } else {
                \Session::flash('message', "該筆報到資料已不存在。");
            }
        } catch (\Exception $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
                \Sentry\captureMessage('取消報到過程發生未知錯誤。');
            }
            logger($e->getMessage());
            return back()->withErrors(['取消報到過程發生未知錯誤。']);
        }
        return back();
    }
}
