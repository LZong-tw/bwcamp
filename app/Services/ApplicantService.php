<?php
namespace App\Services;

use App\Models\Applicant;
use Carbon\Carbon;

class ApplicantService
{
    public function Mandarization($applicant){
        switch($applicant->gender){
            case "M":
                $applicant->gender = "男";
                break;
            case "F":
                $applicant->gender = "女";
                break;
        }
        return $applicant;
    }

    public function convertFormat($title_data, $camp) {
        //匯入報名表時，將各種人理解的內容轉成要寫入database的內容
        //$regions = $camp->regions;  //valid regions
        foreach($title_data as $key => $value) {
            if ($key == 'gender') {
                switch($value){
                    case "男":
                        $title_data[$key] = "M";
                        break;
                    case "女":
                        $title_data[$key] = "F";
                        break;
                }        
            }
            if ($key == 'portrait_agree' || $key == 'profile_agree') {
                switch($value){
                    case "同意":
                        $title_data[$key] = "1";
                        break;
                    case "不同意":
                        $title_data[$key] = "0";
                        break;
                }        
            }
            if ($key == 'region') {
                $region_found = $camp->regions->where('name', $value)->first();
                if ($region_found) {
                    $title_data['region_id'] = $region_found->id;
                }
                //otherwise, leave it null
            }
        }
        return $title_data;
    }

    public function groupAndNumberSeperator($admittedSN){
        $group = substr($admittedSN, 0, 3);
        $number = substr($admittedSN, 3, strlen($admittedSN));
        return compact('group', 'number');
    }

    /**
     * 取得報名者完整資料
     *
     * @param 營隊 ID
     * @param 營隊資料表
     * @param 報名者 ID
     * @param 報名者組別
     * @param 報名者座號
     * @return \App\Models\Applicant
     */
    public function fetchApplicantData($camp_id, $table, $idOrName = null, $group = null, $number = null) {
        $applicant = Applicant::select('applicants.*', $table . '.*', $table . '.id as ""')
            ->join($table, 'applicants.id', '=', $table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $camp_id)
            ->where(function ($query) use ($idOrName, $group, $number) {
                if ($idOrName) {
                    $query->where('applicants.id', $idOrName);
                    $query->orWhere('applicants.name', 'like', '%' . $idOrName . '%');
                }
                if ($group && $number) {
                    $query->orWhere(function ($query) use ($group, $number) {
                        $query->where('group_legacy', 'like', $group);
                        $query->where('number_legacy', 'like', $number);
                    });
                    $query->orWhere(function ($query) use ($group, $number) {
                        $query->whereHas('groupRelation', function ($query) use ($group) {
                            $query->where('alias', 'like', $group);
                        });
                        $query->whereHas('numberRelation', function ($query) use ($number) {
                            $query->where('number', 'like', $number);
                        });
                    });
                }
            })->withTrashed()
            ->first();
        if ($applicant) {
            $applicant->id = $applicant->applicant_id;
        }
        return $applicant;
    }

    public function checkIfPaidEarlyBird($applicant) {
        // 須為已錄取
        // 如果已錄取，或營隊有早鳥且報名者已付清款項，則跳過
        if($applicant->is_admitted || ($applicant->batch->camp->has_early_bird && ($applicant->fee - $applicant->deposit <= 0))){
            return $applicant;
        }
        // 快樂營其他(無論有無早鳥)，僅檢查報名者是否錄取，未錄取表示未繳費完成，則填入繳費資料
        else if($applicant->batch->camp->table == "hcamp" && !$applicant->is_admitted){
            $applicant = $this->fillPaymentData($applicant);
        }
        // 其他(無論有無早鳥)，僅檢查報名者是否錄取，已錄取則填入繳費資料
        else if($applicant->batch->camp->table != "hcamp" && $applicant->is_admitted){
            $applicant = $this->fillPaymentData($applicant);
        }
        return $applicant;
    }

    /**
     * 錄取後，自動生成轉帳資料
     *
     * 使用 Camp 的 set_fee 和 set_payment_deadline，
     * 由 Camp model 本身判斷該提取早鳥價或原價
     *
     * @param 一個報名者 model
     * @param 營隊完整資料
     * @return 一個報名者 model
     */
    public function fillPaymentData($candidate){
        if(!config('camps_payments.' . $candidate->batch->camp->table)){
            return $candidate;
        }
        $data = array_merge(config('camps_payments.general'), config('camps_payments.' . $candidate->batch->camp->table));
        $startdate = Carbon::createFromFormat('Y-m-d', $candidate->batch->camp->payment_startdate ?? "2011-00-00");
        $deadline = Carbon::createFromFormat('Y-m-d', $candidate->batch->camp->set_payment_deadline ?? "2011-00-00");
        $startdate1 = sprintf("%02d%02d", $startdate->month, $startdate->day);
        //"西元年-2011" = 民國年後兩碼
        $deadline1 = sprintf("%02d%02d%02d", $deadline->year-2011, $deadline->month, $deadline->day);
        $data["應繳日期"] = $startdate1;
        $data["繳費期限"] = $deadline1;
        $data["銷帳編號"] = $data["銷帳流水號前1碼"] . str_pad($candidate->id, 5, '0', STR_PAD_LEFT);
        if ($candidate->batch->camp->table == "ycamp") {
            // todo: 應釐清學員的 fee 和交通的 fare 之間的差別
            $candidate->fee = $candidate->traffic?->fare ?? 0;
        } elseif ($candidate->batch->camp->table == "ceocamp") {
            // todo: 應釐清學員的 fee 和交通的 fare 之間的差別
            $candidate->fee = $candidate->lodging?->fare ?? 0;
        } else {
            $candidate->fee = $candidate->batch->camp->set_fee ?? 0;
        }
        $paymentFlow = new PaymentflowService($data);
        $candidate->store_first_barcode = $paymentFlow->getStoreFirstBarcode();
        $candidate->store_second_barcode = $paymentFlow->getStoreSecondBarcode();
        $candidate->store_third_barcode = $paymentFlow->getStoreThirdBarcode($candidate->fee);
        $candidate->bank_second_barcode = $paymentFlow->getBankSecondBarcode();
        $candidate->bank_third_barcode = $paymentFlow->getBankThirdBarcode($candidate->fee);
        $candidate->deposit = $candidate->deposit == null || $candidate->deposit == 0 ? 0 : $candidate->deposit;
        $candidate->save();
        return $candidate;
    }

    public function checkPaymentStatus($applicant){
        if (!$applicant || $applicant->deleted_at) {
            return null;
        }
        $applicant->showCheckInInfo = 0;
        if($applicant->deposit == 0){
            $status = "未繳費";
            if($applicant->fee == 0){
                $status = "無費用";
                $applicant->showCheckInInfo = 1;
            }
        }
        elseif($applicant->fee - $applicant->deposit > 0){
            $status = "已繳部分金額，尚餘" . ($applicant->fee - $applicant->deposit) . "元";
            $applicant->showCheckInInfo = 1;
        }
        elseif($applicant->fee - $applicant->deposit < 0){
            $status = "已繳費，溢繳" . ($applicant->deposit - $applicant->fee) . "元";
            $applicant->showCheckInInfo = 1;
        }
        else{
            $status = "已繳費";
            $applicant->showCheckInInfo = 1;
        }
        $applicant->payment_status = $status;
        return $applicant;
    }

    public function retriveApplicantForSignInSignOut($request) {
        // $group = substr($request->admitted_no, 0, 3);
        // $number = substr($request->admitted_no, 3, 2);
        // todo: 2024/12/15 後需回復
        $request->name = $request->name ?? '0'; //if null, assign some value
        $request->mobile = $request->mobile ?? 'x'; //if null, assign some value
        // $applicant =  Applicant::where('is_admitted', 1)
        //                     ->where(function($query) use ($request){
        // todo: 2024/12/15 後需回復
        $applicant =  Applicant::where(function($query) use ($request){
                                // $query->where('id', $request->query_str)
                                // ->orWhere('name', 'like', '%' . $request->query_str . '%')
                                $query->where('name', 'like', $request->name)
                                      // todo: 2024/12/15 後需回復
                                      ->orWhere(function ($query) use ($request) {
                                           $query->where(\DB::raw("replace(mobile, '-', '')"),  'like', '%' . $request->mobile . '%')
                                                ->orWhere(\DB::raw("replace(mobile, '(', '')"), 'like', '%' . $request->mobile . '%')
                                                ->orWhere(\DB::raw("replace(mobile, ')', '')"), 'like', '%' . $request->mobile . '%')
                                                ->orWhere(\DB::raw("replace(mobile, '（', '')"), 'like', '%' . $request->mobile . '%')
                                                ->orWhere(\DB::raw("replace(mobile, '）', '')"), 'like', '%' . $request->mobile . '%');
                                    });
                            })
                            // ->where([['group', $group], ['number', $number]])
                            ->orderBy('id', 'desc')->first();
        if($applicant?->batch?->camp?->needed_to_reply_attend) {
            return $applicant->where('is_attend', 1)->first();
        }
        return $applicant;
    }

    public function generatesSignMessage($applicant) {
        $signInSignOutObject = $applicant->batch->canSignNow();
        if ($signInSignOutObject) {
            $str = $signInSignOutObject->isSignIn() ? "簽到" : "簽退";
            $message = [
                'status' => true,
                'message' => '可' . $str . '時間：' . Carbon::parse($signInSignOutObject->start)->format('H:i') . ' ~ ' . Carbon::parse($signInSignOutObject->end)->format('H:i')
            ];
        } else {
            $message = [
                'status' => false,
                'message' => '目前非簽到/退時間，僅供檢視記錄'
            ];
        }

        return [$message, $signInSignOutObject];
    }
}
