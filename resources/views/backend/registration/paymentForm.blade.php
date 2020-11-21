<p>
    <h5>{{ $applicant->name }}({{ $applicant->gender }})</h5>
    報名序號：{{ $applicant->id }} <br>
    @if($applicant->region)
        分區：{{ $applicant->region }} <br>
    @endif
    @if(isset($applicant->group) && isset($applicant->number))
        錄取序號：{{ $applicant->group.$applicant->number }} <br>
    @endif            
    應繳日期(mmdd)：{{ $campFullData->payment_startdate }} <br>
    繳費期限(民國yymmdd)：{{ $campFullData->payment_deadline }} <br>
    應繳金額：{{ $campFullData->fee }} <br>
    超商第一段條碼：{!! \DNS1D::getBarcodeSVG($applicant->store_first_barcode, 'C39', 1, 50) !!} <br><br>
    超商第二段條碼：{!! \DNS1D::getBarcodeSVG($applicant->store_second_barcode, 'C39', 1, 50) !!} <br><br>
    超商第三段條碼：{!! \DNS1D::getBarcodeSVG($applicant->store_third_barcode, 'C39', 1, 50) !!} <br><br>
    銀行條碼(銷帳編號)：{!! \DNS1D::getBarcodeSVG($applicant->bank_second_barcode, 'C39', 1, 50) !!} <br><br>
    銀行條碼(應繳金額)：{!! \DNS1D::getBarcodeSVG($applicant->bank_third_barcode, 'C39', 1, 50) !!} <br><br>
</p>