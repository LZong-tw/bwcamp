
請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br><br>

@if(now()->gt($applicant->camp->registration_end))
    報名截止時間 {{ $applicant->camp->registration_end }} 已過，您將被列入備取名單。<br>
    錄取者將會有專人連繫，未錄取者則不另行通知，不便之處，尚請見諒。<br>
@else
    本營隊將於 <u>5/29(日)，6/19(日)</u> 分兩波於官網公布錄取名單，並提供錄取查詢。<br>
@endif
<br>
