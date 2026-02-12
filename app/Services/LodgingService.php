<?php

namespace App\Services;
use App\Models\Applicant;
use App\Models\Lodging;

class LodgingService
{
    private ApplicantService $applicantService;
    private CampDataService $campDataService;

    public function __construct(ApplicantService $applicantService, CampDataService $campDataService) {
		$this->applicantService = $applicantService;
		$this->campDataService = $campDataService;
        return;
    }

	// LodgingService.php 或 ApplicantService.php
    public function updateApplicantLodging(Applicant $applicant, $campTable, $roomType, $nights)
    {
        $lodging = $applicant->lodging ?: new Lodging(['applicant_id' => $applicant->id]);
        
        // 取得費率設定 (這部分邏輯建議也可以封裝)
        $fare_room = config('camps_payments.fare_room.' . $campTable) ?? [];
        $fare_room_early_bird = config('camps_payments.fare_room.' . $campTable . '_early_bird') ?? [];
        $fare_room_discount = config('camps_payments.fare_room.' . $campTable . '_discount') ?? [];
        $createdAt = $applicant->created_at;

        if ($campTable == "nycamp") {
            if ($applicant->camp->early_bird_last_day && $createdAt->lte($applicant->camp->early_bird_last_day)) {
                $fare_room = $fare_room_early_bird;
            } elseif ($applicant->camp->discount_last_day && $createdAt->lte($applicant->camp->discount_last_day)) {
                $fare_room = $fare_room_discount;
            }
        }
        if ($campTable == "utcamp") {
            if ($applicant->camp->early_bird_last_day && $createdAt->lte($applicant->camp->early_bird_last_day)) {
                $fare_room = $fare_room_early_bird + $fare_room_discount;
            } elseif ($applicant->camp->discount_last_day && $createdAt->lte($applicant->camp->discount_last_day)) {
                $fare_room = $fare_room + $fare_room_discount;
            }
        }

        $lodging->room_type = $roomType;
        $lodging->nights = $nights;
        $lodging->fare = ($fare_room[$lodging->room_type] ?? 0);
        $lodging->save();

        // 更新付款資料
        $applicant = $this->applicantService->fillPaymentData($applicant);
        $applicant->save();

        return $applicant; // 回傳更新後的物件
    }
}
