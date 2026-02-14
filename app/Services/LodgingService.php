<?php

namespace App\Services;
use App\Models\Applicant;
use App\Models\Camp;
use App\Models\Lodging;
use Carbon\Carbon;

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
    public function updateApplicantLodging(Applicant $applicant, Camp $camp, $roomType, $nights)
    {
        $lodging = $applicant->lodging ?: new Lodging(['applicant_id' => $applicant->id]);
        
        // 取得費率設定 (這部分邏輯建議也可以封裝)
        $fare_room = $this->getLodgingFare($camp, $applicant->created_at);

        $lodging->room_type = $roomType;
        $lodging->nights = $nights ?? 1;
        $lodging->fare = ($fare_room[$lodging->room_type] ?? 0);
        $lodging->save();

        // 更新付款資料
        $applicant = $this->applicantService->fillPaymentData($applicant);
        $applicant->save();

        return [$applicant, $fare_room]; // 回傳更新後的物件
    }

    public function getLodgingFare(Camp $camp, Carbon $date)
    {
        $campTable = $camp->table;
        // 取得費率設定 (這部分邏輯建議也可以封裝)
        $fare_room = config('camps_payments.fare_room.' . $campTable) ?? [];
        $fare_room_early_bird = config('camps_payments.fare_room.' . $campTable . '_early_bird') ?? [];
        $fare_room_discount = config('camps_payments.fare_room.' . $campTable . '_discount') ?? [];

        if ($campTable == "nycamp") {
            //早鳥價<優惠價<正常價
            if ($camp->early_bird_last_day && $date->lte($camp->early_bird_last_day)) {
                $fare_room = $fare_room_early_bird;
            } elseif ($camp->discount_last_day && $date->lte($camp->discount_last_day)) {
                $fare_room = $fare_room_discount;
            }
        } else if ($campTable == "utcamp") {
            //優惠價：兩人同行，比早鳥更優惠
            if ($camp->early_bird_last_day && $date->lte($camp->early_bird_last_day)) {
                $fare_room = $fare_room_early_bird + $fare_room_discount;
            } elseif ($camp->discount_last_day && $date->lte($camp->discount_last_day)) {
                $fare_room = $fare_room + $fare_room_discount;
            }
        } else if ($camp->has_early_bird && $date->lte($camp->early_bird_last_day)) {
            //僅有早鳥價
            $fare_room = $fare_room_early_bird;
        } else  {
            //僅有正常價
            $fare_room = $fare_room;
        } 
        //
        return $fare_room;
    }
}
