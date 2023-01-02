<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\CheckIn;
use View;
use Carbon\Carbon;

class CheckInController extends Controller {
    protected $campDataService, $applicantService, $batch_id, $camp_data, $batch, $has_attend_data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService, ApplicantService $applicantService, Request $request) {
        $this->middleware('auth');
        $camp = Batch::orderBy(\DB::raw('ABS(DATEDIFF(`batch_start`, NOW()))'))->first()->camp;
        $request->camp_id = $camp->id;
        $this->middleware('permitted');
        $this->camp = $camp;
        $this->campDataService = $campDataService;
        $this->applicantService = $applicantService;
        if($this->camp->table == 'ycamp' || $this->camp->table == 'acamp'){
            $this->has_attend_data = true;
        }
        View::share('camp', $this->camp);
    }

    public function index() {
        return view('checkIn.home');
    }

    public function query(Request $request) {
        $group = null;
        $number = null;
        if(\Str::length($request->query_str) == 5){
            $group = substr($request->query_str, 0, 3);
            $number = substr($request->query_str, 3, 2);
        }
        $constraint = function($query){ $query->where('camps.id', $this->camp->id); };
        $applicantGroupConstraint = function ($query, $group) {
            $query->where('applicants_groups.alias', $group);
        };
        $applicantNumberConstraint = function ($query, $number) {
            $query->where('group_numbers.number', $number);
        };
        $applicants = Applicant::with(['batch', 'batch.camp' => $constraint])
            ->whereHas('batch.camp', $constraint)
            ->where('is_admitted', 1)
            ->where(function($query){
                if($this->has_attend_data){
                    $query->where('is_attend', 1);
                }
            })
            ->where(function($query) use ($request, $applicantGroupConstraint, $applicantNumberConstraint, $group, $number) {
                $query->where('id', $request->query_str)
                ->orWhereHas('groupRelation', $applicantGroupConstraint($query, $group));
                if($group && $number){
                    $query->orWhereHas([['groupRelation', $applicantGroupConstraint($query, $group)], ['numberRelation', $applicantNumberConstraint($query, $number)]]);
                }
                $query->orWhere('name', 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '-', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '(', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, ')', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '（', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '）', '')"), 'like', '%' . $request->query_str . '%');
            })->orderBy('number', 'asc')->get();
        $batches = $applicants->pluck('batch.name', 'batch.id')->unique();
        $request->flash();
        return view('checkIn.home', compact('applicants', 'batches'));
    }

    public function checkIn(Request $request) {
        if(CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', Carbon::today()->format('Y-m-d'))->first()){
            return back()->withErrors(['無法重複報到。']);
        }
        else{
            $applicant = Applicant::find($request->applicant_id);
            if($applicant->deposit - $applicant->fee < 0){
                return back()->withErrors([$applicant->name . '未繳費，無法報到。']);
            }
            $checkin = new CheckIn;
            $checkin->applicant_id = $request->applicant_id;
            $checkin->checker_id = \Auth()->user()->id;
            $checkin->check_in_date = Carbon::today()->format('Y-m-d');
            $checkin->save();
        }
        \Session::flash('message', "報到成功。");
        return back();
    }

    public function by_QR(Request $request) {
        try{
            $dataStr = [['報名資料', '梯次', '錄取序號', '姓名'], ['優惠碼', '場次', '流水號', '優惠碼']];
            $resultStr = [['梯次'], ['限制']];
            $pivot = 0;
            if($request->coupon_code){
                $applicant = Applicant::where('name', $request->coupon_code)->first();
                $pivot = 1;
            }
            else{
                $applicant = Applicant::find($request->applicant_id);
            }
            if(!$applicant){
                return response()->json([
                    'msg' => '<h4 class="text-danger">找不到' . $dataStr[$pivot][0] . '，請檢查後重試</h4>'
                ]);
            }
            $str = $resultStr[$pivot][0] . '：' . $applicant->batch->name . '<br>' . $dataStr[$pivot][2] . '：' . $applicant->group . $applicant->number . '<br>' . $dataStr[$pivot][3] . '：' . $applicant->name;
            if($applicant->deposit - $applicant->fee < 0){
                return response()->json([
                    'msg' => $str . '<h4 class="text-danger">未繳費，無法報到</h4>'
                ]);
            }
            if($pivot == 1){
                $hasCheckedIn = CheckIn::where('applicant_id', $applicant->id)->first();
            }
            else{
                $hasCheckedIn = CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', Carbon::today()->format('Y-m-d'))->first();
            }
            if($hasCheckedIn){
                if($pivot == 1){
                    return response()->json([
                        'msg' => $str . '<h4 class="text-warning">已於 ' . $hasCheckedIn->created_at . ' 兌換，無法重複使用</h4>'
                    ]);
                }
                return response()->json([
                    'msg' => $str . '<h4 class="text-warning">已報到完成，無法重複報到</h4>'
                ]);
            }
            else{
                $checkin = new CheckIn;
                $checkin->applicant_id = $applicant->id;
                $checkin->checker_id = \Auth()->user()->id;
                $checkin->check_in_date = Carbon::today()->format('Y-m-d');
                $checkin->save();
                if($pivot == 1){
                    return response()->json([
                        'msg' => $str . '<h4 class="text-success">兌換完成</h4>'
                    ]);
                }
                return response()->json([
                    'msg' => $str . '<h4 class="text-success">報到完成</h4>'
                ]);
            }
        }
        catch(\Exception $e){
            logger($e);
            if($pivot == 1){
                return response()->json([
                    'msg' => '<h4 class="text-danger">發生未預期錯誤，無法完成兌換程序</h4>'
                ]);
            }
            return response()->json([
                'msg' => '<h4 class="text-danger">發生未預期錯誤，無法完成報到程序</h4>'
            ]);
        }
    }

    public function realtimeStat() {
        try{
            $checkedInCount = CheckIn::where('check_in_date', Carbon::today()->format('Y-m-d'))->count();
            $applicants = Applicant::join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                        ->where('batchs.camp_id', $this->camp->id)
                        ->where(function($query){
                            if($this->has_attend_data){
                                $query->where('is_attend', 1);
                            }
                        })
                        ->where(\DB::raw('fee - deposit'), '<=', 0)
                        ->whereNotNull('group_id')
                        ->where('group_id', '<>', '')
                        ->where([['batch_start', '<=', Carbon::today()], ['batch_end', '>=', Carbon::today()]])
                        ->count();
            return response()->json([
                'msg' => $checkedInCount . ' / ' . ($applicants - $checkedInCount)
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'msg' => '<h6 class="text-danger">發生未預期錯誤，無法顯示報到人數</h6>'
            ]);
        }
    }

    public function detailedStat(Request $request) {
        $checkedInData = CheckIn::where('check_in_date', Carbon::today()->format('Y-m-d'))->get();
        $allApplicants = Applicant::join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                    ->where('batchs.camp_id', $this->camp->id)
                    ->where(\DB::raw('fee - deposit'), '<=', 0)
                    ->where(function($query){
                        if($this->has_attend_data){
                            $query->where('is_attend', 1);
                        }
                    })
                    ->get();
        $checkedInApplicants = Applicant::select('batchs.name', \DB::raw('count(*) as count'))
                    ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
                    ->where('batchs.camp_id', $this->camp->id)
                    ->whereIn('applicants.id', $checkedInData->pluck('applicant_id'))
                    ->groupBy('batchs.name')
                    ->get();
        $batches = $allApplicants->pluck('batch.name')->unique();
        $batchArray = array();
        foreach ($batches as $key => $batch){
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

    public function detailedStatOptimized(Request $request) {
        // 取得報到資料
        $checkedInData = CheckIn::where('check_in_date', Carbon::today()->format('Y-m-d'))->get();
        // 取得梯次
        $batches = Batch::where("camp_id", $this->camp->id)->where([['batch_start', '<=', Carbon::today()], ['batch_end', '>=', Carbon::today()]])->get();
        $batchArray = array();
        // 照梯次取報名人
        $applicantsCount = 0;
        $allApplicants = null;
        $checkedInApplicants = null;
        foreach($batches as $key => $batch){
            $allApplicants = Applicant::where(\DB::raw("fee - deposit"), "<=", 0)
                                ->where("batch_id", $batch->id)
                                ->whereNotNull('group_id')
                                ->where(function($query){
                                    if($this->has_attend_data){
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

    public function uncheckIn(Request $request) {
        if(CheckIn::where('applicant_id', $request->applicant_id)->where('check_in_date', $request->check_in_date)->first()->delete()){
            \Session::flash('message', "報消報到成功。");
        }
        else{
            return back()->withErrors(['取消報到過程發生未知錯誤。']);
        }
        return back();
    }
}
