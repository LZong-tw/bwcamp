
請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br><br>
@if(now()->gt($applicant->camp->registration_end))
    報名截止時間 {{ $applicant->camp->registration_end }} 已過，您將被列入備取名單（若錄取將另外通知）。
@else
    本營隊將於 <u>{{ $camp_data->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }})</u> 於官網公布錄取名單，並提供錄取查詢。
@endif
<br>