<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Batch;
use App\Models\Applicant;
use App\Models\Traffic;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ApplicantMail;
use App\Mail\QueuedApplicantMail;
use App\Jobs\SendApplicantMail;
use View;
use App\Traits\EmailConfiguration;
use Intervention\Image\Facades\Image;

class CampController extends Controller
{
    use EmailConfiguration;

    protected $campDataService;
    protected $applicantService;
    protected $batch_id;
    protected $camp_data;
    protected $admission_announcing_date_Weekday;
    protected $admission_confirming_end_Weekday;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CampDataService $campDataService, ApplicantService $applicantService, Request $request)
    {
        $this->applicantService = $applicantService;
        $this->campDataService = $campDataService;
        // 營隊資料，存入 view 全域
        $this->batch_id = $request->route()->parameter('batch_id');
        $this->camp_data = $this->campDataService->getCampData($this->batch_id);
        $admission_announcing_date_Weekday = $this->camp_data['admission_announcing_date_Weekday'];
        $admission_confirming_end_Weekday = $this->camp_data['admission_confirming_end_Weekday'];
        $this->camp_data = $this->camp_data['camp_data'];
        View::share('batch_id', $this->batch_id);
        View::share('camp_data', $this->camp_data);
        View::share('admission_announcing_date_Weekday', $admission_announcing_date_Weekday);
        View::share('admission_confirming_end_Weekday', $admission_confirming_end_Weekday);
        // 動態載入電子郵件設定
        $this->setEmail($this->camp_data->table, $this->camp_data->variant);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function campIndex()
    {
        if($this->camp_data->site_url) {
            return redirect()->to($this->camp_data->site_url);
        }
        $now = Carbon::now();
        $registration_start = Carbon::createFromFormat('Y-m-d', $this->camp_data->registration_start);
        if($now->lt($registration_start)) {
            return '<div style="margin: atuo;">距離開始報名日，還有 <br><img src="http://s.mmgo.io/t/B7Aj" alt="motionmailapp.com" /></div>';
        }

    }

    public function campRegistration(Request $request)
    {
        $today = \Carbon\Carbon::today();
        if($request->isBackend == "目前為後台報名狀態。") {
            $batch = Batch::find($request->batch_id);
        } else {
            $batch = Batch::find($this->batch_id);
        }
        if($batch->is_late_registration_end) {
            $registration_end = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $batch->late_registration_end . "23:59:59");
        } else {
            $registration_end = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $this->camp_data->registration_end . "23:59:59");
        }
        $final_registration_end = $this->camp_data->final_registration_end ? \Carbon\Carbon::createFromFormat("Y-m-d", $this->camp_data->final_registration_end)->endOfDay() : \Carbon\Carbon::today();
        if($today > $registration_end && !isset($request->isBackend)) {
            return view('camps.' . $this->camp_data->table . '.outdated');
        } elseif(isset($request->isBackend) && $today > $final_registration_end) {
            return view('camps.' . $this->camp_data->table . '.outdated')->with('isBackend', '超出最終報名日。');
        } else {
            return view('camps.' . $this->camp_data->table . '.form')
                    ->with('isBackend', $request->isBackend)
                    ->with('batch', Batch::find($request->batch_id));
        }
    }


    public function campRegistrationFormSubmitted(Request $request)
    {
        // 檢查電子郵件是否一致
        if(isset($request->emailConfirm) && ($request->email != $request->emailConfirm)) {
            return view("errorPage")->with('error', '電子郵件不一致，請檢查是否輸入錯誤。');
        }

        if (!file_exists(storage_path("avatars"))) {
            mkdir(storage_path("avatars"), 777, true);
        }

        // 修改資料
        if (isset($request->applicant_id) && !isset($request->useOldData2Register)) {
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
            $formData = $this->campDataService->handleRegion($formData, $this->camp_data->table, $this->camp_data->id);

            try {
                $disk = \Storage::disk('local');
                $path = 'avatars/';
                if(request()->hasFile('avatar')) {
                    $file = request()->file('avatar');
                    $name = $file->hashName();
                }
                if(request()->hasFile('avatar_re')) {
                    $file = request()->file('avatar_re');
                    $name = $file->hashName();
                }

                if($file ?? false) {
                    $disk->put($path, $file);
                    $image = Image::make(storage_path($path . $name))->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save(storage_path($path . $name));
                    $formData['avatar'] = $path . $name;
                }
            } catch(\Throwable $e) {
                logger($e);
            }

            $applicant = \DB::transaction(function () use ($formData) {
                $applicant = Applicant::where('id', $formData['applicant_id'])->first();
                $model = '\\App\\Models\\' . ucfirst($this->camp_data->table);
                $camp = $model::where('applicant_id', $formData['applicant_id'])->first();
                $applicantFillable = $applicant->getFillable();
                $campFillable = $camp->getFillable();
                $applicantData = array();
                $campData = array();
                foreach($formData as $key => $value) {
                    if(in_array($key, $applicantFillable)) {
                        $applicantData[$key] = $value;
                    }
                    if(in_array($key, $campFillable)) {
                        $campData[$key] = $value;
                    }
                }
                $applicant->where('id', $formData['applicant_id'])->update($applicantData);
                $applicant->save();
                $camp->where('applicant_id', $formData['applicant_id'])->update($campData);
                $camp->save();

                return $applicant;
            });
            return view('camps.' . $this->camp_data->table . '.modifyingSuccessful', ['applicant' => $applicant]);
        }
        // 營隊報名
        else {
            $applicant = Applicant::select('applicants.*')
                ->join($this->camp_data->table, 'applicants.id', '=', $this->camp_data->table . '.applicant_id')
                ->join('batchs', 'applicants.batch_id', '=', 'batchs.id')
                //->join('batchs', function($query) {
                //    $query->on('batchs.camp_id', '=', 'camps.id')
                //            ->on('batchs.id', '=', 'applicants.batch_id');
                //})
                //->join('camps', 'camps.id', '=', 'batchs.camp_id')
                ->where('batchs.camp_id', $this->camp_data->id)
                ->where('applicants.name', $request->name)
                ->where('email', $request->email)
                ->withTrashed()->first();
            if ($applicant) {
                if ($applicant->trashed()) {
                    $applicant->restore();
                }
                return view(
                    'camps.' . $this->camp_data->table . '.success',
                    //['isRepeat' => "已成功報名，請勿重複送出報名資料。",
                    ['isRepeat' => "您已報名過，請勿重複報名。底下顯示為您之前的報名序號。",
                    'applicant' => $applicant]
                );
            }
            $request = $this->campDataService->checkBoxToArray($request);
            $formData = $request->toArray();
            $formData['batch_id'] = isset($formData["set_batch_id"]) ? $formData["set_batch_id"] : $this->batch_id;
            $formData = $this->campDataService->handleRegion($formData, $this->camp_data->table, $this->camp_data->id);

            try {
                $disk = \Storage::disk('local');
                $path = 'avatars/';
                if(request()->hasFile('avatar')) {
                    $file = request()->file('avatar');
                    $name = $file->hashName();
                    $result = $disk->put($path, $file);
                    $image = Image::make(storage_path($path . $name))->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save(storage_path($path . $name));
                    $formData['avatar'] = $path . $name;
                }
            } catch(\Throwable $e) {
                logger($e);
            }

            // 報名資料開始寫入資料庫，使用 transaction 確保可以同時將資料寫入不同的表，
            // 或確保若其中一個步驟失敗，不會留下任何殘餘、未完整的資料（屍體）
            // $applicant 為最終報名資料
            $controller = $this;
            $applicant = \DB::transaction(function () use ($formData, $controller) {
                $applicant = Applicant::create($formData);
                $formData['applicant_id'] = $applicant->id;
                $model = '\\App\\Models\\' . ucfirst($this->camp_data->table);
                $model::create($formData);
                if($controller->camp_data->table == 'hcamp') {
                    $applicant = $controller->applicantService->fillPaymentData($applicant);
                    $applicant->save();
                }
                return $applicant;
            });
            // 寄送報名資料
            try {
                // Mail::to($applicant)->send(new ApplicantMail($applicant, $this->camp_data));
                SendApplicantMail::dispatch($applicant->id);
            } catch(\Exception $e) {
                logger($e);
            }
        }

        return view('camps.' . $this->camp_data->table . '.success')->with('applicant', $applicant);
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

        View::share('camp_data', $campCopy);    //replace camp_data

        $applicant_data = $applicantOri->toJson();
        $applicant_data = str_replace("\\r", "", $applicant_data);
        $applicant_data = str_replace("\\n", "", $applicant_data);
        $applicant_data = str_replace("\\t", "", $applicant_data);

        //先不複製，是把資料填到"campCopy"表中顯示，由user自己按報名。
        return view('camps.' . $campCopy->table . '.form')
        //->with('applicant_id', $applicantOri->applicant_id)
        //->with('applicant_batch_id', $applicantOri->batch_id)   //??
        ->with('applicant_data', $applicant_data)                 //處理過一些空白字元的版本
        ->with('applicant_raw_data', $applicantOri)             //資料庫抓出的原始資料,已join
        ->with('isModify', true)
        ->with('useOldData2Register', true)                     //新增：使用舊資料報名
        ->with('batch', $batchCopy)
        ->with('camp_data', $campCopy);
    }

    public function campQueryRegistrationDataPage(Request $request)
    {
        return view('camps.' . $this->camp_data->table . '.query')->with('batch_id_from', $request->batch_id_from);
    }

    /**
     * 查詢/修改報名資料
     * 如果從 query 頁選擇查詢報名資料，則可跨梯次查詢資料，但無法再按下修改資料
     * 如果從 query 頁選擇修改報名資料，則可跨梯次修改資料
     *
     */
    public function campViewRegistrationData(Request $request)
    {
        $applicant = null;
        $isModify = false;
        $campTable = $this->camp_data->table;
        if($request->name != null && $request->sn != null) {
            $applicant = Applicant::select('applicants.*', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)
                ->where('name', $request->name)->withTrashed()->first();
        }
        // 只使用報名 ID（報名序號）查詢資料，僅開放有限的存取
        //（因會有個資洩漏的疑慮，故只在檢視報名資料及報名資料送出後的畫面允許使用）
        // 唯三允許進入修改資料的來源：兩個地方（報名、報名資料修改）的報名資料送出後
        //                        及檢視報名資料頁面所進來的請求
        elseif(Str::contains(request()->headers->get('referer'), 'submit') ||
                Str::contains(request()->headers->get('referer'), 'queryupdate') ||
                Str::contains(request()->headers->get('referer'), 'queryview')) {
            $applicant = Applicant::select('applicants.*', $campTable . '.*')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)->withTrashed()->first();
        }
        if($request->isModify) {
            $isModify = true;
        }
        if($applicant) {
            // 取得報名者梯次資料
            $camp = $applicant->batch->camp;
            $applicant->offsetUnset('files'); // files 僅供後台備註使用，同時，現在 files 的記錄方式若轉為 Json，在前端會出錯
            $applicant_data = $applicant->toJson();
            $applicant_data = str_replace("\\r", "", $applicant_data);
            $applicant_data = str_replace("\\n", "", $applicant_data);
            $applicant_data = str_replace("\\t", "", $applicant_data);
            if($camp->modifying_deadline) {
                $modifying_deadline = Carbon::createFromFormat('Y-m-d', $camp->modifying_deadline);
            } else {
                $modifying_deadline = Carbon::now();
            }
            if($isModify && $modifying_deadline->lt(Carbon::today())) {
                if(!Str::contains(request()->headers->get('referer'), 'queryview')) {
                    return back()->withInput()->withErrors(['很抱歉，報名資料修改期限已過。']);
                } else {
                    return view('camps.' . $campTable . '.form')
                            ->with('applicant_id', $applicant->applicant_id)
                            ->with('applicant_batch_id', $applicant->batch_id)
                            ->with('applicant_data', $applicant_data)
                            ->with('applicant_raw_data', $applicant)
                            ->with('isModify', false)
                            ->with('isBackend', $request->isBackend)
                            ->with('batch', Batch::find($request->batch_id))
                            ->with('camp_data', $camp)
                            ->withErrors(['很抱歉，報名資料修改期限已過。']);
                }
            }
            if($request->batch_id_from) {
                $batchFrom = Batch::find($request->batch_id_from);
                $campFrom = $batchFrom->camp;
                $campAbbrFrom = $campFrom->abbreviation;   //查詢營隊名
                return view('camps.' . $campTable . '.form')
                ->with('applicant_id', $applicant->applicant_id)
                ->with('applicant_batch_id', $applicant->batch_id)
                ->with('applicant_data', $applicant_data)
                ->with('applicant_raw_data', $applicant)
                ->with('isModify', $isModify)
                ->with('isBackend', $request->isBackend)
                ->with('batch', Batch::find($request->batch_id))
                ->with('camp_data', $camp)
                ->with('batch_id_from', $request->batch_id_from)
                ->with('camp_abbr_from', $campAbbrFrom);
            } else {
                return view('camps.' . $campTable . '.form')
                ->with('applicant_id', $applicant->applicant_id)
                ->with('applicant_batch_id', $applicant->batch_id)
                ->with('applicant_data', $applicant_data)
                ->with('applicant_raw_data', $applicant)
                ->with('isModify', $isModify)
                ->with('isBackend', $request->isBackend)
                ->with('batch', Batch::find($request->batch_id))
                ->with('camp_data', $camp);
            }
        } else {
            return '<h2>找不到報名資料，請再次確認是否填寫錯誤。</h2>';
        }
    }

    public function campGetApplicantSN(Request $request)
    {
        $campTable = $this->camp_data->table;
        $applicant = Applicant::select('applicants.id', 'applicants.email', 'applicants.name')
                    ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                    ->where('applicants.name', $request->name);
        if($request->mobile) {
            $applicant = $applicant->where('mobile', $request->mobile)
            ->withTrashed()->first();
        } else {
            $applicant = $applicant->where('birthyear', ltrim($request->birthyear, '0'))
            ->where('birthmonth', ltrim($request->birthmonth, '0'));
            if($campTable == 'acamp' || $campTable == 'ceocamp') {
                $applicant = $applicant->withTrashed()->first();
            } else {
                $applicant = $applicant->where('birthday', ltrim($request->birthday, '0'))
                ->withTrashed()->first();
            }
        }
        if($applicant) {
            // 寄送報名序號
            // Mail::to($applicant)->send(new ApplicantMail($applicant, $this->camp_data, true));
            SendApplicantMail::dispatch($applicant->id, true);
            return view('camps.' . $campTable . '.getSN')
                ->with('applicant', $applicant);
        } else {
            return view('camps.' . $campTable . '.getSN')
                ->with('error', "找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。");
        }
    }

    public function campViewAdmission()
    {
        return view('camps.' . $this->camp_data->table . ".queryadmission", ["camp" => $this->camp_data]);
    }

    public function campConfirmCancel(Request $request)
    {
        $applicant = Applicant::where('id', $request->sn)
                        ->where('name', $request->name)
                        ->where('idno', $request->idno)
                        ->withTrashed()
                        ->first();
        if($applicant) {
            return view('camps.' . $this->camp_data->table . '.confirm_cancel', compact('applicant'));
        } else {
            return back()->withInput()->withErrors(["找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。"]);
        }
    }

    public function campCancellation(Request $request)
    {
        try {
            if(Applicant::find($request->sn)->delete()) {
                return view('camps.' . $this->camp_data->table . '.cancel_successful');
            }
        } catch(\Exception $e) {
            logger($e);
            return "<h2>取消時發生未預期錯誤，請確認報名資料是否正確，或向主辦方回報。</h2>";
        }
    }

    public function restoreCancellation(Request $request)
    {
        if(Applicant::withTrashed()->find($request->sn)->restore()) {
            $applicant = Applicant::find($request->sn);
            return view('camps.' . $this->camp_data->table . '.restore_successful', compact('applicant'));
        }
        return "<h2>回復時發生未預期錯誤，請向主辦方回報。</h2>";
    }

    public function campQueryAdmission(Request $request)
    {
        $campTable = $this->camp_data->table;
        $camp = $this->camp_data;
        $applicant = null;

        $request->validate([
            'name' => 'required',
            'sn' => 'required|integer'
        ]);

        if($request->name != null && $request->sn != null) {
            $applicant = Applicant::with('batch', 'camp')
                ->select('applicants.*', $campTable . '.*', 'applicants.id as applicant_id')
                ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
                ->where('applicants.id', $request->sn)
                ->where('name', $request->name)
                ->withTrashed()->first();
        }

        if($applicant && $applicant->camp->id == $camp->id) {
            $traffic = null;
            $applicant->id = $applicant->applicant_id;
            if($applicant->batch->id == 132 && $applicant->traffic == null) {
                //for 2023 ycamp, if null, create one
                $newTraffic = array();
                $newTraffic['applicant_id'] = $applicant->id;
                $newTraffic['depart_from'] = "自往";
                $newTraffic['back_to'] = "自回";
                $newTraffic['fare'] = "0";
                $newTraffic['deposit'] = "0";
                Traffic::create($newTraffic);
                //relink applicant1 and newly created traffic??
                $applicant1 = Applicant::find($request->sn);
                //update barcode
                $applicant1 = $this->applicantService->fillPaymentData($applicant1);
                $applicant1->save();
                $traffic = $applicant1->traffic;
	    } else {
		//if not null, retrieve it
		$traffic = $applicant->traffic;
	    }

            $applicant = $this->applicantService->checkPaymentStatus($applicant);
            //for 2023大專教師營
            if ($applicant->camp->table == 'utcamp') {
                $group = $applicant->group;
                if (str_contains($group, 'B')) {
                    $applicant->xsession = '桃園場';
                    $applicant->xaddr = '桃園市中壢區成章四街120號';
                } elseif (str_contains($group, 'C')) {
                    $applicant->xsession = '新竹場';
                    $applicant->xaddr = '新竹縣新豐鄉新興路1號';
                } elseif (str_contains($group, 'D')) {
                    $applicant->xsession = '台中場';
                    $applicant->xaddr = '台中市西區民生路227號';
                } elseif (str_contains($group, 'E')) {
                    $applicant->xsession = '雲林場';
                    $applicant->xaddr = '雲林縣斗六市慶生路6號';
                } elseif (str_contains($group, 'F')) {
                    $applicant->xsession = '台南場';
                    $applicant->xaddr = '台南市東區大學路1號';
                } elseif (str_contains($group, 'G')) {
                    $applicant->xsession = '高雄場';
                    $applicant->xaddr = '高雄市新興區中正四路53號12樓之7';
                } else {
                    $applicant->xsession = '台北場';
                    $applicant->xaddr = '台北市南京東路四段165號九樓 福智學堂';
                }
            }
            return view('camps.' . $campTable . ".admissionResult", compact('applicant','traffic'));
        } else{
            return back()->withInput()->withErrors(["找不到報名資料，請確認是否已成功報名，或是輸入了錯誤的查詢資料。"]);
        }
    }

    public function showDownloads()
    {
        return view('camps.' . $this->camp_data->table . '.downloads');
    }

    public function downloadPaymentForm(Request $request)
    {
        ini_set('memory_limit', -1);
        $applicant = Applicant::find($request->applicant_id);
        $applicant = $this->applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->save();
        return \PDF::loadView('camps.' . $this->camp_data->table . '.paymentFormPDF', compact('applicant'))->setPaper('a3')->download('Payment_' . \Carbon\Carbon::now()->format('YmdHis') . $applicant->id . '.pdf');
    }

    public function downloadCheckInNotification(Request $request)
    {
        $applicant = Applicant::find($request->applicant_id);
        return \PDF::loadView('camps.' . $this->camp_data->table . '.checkInMail', compact('applicant'))->download(\Carbon\Carbon::now()->format('YmdHis') . $this->camp_data->table . $applicant->id . '報到通知單.pdf');
    }

    public function downloadCheckInQRcode(Request $request)
    {
        $applicant = Applicant::find($request->applicant_id);
        $qr_code = \DNS2D::getBarcodePNG('{"applicant_id":' . $applicant->id . '}', 'QRCODE');
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($applicant->batch->camp->fullName . ' QR code 報到單<br>梯次：' . $applicant->batch->name . '<br>錄取序號：' . $applicant->group . $applicant->number . '<br>姓名：' . $applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
        return $pdf->download(\Carbon\Carbon::now()->format('YmdHis') . $this->camp_data->table . $applicant->id . 'QR Code 報到單.pdf');
    }

    public function getCampTotalRegisteredNumber()
    {
        $batches = Batch::where('camp_id', $this->camp_data->id)->get()->pluck('id');
        return Applicant::whereIn('batch_id', $batches)->withTrashed()->count();
    }

    public function toggleAttend(Request $request)
    {
        $applicant = Applicant::find($request->id);
        //other camps
        if($request->camp == "ycamp") {
            if($request->cancel) {$applicant->is_attend = 0;}
            else {$applicant->is_attend = 1;} //reconfirm
        } else {
            if($request->confirmation_no) {
                $applicant->is_attend = 0;
            } else{
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

    public function modifyTraffic(Request $request) {
        $applicant = Applicant::find($request->id);
        $traffic = $applicant->traffic;
        if (!$traffic) {
            $traffic = new Traffic;
            $traffic->applicant_id = $applicant->id;
        }
        $traffic->depart_from = $request->depart_from;
        $traffic->back_to = $request->back_to;
        if ($request->camp == "ycamp") {
            if ($request->depart_from == "台北專車")
                $from_fare = 400;
            elseif  ($request->depart_from == "桃園專車")
                $from_fare = 350;
            elseif  ($request->depart_from == "新竹專車")
                $from_fare = 250;
            elseif  ($request->depart_from == "台中專車")
                $from_fare = 200;
            elseif  ($request->depart_from == "台南專車")
                $from_fare = 200;
            elseif  ($request->depart_from == "高雄專車")
                $from_fare = 350;
            else
                $from_fare = 0;

            if ($request->back_to == "台北專車")
                $back_fare = 400;
            elseif  ($request->back_to == "桃園專車")
                $back_fare = 350;
            elseif  ($request->back_to == "新竹專車")
                $back_fare = 250;
            elseif  ($request->back_to == "台中專車")
                $back_fare = 200;
            elseif  ($request->back_to == "台南專車")
                $back_fare = 200;
            elseif  ($request->back_to == "高雄專車")
                $back_fare = 350;
            else
                $back_fare = 0;
        }
        $traffic->fare = $from_fare + $back_fare;
        $traffic->save();
        //update barcode
        $applicant = $this->applicantService->fillPaymentData($applicant);
        $applicant->save();

        return redirect(route('showadmit', ['batch_id' => $applicant->batch_id, 'sn' => $applicant->id, 'name' => $applicant->name]));
    }

    public function showCampPayment() {
        return view('camps.' . $this->camp_data->table . '.payment');
    }

    public function returnBatches()
    {
        return;
    }
}
