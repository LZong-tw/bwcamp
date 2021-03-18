<?
namespace App\Services;

use App\Models\Applicant;

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
     * @return 一個報名者 model
     */
    public function fetchApplicantData($camp_id, $table, $id, $group, $number){
        return Applicant::select('applicants.*')
            ->join($table, 'applicants.id', '=', $table . '.applicant_id')
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $camp_id)
            ->where(function ($query) use ($id, $group, $number) {
                $query->where('applicants.id', $id)
                ->orWhere(function ($query) use ($group, $number) {
                    $query->where('group', 'like', $group);
                    $query->where('number', 'like', $number);
                });
            })->first();
    }

    public function checkIfPaidEarlyBird($applicant) {
        // 須為已錄取
        // 如果已錄取，且營隊有早鳥，且報名者已付清款項，則跳過
        if($applicant->is_admitted && $applicant->batch->camp->has_early_bird && ($applicant->fee - $applicant->deposit <= 0)){
            return 0;
        }
        // 其他(無論有無早鳥)，僅檢查報名者是否錄取，已錄取則填入繳費資料
        else if($applicant->is_admitted){
            $applicant = $this->fillPaymentData($applicant);
            $applicant->save();
        }
        return 0;
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
        $data = array_merge(config('camps_payments.general'), config('camps_payments.' . $candidate->batch->camp->table));
        $data["應繳日期"] = $candidate->batch->camp->payment_startdate ?? "0000";
        $data["繳費期限"] = $candidate->batch->camp->set_payment_deadline ?? "000000";
        $data["銷帳編號"] = $data["銷帳流水號前1碼"] . str_pad($candidate->id, 5, '0', STR_PAD_LEFT);
        $candidate->fee = $candidate->batch->camp->set_fee;
        $paymentFlow = new PaymentflowService($data);        
        $candidate->store_first_barcode = $paymentFlow->getStoreFirstBarcode();
        $candidate->store_second_barcode = $paymentFlow->getStoreSecondBarcode();
        $candidate->store_third_barcode = $paymentFlow->getStoreThirdBarcode($candidate->fee ?? 0);
        $candidate->bank_second_barcode = $paymentFlow->getBankSecondBarcode();
        $candidate->bank_third_barcode = $paymentFlow->getBankThirdBarcode($candidate->fee ?? 0);
        $candidate->deposit = $candidate->deposit == null || $candidate->deposit == 0 ? 0 : $candidate->deposit;
        return $candidate;
    }

    public function checkPaymentStatus($applicant){
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
}