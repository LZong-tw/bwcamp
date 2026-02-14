<?php

namespace App\Services;
use App\Models\Applicant;
use App\Models\Traffic;

class TrafficService
{
    private ApplicantService $applicantService;
    private CampDataService $campDataService;

    public function __construct(ApplicantService $applicantService, CampDataService $campDataService) {
		$this->applicantService = $applicantService;
		$this->campDataService = $campDataService;
        return;
    }

	// TrafficService.php 或 ApplicantService.php
    public function updateApplicantTraffic(Applicant $applicant, $camp_table, $departFrom, $backTo)
    {
        $traffic = $applicant->traffic ?: new Traffic(['applicant_id' => $applicant->id]);
        [$fare_depart_from, $fare_back_to] = $this->getTrafficFare($camp_table);
        $traffic->depart_from = $departFrom;
        $traffic->back_to = $backTo;
        $traffic->fare = ($fare_depart_from[$departFrom] ?? 0) + ($fare_back_to[$backTo] ?? 0);
        $traffic->save();

        // 更新付款資料
        $applicant = $this->applicantService->fillPaymentData($applicant);
        $applicant->save();

        return [$applicant, $fare_depart_from, $fare_back_to]; // 回傳更新後的物件及費率設定
    }

    public function getTrafficFare($campTable)
    {
        // 取得費率設定 (這部分邏輯建議也可以封裝)
        $fare_depart_from = config('camps_payments.fare_depart_from.' . $campTable) ?? [];
        $fare_back_to = config('camps_payments.fare_back_to.' . $campTable) ?? [];

        return [$fare_depart_from, $fare_back_to];
    }
}
